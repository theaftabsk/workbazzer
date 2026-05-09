<?php
/**
 * WorkBazar — Database Repair & Auto-Fix
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

echo "<h2>WorkBazar Database Repair 🛠️</h2><hr>";

function addColumn($table, $column, $definition) {
    try {
        // Check if column exists
        $exists = DB::row("SHOW COLUMNS FROM `$table` LIKE '$column'");
        if (!$exists) {
            DB::query("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
            echo "<p style='color:green'>✅ Added column <b>$column</b> to <b>$table</b>.</p>";
        } else {
            echo "<p style='color:blue'>ℹ️ Column <b>$column</b> already exists in <b>$table</b>.</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color:red'>❌ Error adding $column: " . $e->getMessage() . "</p>";
    }
}

// 1. Jobs Table Fixes
addColumn('jobs', 'is_approved', "TINYINT(1) DEFAULT 1 AFTER status");
addColumn('jobs', 'work_type', "ENUM('remote', 'on_site', 'full_time') DEFAULT 'remote' AFTER budget_type");

// 2. Users Table Fixes
addColumn('users', 'phone', "VARCHAR(20) DEFAULT NULL AFTER email");
addColumn('users', 'avatar', "VARCHAR(255) DEFAULT NULL AFTER fullname");

// 3. Freelancer Profiles Fixes
addColumn('freelancer_profiles', 'earnings_balance', "DECIMAL(15,2) DEFAULT 0.00 AFTER coin_balance");
addColumn('freelancer_profiles', 'rating', "DECIMAL(3,2) DEFAULT 0.00 AFTER earnings_balance");
addColumn('freelancer_profiles', 'total_reviews', "INT DEFAULT 0 AFTER rating");

// Ensure all existing jobs are approved so they show up
try {
    DB::query("UPDATE jobs SET is_approved = 1");
    echo "<p style='color:green'>✅ All existing jobs marked as approved.</p>";
} catch (Exception $e) {}

echo "<hr><h3>Repair Complete! Now go to Marketplace.</h3>";
echo "<a href='/public/find-work.php' style='padding:10px 20px; background:green; color:#fff; text-decoration:none; border-radius:5px;'>Open Marketplace</a>";
