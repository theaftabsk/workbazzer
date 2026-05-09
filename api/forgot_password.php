<?php
/**
 * API: Forgot Password — Send Reset Email
 * POST /api/forgot_password.php
 * Body: { "email": "..." }
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

Security::verifyCsrf();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Security::jsonError('Method not allowed.', 405);
}

$body  = json_decode(file_get_contents('php://input'), true) ?? [];
$email = filter_var(trim($body['email'] ?? ''), FILTER_SANITIZE_EMAIL);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    Security::jsonError('Please enter a valid email address.');
}

// Rate limit
Security::rateLimit('forgot_' . md5($email), 3, 3600);

// Always return success to prevent email enumeration
$user = DB::row("SELECT id, fullname FROM users WHERE email = ?", [$email]);

if ($user) {
    // Generate secure token
    $token     = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 hour

    // Invalidate old tokens
    DB::query("UPDATE password_reset_tokens SET used = 1 WHERE email = ?", [$email]);

    // Store new token
    DB::query(
        "INSERT INTO password_reset_tokens (email, token, expires_at) VALUES (?, ?, ?)",
        [$email, $token, $expiresAt]
    );

    $resetUrl  = APP_URL . '/auth/reset-password.php?token=' . $token;
    $firstName = explode(' ', $user['fullname'])[0];
    $year      = date('Y');

    $htmlBody = <<<HTML
<!DOCTYPE html><html><head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#f4f7f4;font-family:'Helvetica Neue',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7f4;padding:40px 0;">
    <tr><td align="center">
      <table width="560" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);">
        <tr><td style="background:#0d1117;padding:32px 40px;text-align:center;">
          <div style="font-size:24px;font-weight:800;color:#fff;">Work<span style="color:#1dbf73;">Bazar</span></div>
          <div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:4px;">Enterprise Freelance Platform</div>
        </td></tr>
        <tr><td style="padding:40px;">
          <h2 style="margin:0 0 12px;font-size:22px;font-weight:800;color:#0d1117;">Reset Your Password 🔐</h2>
          <p style="margin:0 0 24px;color:#6e7a8a;font-size:15px;line-height:1.7;">Hi <strong>$firstName</strong>, we received a request to reset your WorkBazar password. Click the button below to set a new password. This link expires in <strong>1 hour</strong>.</p>
          <div style="text-align:center;margin:32px 0;">
            <a href="$resetUrl" style="background:#1dbf73;color:#fff;padding:16px 40px;border-radius:12px;text-decoration:none;font-weight:800;font-size:16px;display:inline-block;">Reset My Password</a>
          </div>
          <p style="margin:0;color:#6e7a8a;font-size:13px;line-height:1.6;">If the button doesn't work, copy this link: <br><a href="$resetUrl" style="color:#1dbf73;word-break:break-all;">$resetUrl</a></p>
          <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:14px 18px;margin-top:24px;">
            <p style="margin:0;font-size:13px;color:#991b1b;">⚠️ If you didn't request this, please ignore this email. Your account is safe.</p>
          </div>
        </td></tr>
        <tr><td style="background:#f9faf9;padding:20px 40px;border-top:1px solid #e2e8e2;text-align:center;">
          <p style="margin:0;font-size:12px;color:#b0bab0;">© $year WorkBazar® · Developed by ITVEXO</p>
        </td></tr>
      </table>
    </td></tr>
  </table>
</body></html>
HTML;

    Mailer::send($email, $user['fullname'], 'Reset Your WorkBazar Password', $htmlBody);
    Logger::info("Password reset email sent to: $email");
}

// Always return success (security: don't reveal if email exists)
Security::jsonOk(['message' => 'If this email has an account, a reset link has been sent.']);
