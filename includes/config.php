<?php
/**
 * WorkBazar — Enterprise Configuration
 * ⚠️  Never commit real credentials to Git
 */

// ── Environment Detection ────────────────────────
$isLocal = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']);
define('APP_ENV',   $isLocal ? 'local' : 'production');
define('APP_DEBUG', $isLocal);

// ── Database ─────────────────────────────────────
define('DB_HOST',    'localhost');
define('DB_NAME',    'u849033904_aaaa');
define('DB_USER',    'u849033904_aaaa');
define('DB_PASS',    '2bXwC!EVp*');
define('DB_CHARSET', 'utf8mb4');

// ── Application ──────────────────────────────────
define('APP_NAME', 'WorkBazar');
define('APP_URL',  $isLocal ? 'http://localhost' : 'https://blue-tiger-674937.hostingersite.com');

// ── Security & Session ───────────────────────────
define('SESSION_NAME',     'WB_SESS_ID');
define('SESSION_LIFETIME', 86400);           // 24 hours
define('JWT_SECRET',       'wb_jwt_super_secret_2026!change_this');

// ── OTP Settings ─────────────────────────────────
define('OTP_EXPIRY_MIN',  10);   // OTP valid for 10 minutes
define('OTP_RATE_LIMIT',   3);   // Max 3 sends per hour per email

// ── SMTP Mail (OTP via Email) ─────────────────────
// Use Gmail App Password (not your login password)
// Gmail → Security → 2FA → App passwords → Generate
define('MAIL_HOST',      'smtp.gmail.com');
define('MAIL_PORT',       465);         // MUST be 465 for direct SSL connection
define('MAIL_SECURE',    'ssl');
define('MAIL_USER',      'aftabsk741156@gmail.com');           // ← your Gmail here
define('MAIL_PASS',      'fygp lbkb cnqs byje');           // ← Gmail App Password here
define('MAIL_FROM',      'noreply@workbazar.in');
define('MAIL_FROM_NAME', 'WorkBazar');

// ── Razorpay ─────────────────────────────────────
define('RAZORPAY_KEY_ID',     'rzp_test_SmPhTPgf2falxZ');
define('RAZORPAY_KEY_SECRET', 'euaEc3GnfqTkK5o8JZDu2s6o');
