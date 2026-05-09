<?php
require_once __DIR__ . '/includes/app.php';
App::init();

try {
    // 1. Add phone column to users
    DB::query("ALTER TABLE users ADD COLUMN phone VARCHAR(20) DEFAULT NULL AFTER email");
    echo "Success: phone column added to users table.\n";
} catch (Exception $e) { echo "Info: " . $e->getMessage() . "\n"; }

try {
    // 2. Create messages table
    DB::query("CREATE TABLE IF NOT EXISTS `messages` (
      `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `proposal_id`   INT UNSIGNED NOT NULL,
      `sender_id`     INT UNSIGNED NOT NULL,
      `receiver_id`   INT UNSIGNED NOT NULL,
      `message`       TEXT NOT NULL,
      `is_read`       TINYINT(1) DEFAULT 0,
      `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (`proposal_id`) REFERENCES `proposals`(`id`) ON DELETE CASCADE,
      FOREIGN KEY (`sender_id`)   REFERENCES `users`(`id`)     ON DELETE CASCADE,
      FOREIGN KEY (`receiver_id`) REFERENCES `users`(`id`)     ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "Success: messages table created.\n";
} catch (Exception $e) { echo "Error: " . $e->getMessage() . "\n"; }
