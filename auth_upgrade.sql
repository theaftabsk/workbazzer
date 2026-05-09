-- WorkBazar Auth System Upgrade
-- Run this SQL on your Hostinger MySQL database

-- 1. Add password column to users table (if not exists)
ALTER TABLE users 
    ADD COLUMN IF NOT EXISTS password VARCHAR(255) NULL AFTER email;

-- 2. Create password reset tokens table
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    email       VARCHAR(255) NOT NULL,
    token       VARCHAR(100) NOT NULL,
    expires_at  DATETIME NOT NULL,
    used        TINYINT(1) DEFAULT 0,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (token),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
