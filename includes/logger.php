<?php
/**
 * Enterprise Logger System
 * Handles application, error, security, and API logging.
 */

class Logger {
    private const LOG_DIR = __DIR__ . '/../logs';

    private static function init() {
        if (!is_dir(self::LOG_DIR)) {
            @mkdir(self::LOG_DIR, 0755, true);
        }
    }

    private static function write(string $file, string $level, string $message, array $context = []) {
        self::init();
        
        $date = date('Y-m-d H:i:s');
        $ip = Security::getIp();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        
        $contextString = !empty($context) ? ' | Context: ' . json_encode($context) : '';
        
        $logEntry = sprintf(
            "[%s] [%s] [IP: %s] [UA: %s] %s%s\n",
            $date,
            $level,
            $ip,
            $userAgent,
            $message,
            $contextString
        );

        $filename = self::LOG_DIR . '/' . $file . '-' . date('Y-m-d') . '.log';
        @file_put_contents($filename, $logEntry, FILE_APPEND | LOCK_EX);
    }

    public static function info(string $message, array $context = []) {
        self::write('app', 'INFO', $message, $context);
    }

    public static function error(string $message, array $context = []) {
        self::write('error', 'ERROR', $message, $context);
    }

    public static function security(string $message, array $context = []) {
        self::write('security', 'CRITICAL', $message, $context);
    }

    public static function api(string $message, array $context = []) {
        self::write('api', 'API', $message, $context);
    }
    
    public static function auth(string $message, array $context = []) {
        self::write('auth', 'AUTH', $message, $context);
    }
}
