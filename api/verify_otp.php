<?php
/**
 * API: Verify OTP + Login / Register
 * POST /api/verify_otp.php
 * Body: { "email": "...", "otp": "123456", "role": "freelancer|client|admin", "name": "..." }
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

Security::verifyCsrf();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Security::jsonError('Method not allowed.', 405);
}

$body  = json_decode(file_get_contents('php://input'), true) ?? [];

// ── Input extraction & sanitization ────────────
// Accept both 'email' and 'contact' keys for compatibility
$email = filter_var(
    trim($body['email'] ?? $body['contact'] ?? ''),
    FILTER_SANITIZE_EMAIL
);
$otp   = preg_replace('/\D/', '', trim($body['otp'] ?? ''));           // digits only
$role  = in_array($body['role'] ?? '', ['freelancer','client','admin'])
         ? $body['role'] : 'freelancer';
$name  = Security::clean($body['name'] ?? '');
$password = trim($body['password'] ?? '');

// ── Validate email ──────────────────────────────
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    Security::jsonError('Invalid email address provided.');
}

// ── Validate OTP format ─────────────────────────
if (!preg_match('/^\d{6}$/', $otp)) {
    Security::jsonError('OTP must be exactly 6 digits.');
}

// ── Rate limit verify attempts (5 per 15 min) ──
Security::rateLimit('otp_verify_' . md5($email), 5, 900);

// ── Verify OTP from DB ──────────────────────────
if (!OTP::verify($email, $otp)) {
    Logger::warn("OTP verification failed for: $email");
    Security::jsonError('Invalid or expired OTP. Please request a new one.');
}

// ── Find or create user ─────────────────────────
$user = DB::row("SELECT * FROM users WHERE email = ?", [$email]);

if (!$user) {
    // ── New user: Register ──────────────────────
    if (empty($name) || $name === 'User') {
        // Auto-generate name from email prefix
        $name = ucfirst(preg_replace('/[^a-z0-9]/i', '', explode('@', $email)[0]));
    }

    if (empty($password)) {
        Security::jsonError('Password is required for registration.');
    }
    if (strlen($password) < 8) {
        Security::jsonError('Password must be at least 8 characters long.');
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    DB::query(
        "INSERT INTO users (fullname, email, password, role, verified, available, created_at, updated_at)
         VALUES (?, ?, ?, ?, 1, 1, NOW(), NOW())",
        [$name, $email, $hashedPassword, $role]
    );
    $userId = (int) DB::lastId();

    // ── Create role-specific profile ────────────
    if ($role === 'freelancer') {
        $bonus = (int) App::setting('signup_bonus_coins', 20);

        // Create freelancer profile
        DB::query(
            "INSERT IGNORE INTO freelancer_profiles (user_id, coin_balance) VALUES (?, ?)",
            [$userId, $bonus]
        );
        // Log welcome bonus coins
        DB::query(
            "INSERT INTO coin_transactions (user_id, amount, type, description, created_at)
             VALUES (?, ?, 'bonus', 'Welcome Signup Bonus 🎁', NOW())",
            [$userId, $bonus]
        );
        Logger::info("New freelancer registered: $email (bonus: $bonus coins)");

    } elseif ($role === 'client') {
        DB::query(
            "INSERT IGNORE INTO client_profiles (user_id) VALUES (?)",
            [$userId]
        );
        Logger::info("New client registered: $email");

    } elseif ($role === 'admin') {
        // Admin registration only allowed in local environment
        if (!APP_DEBUG) {
            Security::jsonError('Admin registration is not permitted.', 403);
        }
        Logger::warn("Admin registered in dev mode: $email");
    }

    $user = DB::row("SELECT * FROM users WHERE id = ?", [$userId]);

} else {
    // ── Returning user: Login ───────────────────
    DB::query(
        "UPDATE users SET updated_at = NOW() WHERE id = ?",
        [$user['id']]
    );
    Logger::info("User logged in: $email (role: {$user['role']})");
}

// ── Set PHP session ─────────────────────────────
Auth::setSession($user);

// ── Generate JWT for Flutter API ────────────────
$jwt = Security::generateJWT([
    'id'    => $user['id'],
    'email' => $user['email'],
    'role'  => $user['role'],
    'iat'   => time(),
    'exp'   => time() + 86400,   // 24 hours
]);

// ── Determine redirect URL ──────────────────────
$redirect = Auth::dashboardUrl($user['role']);

Security::jsonOk([
    'message'  => 'Login successful! Welcome back.',
    'user_id'  => $user['id'],
    'name'     => $user['fullname'] ?? $user['name'] ?? $name,
    'email'    => $user['email'],
    'role'     => $user['role'],
    'token'    => $jwt,        // For Flutter/Mobile API usage
    'redirect' => $redirect,
]);
