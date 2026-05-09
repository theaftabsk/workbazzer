<?php
require_once __DIR__ . '/includes/app.php';
App::init();

try {
    DB::query("ALTER TABLE jobs ADD COLUMN is_approved TINYINT(1) DEFAULT 0 AFTER status");
    echo "Success: is_approved column added to jobs table.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
