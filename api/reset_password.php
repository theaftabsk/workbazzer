<?php
/**
 * API: Reset Password
 * POST /api/reset_password.php
 * Body: { "token": "...", "password": "...", "confirm_password": "..." }
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

Security::verifyCsrf();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Security::jsonError('Method not allowed.', 405);
}

$body            = json_decode(file_get_contents('php://input'), true) ?? [];
$token           = Security::clean($body['token'] ?? '');
$password        = $body['password'] ?? '';
$confirmPassword = $body['confirm_password'] ?? '';

if (empty($token)) {
    Security::jsonError('Invalid reset link. Please request a new one.');
}
if (strlen($password) < 8) {
    Security::jsonError('Password must be at least 8 characters long.');
}
if ($password !== $confirmPassword) {
    Security::jsonError('Passwords do not match. Please try again.');
}

// ── Validate token ──────────────────────────────
$resetRow = DB::row(
    "SELECT * FROM password_reset_tokens 
     WHERE token = ? AND used = 0 AND expires_at > NOW()",
    [$token]
);

if (!$resetRow) {
    Security::jsonError('This reset link is invalid or has expired. Please request a new one.');
}

// ── Check user exists ───────────────────────────
$user = DB::row("SELECT id FROM users WHERE email = ?", [$resetRow['email']]);
if (!$user) {
    Security::jsonError('Account not found.');
}

// ── Update password ─────────────────────────────
$hashed = password_hash($password, PASSWORD_DEFAULT);
DB::query("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?", [$hashed, $user['id']]);

// ── Mark token as used ──────────────────────────
DB::query("UPDATE password_reset_tokens SET used = 1 WHERE token = ?", [$token]);

Logger::info("Password reset successful for: " . $resetRow['email']);

Security::jsonOk(['message' => 'Password reset successful! You can now log in.', 'redirect' => '/auth/login.php']);
