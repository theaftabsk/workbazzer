<?php
/**
 * API: Send Email OTP via SMTP
 * POST /api/send_otp.php
 * Body: { "email": "user@example.com" }
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

Security::verifyCsrf();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Security::jsonError('Method not allowed.', 405);
}

$body  = json_decode(file_get_contents('php://input'), true) ?? [];
$email = filter_var(trim($body['email'] ?? ''), FILTER_SANITIZE_EMAIL);

// ── Validate email ──────────────────────────────
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    Security::jsonError('Please enter a valid email address.');
}

// ── Rate limit: max 3 OTPs per hour per email ───
Security::rateLimit('otp_send_' . md5($email), OTP_RATE_LIMIT, 3600);

// ── Check existence based on context ────────────
$context = $body['context'] ?? 'register'; // 'register' or 'login'
$userExists = DB::row("SELECT id FROM users WHERE email = ?", [$email]);

if ($context === 'login' && !$userExists) {
    Security::jsonError('No account found with this email. Please register first.');
}

if ($context === 'register' && $userExists) {
    Security::jsonError('An account with this email already exists. Please log in.');
}

// ── Generate OTP & save to DB ───────────────────
$otp = OTP::generate($email);

// ── Build premium HTML email ────────────────────
$appName   = APP_NAME;
$expiryMin = OTP_EXPIRY_MIN;
$year      = date('Y');

$htmlBody = <<<HTML
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width">
</head>
<body style="margin:0;padding:0;background:#f4f7f4;font-family:'Helvetica Neue',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7f4;padding:40px 0;">
    <tr><td align="center">
      <table width="560" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);">
        <!-- Header -->
        <tr>
          <td style="background:#0d1117;padding:32px 40px;text-align:center;">
            <div style="font-size:24px;font-weight:800;color:#fff;letter-spacing:-0.5px;">
              Work<span style="color:#1dbf73;">Bazar</span>
            </div>
            <div style="font-size:12px;color:rgba(255,255,255,.5);margin-top:4px;">Enterprise Freelance Platform</div>
          </td>
        </tr>
        <!-- Body -->
        <tr>
          <td style="padding:40px;">
            <h2 style="margin:0 0 8px;font-size:22px;font-weight:800;color:#0d1117;">Your Login OTP</h2>
            <p style="margin:0 0 28px;color:#6e7a8a;font-size:15px;line-height:1.6;">
              Use the code below to verify your identity on {$appName}. 
              This code expires in <strong>{$expiryMin} minutes</strong>.
            </p>
            <!-- OTP Box -->
            <div style="background:#f4f7f4;border-radius:12px;padding:28px;text-align:center;margin-bottom:28px;">
              <div style="font-size:48px;font-weight:800;color:#1dbf73;letter-spacing:12px;">{$otp}</div>
              <div style="font-size:12px;color:#6e7a8a;margin-top:8px;">Enter this code in WorkBazar</div>
            </div>
            <div style="background:#fff8e1;border:1px solid #fde68a;border-radius:10px;padding:14px 18px;margin-bottom:24px;">
              <p style="margin:0;font-size:13px;color:#92400e;">
                ⚠️ <strong>Never share this OTP</strong> with anyone — WorkBazar will never ask for it.
                If you didn't request this, please ignore this email.
              </p>
            </div>
            <p style="margin:0;color:#6e7a8a;font-size:13px;line-height:1.6;">
              This OTP is valid for <strong>{$expiryMin} minutes</strong> only and can only be used once.
            </p>
          </td>
        </tr>
        <!-- Footer -->
        <tr>
          <td style="background:#f9faf9;padding:20px 40px;border-top:1px solid #e2e8e2;text-align:center;">
            <p style="margin:0;font-size:12px;color:#b0bab0;">
              © {$year} WorkBazar® · Developed by ITVEXO · Enterprise Software Solutions
            </p>
          </td>
        </tr>
      </table>
    </td></tr>
  </table>
</body>
</html>
HTML;

$textBody = "Your WorkBazar OTP is: $otp\nThis code expires in $expiryMin minutes.\nNever share this code with anyone.";

// ── Send via SMTP ───────────────────────────────
$emailSent = Mailer::send($email, 'User', "Your WorkBazar OTP: $otp", $htmlBody);

if (!$emailSent) {
    Logger::error("SMTP failed for: $email");
    Security::jsonError('Could not send email. Please check SMTP configuration or try again.');
}

Logger::info("OTP email sent successfully to: $email");

Security::jsonOk([
    'message' => 'OTP sent to your email. Please check your inbox.',
    // Only expose OTP in local dev mode, never in production
    'dev_otp' => (APP_ENV === 'local' || APP_DEBUG) ? $otp : null,
]);
