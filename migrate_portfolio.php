<?php
/**
 * Migration: Portfolio Support
 */
require_once __DIR__ . '/includes/app.php';
App::init();

try {
    DB::query("CREATE TABLE IF NOT EXISTS `portfolios` (
      `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `user_id`       INT UNSIGNED NOT NULL,
      `title`         VARCHAR(200) NOT NULL,
      `description`   TEXT DEFAULT NULL,
      `image_url`     VARCHAR(255) DEFAULT NULL,
      `project_url`   VARCHAR(255) DEFAULT NULL,
      `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    
    echo "Success: portfolios table created.";
} catch (Exception $e) { echo "Error: " . $e->getMessage(); }
