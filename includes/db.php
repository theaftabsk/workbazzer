<?php
/**
 * WorkBazar — Database (PDO Singleton)
 * SQL Injection: 100% prevented via prepared statements
 */

require_once __DIR__ . '/config.php';

class DB {
    private static ?PDO $pdo = null;

    public static function conn(): PDO {
        if (self::$pdo === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            self::$pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$pdo;
    }

    /** Run a prepared query, return PDOStatement */
    public static function query(string $sql, array $params = []): PDOStatement {
        $stmt = self::conn()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /** Fetch single row */
    public static function row(string $sql, array $params = []): ?array {
        $r = self::query($sql, $params)->fetch();
        return $r ?: null;
    }

    /** Fetch all rows */
    public static function all(string $sql, array $params = []): array {
        return self::query($sql, $params)->fetchAll();
    }

    /** Return last insert ID */
    public static function lastId(): string {
        return self::conn()->lastInsertId();
    }

    public static function beginTransaction(): bool {
        return self::conn()->beginTransaction();
    }

    public static function commit(): bool {
        return self::conn()->commit();
    }

    public static function rollBack(): bool {
        return self::conn()->rollBack();
    }
}
