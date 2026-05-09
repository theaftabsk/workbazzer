<?php
/**
 * WorkBazar — Enterprise Core Engine
 * Initializes all core components.
 */

class App {
    public static function init() {
        // Load Configuration
        require_once __DIR__ . '/config.php';
        
        // Load Core Modules
        require_once __DIR__ . '/helpers.php';
        require_once __DIR__ . '/logger.php';
        require_once __DIR__ . '/db.php';
        require_once __DIR__ . '/security.php';
        require_once __DIR__ . '/auth.php';
        require_once __DIR__ . '/validator.php';
        require_once __DIR__ . '/mail.php';
        require_once __DIR__ . '/otp.php';
        require_once __DIR__ . '/router.php';

        // Initialize Core Security & Session
        Security::startSession();
        try {
            Security::setHeaders();
            Security::setCorsHeaders();
        } catch (Exception $e) {
            // Headers may already be sent, ignore
        }

        // Setup Error Handling
        self::setupErrorHandling();
    }

    private static function setupErrorHandling() {
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            $msg = "Error ($errno): $errstr in $errfile on line $errline";
            Logger::error($msg);
            if (APP_DEBUG) {
                echo "<pre>$msg</pre>";
            } else {
                http_response_code(500);
                include __DIR__ . '/../errors/500.php';
            }
            return true;
        });

        set_exception_handler(function($e) {
            Logger::error("Uncaught Exception: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            if (APP_DEBUG) {
                echo "<pre>" . $e->getMessage() . PHP_EOL . $e->getTraceAsString() . "</pre>";
            } else {
                http_response_code(500);
                include __DIR__ . '/../errors/500.php';
            }
        });
    }

    /** Get system settings dynamically */
    public static function setting($key, $default = null) {
        static $settings = null;
        if ($settings === null) {
            try {
                $all = DB::all("SELECT `key`, value FROM settings");
                $settings = array_column($all, 'value', 'key');
            } catch (Exception $e) {
                return $default; // DB might not be setup yet
            }
        }
        return $settings[$key] ?? $default;
    }
}

