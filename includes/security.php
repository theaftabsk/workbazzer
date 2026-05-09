<?php
/**
 * Enterprise Security Engine
 * Covers: CSRF, JWT, CORS, Rate Limiting, Sanitization, Advanced HTTP Headers
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

class Security {

    // ── Secure Session Management ─────────────────────────────────
    public static function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_set_cookie_params([
                'lifetime' => SESSION_LIFETIME,
                'path'     => '/',
                'secure'   => APP_ENV === 'production', // Requires HTTPS
                'httponly' => true,                     // Prevents JS access
                'samesite' => 'Strict',                 // Prevents CSRF
            ]);
            session_start();
        }
        
        // Anti-Session Fixation & Hijacking
        if (empty($_SESSION['_last_regen']) || time() - $_SESSION['_last_regen'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['_last_regen'] = time();
            $_SESSION['_user_ip'] = self::getIp();
            $_SESSION['_user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        }

        // Validate session environment to prevent theft
        if (isset($_SESSION['_user_ip']) && $_SESSION['_user_ip'] !== self::getIp()) {
            session_unset();
            session_destroy();
            self::jsonError('Security breach detected. Session terminated.', 403);
        }
    }

    // ── CSRF Protection ───────────────────────────────────────────
    public static function csrfToken(): string {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function verifyCsrf(): void {
        $token = $_POST['_csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        if (!hash_equals($_SESSION['_csrf'] ?? '', $token)) {
            Logger::security('CSRF token mismatch', ['ip' => self::getIp(), 'uri' => $_SERVER['REQUEST_URI']]);
            self::jsonError('Invalid security token. Please refresh the page.', 403);
        }
    }

    public static function csrfField(): string {
        return '<input type="hidden" name="_csrf" value="' . self::csrfToken() . '">';
    }

    // ── Flutter API JWT Authentication (Stateless) ────────────────
    public static function generateJWT(array $payload): string {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload['iat'] = time();
        $payload['exp'] = time() + 86400; // 1 day expiry
        $payload = json_encode($payload);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, JWT_SECRET, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function verifyJWT(string $jwt): ?array {
        $tokenParts = explode('.', $jwt);
        if (count($tokenParts) !== 3) return null;

        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, JWT_SECRET, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        if (!hash_equals($base64UrlSignature, $signatureProvided)) {
            Logger::security('JWT signature verification failed');
            return null;
        }

        $payloadArray = json_decode($payload, true);
        if (isset($payloadArray['exp']) && $payloadArray['exp'] < time()) {
            return null; // Expired
        }

        return $payloadArray;
    }

    // ── Database-Backed Rate Limiting (DDoS Protection) ───────────
    public static function rateLimit(string $key, int $maxAttempts, int $decaySeconds): void {
        $ip = self::getIp();
        $cacheKey = 'rate_' . md5($key . '_' . $ip);

        $row = DB::row("SELECT attempts, reset_at FROM rate_limits WHERE cache_key=?", [$cacheKey]);

        if ($row && strtotime($row['reset_at']) > time()) {
            if ((int)$row['attempts'] >= $maxAttempts) {
                $wait = ceil((strtotime($row['reset_at']) - time()) / 60);
                Logger::security("Rate limit exceeded for $key", ['ip' => $ip]);
                self::jsonError("Too many attempts. Wait {$wait} minute(s).", 429);
            }
            DB::query("UPDATE rate_limits SET attempts=attempts+1 WHERE cache_key=?", [$cacheKey]);
        } else {
            DB::query(
                "INSERT INTO rate_limits (cache_key, attempts, reset_at)
                 VALUES (?, 1, DATE_ADD(NOW(), INTERVAL ? SECOND))
                 ON DUPLICATE KEY UPDATE attempts=1, reset_at=DATE_ADD(NOW(), INTERVAL ? SECOND)",
                [$cacheKey, $decaySeconds, $decaySeconds]
            );
        }
    }

    // ── Input Sanitization ────────────────────────────────────────
    public static function clean(string $val): string {
        // Strip tags and encode special characters to prevent XSS
        return htmlspecialchars(strip_tags(trim($val)), ENT_QUOTES, 'UTF-8');
    }

    public static function int(mixed $val): int {
        return (int) filter_var($val, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function float(mixed $val): float {
        return (float) filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    public static function phone(string $val): string {
        return preg_replace('/[^0-9]/', '', $val);
    }
    
    public static function validatePhone(string $phone): bool {
        return (bool) preg_match('/^\d{10}$/', $phone);
    }

    // ── Advanced HTTP Security & CORS Headers ─────────────────────
    public static function setHeaders(): void {
        // Prevent clickjacking
        header('X-Frame-Options: SAMEORIGIN');
        
        // Prevent MIME-sniffing
        header('X-Content-Type-Options: nosniff');
        
        // Cross-Site Scripting (XSS) Protection
        header('X-XSS-Protection: 1; mode=block');
        
        // Strict Transport Security (HSTS) - Force HTTPS
        if (APP_ENV === 'production') {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        }
        
        // Content Security Policy (CSP) — Updated to allow CDN assets
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://www.gstatic.com https://checkout.razorpay.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com; font-src 'self' data: https://cdn.jsdelivr.net https://fonts.gstatic.com; connect-src 'self' https:; frame-src 'self' https://api.razorpay.com https://checkout.razorpay.com; img-src 'self' data: https: blob:;");

        // Referrer Policy
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Permissions Policy (Camera, Mic, Geo)
        header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    }

    public static function setCorsHeaders(): void {
        // Essential for Flutter Mobile App & External Frontends
        header('Access-Control-Allow-Origin: *'); // Restrict to specific domains in production
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-TOKEN, X-Requested-With');
        
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }

    // ── JSON API Response Formatter ───────────────────────────────
    public static function jsonOut(array $data, int $code = 200): never {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function jsonError(string $msg, int $code = 422): never {
        self::jsonOut(['success' => false, 'message' => $msg], $code);
    }

    public static function jsonOk(array $data = []): never {
        self::jsonOut(array_merge(['success' => true], $data));
    }

    // ── Client IP Detection ───────────────────────────────────────
    public static function getIp(): string {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] 
            ?? $_SERVER['HTTP_CLIENT_IP'] 
            ?? $_SERVER['REMOTE_ADDR'] 
            ?? '0.0.0.0';
            
        // Handle comma-separated IPs in proxies
        if (strpos($ip, ',') !== false) {
            $ip = explode(',', $ip)[0];
        }
        return trim($ip);
    }
}
