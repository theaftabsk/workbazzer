<?php
/**
 * API: Password-Based Login
 * POST /api/login.php
 * Body: { "email": "...", "password": "...", "role": "freelancer|client|admin" }
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

Security::verifyCsrf();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Security::jsonError('Method not allowed.', 405);
}

$body     = json_decode(file_get_contents('php://input'), true) ?? [];
$email    = filter_var(trim($body['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$password = trim($body['password'] ?? '');
$role     = in_array($body['role'] ?? '', ['freelancer','client','admin']) ? $body['role'] : 'freelancer';

// ── Validate inputs ─────────────────────────────
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    Security::jsonError('Please enter a valid email address.');
}
if (empty($password)) {
    Security::jsonError('Please enter your password.');
}

// ── Rate limit (5 attempts per 10 min) ──────────
Security::rateLimit('login_' . md5($email), 5, 600);

// ── Find user ───────────────────────────────────
$user = DB::row("SELECT * FROM users WHERE email = ? AND role = ?", [$email, $role]);

if (!$user) {
    Logger::warn("Login failed — no account: $email (role: $role)");
    Security::jsonError('No account found with this email. Please register first.');
}

// ── Verify password ─────────────────────────────
if (empty($user['password']) || !password_verify($password, $user['password'])) {
    Logger::warn("Login failed — wrong password: $email");
    Security::jsonError('Incorrect password. Please try again or use Forgot Password.');
}

// ── Update last seen ────────────────────────────
DB::query("UPDATE users SET updated_at = NOW() WHERE id = ?", [$user['id']]);

// ── Set session ─────────────────────────────────
Auth::setSession($user);

// ── JWT for mobile ──────────────────────────────
$jwt = Security::generateJWT([
    'id'    => $user['id'],
    'email' => $user['email'],
    'role'  => $user['role'],
]);

Logger::info("User logged in via password: $email (role: {$user['role']})");

Security::jsonOk([
    'message'  => 'Login successful! Welcome back.',
    'user_id'  => $user['id'],
    'name'     => $user['fullname'],
    'role'     => $user['role'],
    'token'    => $jwt,
    'redirect' => Auth::dashboardUrl($user['role']),
]);
