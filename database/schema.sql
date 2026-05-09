-- WorkBazar Enterprise Database Schema (Final Fixed Version)
SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- 1. Users Table
CREATE TABLE IF NOT EXISTS `users` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `fullname`      VARCHAR(120) NOT NULL,
  `email`         VARCHAR(191) UNIQUE NOT NULL,
  `password`      VARCHAR(255) NOT NULL,
  `role`          ENUM('client','freelancer','admin') NOT NULL DEFAULT 'client',
  `avatar`        VARCHAR(255) DEFAULT NULL,
  `title`         VARCHAR(180) DEFAULT NULL,
  `bio`           TEXT DEFAULT NULL,
  `country`       VARCHAR(80) DEFAULT 'India',
  `verified`      TINYINT(1) DEFAULT 0,
  `available`     TINYINT(1) DEFAULT 1,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_role (`role`),
  INDEX idx_email (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Freelancer Profiles
CREATE TABLE IF NOT EXISTS `freelancer_profiles` (
  `user_id`       INT UNSIGNED PRIMARY KEY,
  `hourly_rate`   DECIMAL(10,2) DEFAULT 0.00,
  `coin_balance`  INT DEFAULT 0,
  `success_rate`  TINYINT UNSIGNED DEFAULT 100,
  `rating`        DECIMAL(3,2) DEFAULT 0.00,
  `total_reviews` INT UNSIGNED DEFAULT 0,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Client Profiles
CREATE TABLE IF NOT EXISTS `client_profiles` (
  `user_id`       INT UNSIGNED PRIMARY KEY,
  `company_name`  VARCHAR(150) DEFAULT NULL,
  `total_spent`   DECIMAL(15,2) DEFAULT 0.00,
  `total_jobs`    INT UNSIGNED DEFAULT 0,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Jobs / Leads Table
CREATE TABLE IF NOT EXISTS `jobs` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `client_id`     INT UNSIGNED NOT NULL,
  `title`         VARCHAR(255) NOT NULL,
  `description`   TEXT NOT NULL,
  `category`      VARCHAR(100) NOT NULL,
  `budget_type`   ENUM('fixed', 'hourly') DEFAULT 'fixed',
  `budget_min`    DECIMAL(12,2) DEFAULT 0,
  `budget_max`    DECIMAL(12,2) DEFAULT 0,
  `status`        ENUM('open', 'in_progress', 'completed', 'cancelled') DEFAULT 'open',
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`client_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX idx_status (`status`),
  INDEX idx_category (`category`),
  FULLTEXT idx_search (`title`, `description`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Proposals Table
CREATE TABLE IF NOT EXISTS `proposals` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `job_id`        INT UNSIGNED NOT NULL,
  `freelancer_id` INT UNSIGNED NOT NULL,
  `bid_amount`    DECIMAL(12,2) NOT NULL,
  `delivery_days` SMALLINT UNSIGNED NOT NULL,
  `cover_letter`  TEXT NOT NULL,
  `status`        ENUM('pending', 'accepted', 'rejected', 'withdrawn') DEFAULT 'pending',
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`freelancer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_bid` (`job_id`, `freelancer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Wallet Transactions
CREATE TABLE IF NOT EXISTS `coin_transactions` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`       INT UNSIGNED NOT NULL,
  `amount`        INT NOT NULL,
  `type`          ENUM('bonus', 'purchase', 'spend', 'refund') NOT NULL,
  `description`   VARCHAR(255) DEFAULT NULL,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. System Settings
CREATE TABLE IF NOT EXISTS `settings` (
  `key`           VARCHAR(64) PRIMARY KEY,
  `value`         TEXT DEFAULT NULL,
  `updated_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Notifications System
CREATE TABLE IF NOT EXISTS `notifications` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id`       INT UNSIGNED NOT NULL,
  `type`          VARCHAR(50) NOT NULL,
  `message`       TEXT NOT NULL,
  `link`          VARCHAR(255) DEFAULT NULL,
  `is_read`       TINYINT(1) DEFAULT 0,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX idx_unread (`user_id`, `is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Rate Limits (Required to prevent 500 errors in verify_otp)
CREATE TABLE IF NOT EXISTS `rate_limits` (
  `cache_key`     VARCHAR(64) PRIMARY KEY,
  `attempts`      INT DEFAULT 0,
  `reset_at`      DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. OTP Verifications
CREATE TABLE IF NOT EXISTS `otp_verifications` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `contact`       VARCHAR(191) NOT NULL,
  `otp`           VARCHAR(10) NOT NULL,
  `expires_at`    DATETIME NOT NULL,
  `used`          TINYINT(1) DEFAULT 0,
  `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_contact (`contact`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default Data
INSERT IGNORE INTO `settings` (`key`, `value`) VALUES 
('signup_bonus_coins', '20'),
('proposal_cost_coins', '2'),
('site_name', 'WorkBazar'),
('currency_symbol', '₹');