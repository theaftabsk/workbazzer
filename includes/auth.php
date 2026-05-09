<?php
/**
 * WorkBazar — Auth & Session Helpers
 */

require_once __DIR__ . '/security.php';
require_once __DIR__ . '/db.php';

class Auth {
    public static function check(): bool {
        return !empty($_SESSION['user_id']);
    }

    public static function user(): ?array {
        if (!self::check()) return null;
        return DB::row("SELECT * FROM users WHERE id=?", [$_SESSION['user_id']]);
    }

    public static function id(): int {
        return (int)($_SESSION['user_id'] ?? 0);
    }

    public static function role(): string {
        return $_SESSION['user_role'] ?? '';
    }

    public static function requireLogin(string $redirect = '/auth/login.php'): void {
        if (!self::check() || !self::user()) {
            self::logout();
            header("Location: $redirect");
            exit;
        }
    }

    public static function requireRole(string $role): void {
        self::requireLogin();
        if (self::role() !== $role) {
            header("Location: " . self::dashboardUrl(self::role()));
            exit;
        }
    }

    public static function setSession(array $user): void {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['fullname'] ?? $user['name'] ?? 'User';
    }

    public static function logout(): void {
        session_unset();
        session_destroy();
    }

    public static function dashboardUrl(string $role): string {
        return match($role) {
            'admin'  => '/dashboard/admin/index.php',
            'client' => '/dashboard/client/index.php',
            default  => '/dashboard/freelancer/index.php',
        };
    }

    /** Freelancer profile (coin balance etc.) */
    public static function freelancerProfile(): ?array {
        return DB::row("SELECT * FROM freelancer_profiles WHERE user_id=?", [self::id()]);
    }
}
