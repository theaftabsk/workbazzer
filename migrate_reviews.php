<?php
/**
 * Migration: Reviews Support
 */
require_once __DIR__ . '/includes/app.php';
App::init();

try {
    DB::query("CREATE TABLE IF NOT EXISTS `reviews` (
      `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `job_id`        INT UNSIGNED NOT NULL,
      `reviewer_id`   INT UNSIGNED NOT NULL,
      `reviewee_id`   INT UNSIGNED NOT NULL,
      `rating`        TINYINT UNSIGNED NOT NULL, -- 1 to 5
      `comment`       TEXT DEFAULT NULL,
      `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (`job_id`)      REFERENCES `jobs`(`id`)      ON DELETE CASCADE,
      FOREIGN KEY (`reviewer_id`) REFERENCES `users`(`id`)     ON DELETE CASCADE,
      FOREIGN KEY (`reviewee_id`) REFERENCES `users`(`id`)     ON DELETE CASCADE,
      UNIQUE KEY `unique_review` (`job_id`, `reviewer_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    
    echo "Success: reviews table created.";
} catch (Exception $e) { echo "Error: " . $e->getMessage(); }
