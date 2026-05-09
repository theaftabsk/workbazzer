<?php
/**
 * WorkBazar — OTP via SMTP + DB storage
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

class OTP {
    /** Generate + store OTP, return it */
    public static function generate(string $contact): int {
        $otp = random_int(100000, 999999);
        $expires = date('Y-m-d H:i:s', time() + OTP_EXPIRY_MIN * 60);

        DB::query(
            "INSERT INTO otp_verifications (contact, otp, expires_at, used, created_at)
             VALUES (?, ?, ?, 0, NOW())
             ON DUPLICATE KEY UPDATE otp=?, expires_at=?, used=0, created_at=NOW()",
            [$contact, $otp, $expires, $otp, $expires]
        );
        return $otp;
    }

    /** Verify OTP — returns true on success, false on fail */
    public static function verify(string $contact, string $otp): bool {
        $row = DB::row(
            "SELECT id FROM otp_verifications
             WHERE contact=? AND otp=? AND used=0 AND expires_at > NOW()
             ORDER BY id DESC LIMIT 1",
            [$contact, $otp]
        );
        if (!$row) return false;
        // Mark as used
        DB::query("UPDATE otp_verifications SET used=1 WHERE id=?", [$row['id']]);
        return true;
    }

    /** Send OTP via Mailer class */
    public static function sendSMTP(string $toEmail, string $toName, int $otp): bool {
        $subject = 'Your WorkBazar OTP: ' . $otp;
        $body = "
        <div style='font-family:Arial,sans-serif;padding:24px;background:#f8fafc'>
          <h2 style='color:#4f46e5'>WorkBazar</h2>
          <p>Hello <b>$toName</b>,</p>
          <p>Your OTP for login/registration is:</p>
          <div style='font-size:2.5rem;font-weight:800;color:#4f46e5;letter-spacing:8px;padding:16px;background:#eef2ff;border-radius:8px;text-align:center'>$otp</div>
          <p style='color:#94a3b8;font-size:0.85rem;margin-top:16px'>Valid for " . OTP_EXPIRY_MIN . " minutes. Do not share this with anyone.</p>
          <p style='color:#94a3b8;font-size:0.75rem'>WorkBazar — India's Freelance Lead Marketplace</p>
        </div>";

        return Mailer::send($toEmail, $toName, $subject, $body);
    }
}
