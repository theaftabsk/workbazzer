<?php
/**
 * API: Update Avatar
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

Auth::requireLogin();
Security::verifyCsrf();

if (!isset($_FILES['avatar'])) {
    Security::jsonError('No file uploaded.');
}

$file = $_FILES['avatar'];
$userId = Auth::id();

// Basic Validation
$allowed = ['image/jpeg', 'image/png', 'image/webp'];
if (!in_array($file['type'], $allowed)) {
    Security::jsonError('Only JPG, PNG and WebP images are allowed.');
}

if ($file['size'] > 2 * 1024 * 1024) {
    Security::jsonError('File size must be less than 2MB.');
}

// Upload Logic (Saving to assets/uploads/avatars)
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'avatar_' . $userId . '_' . time() . '.' . $ext;
$uploadDir = __DIR__ . '/../assets/uploads/avatars/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$targetPath = $uploadDir . $filename;
$dbPath = '/assets/uploads/avatars/' . $filename;

if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    DB::query("UPDATE users SET avatar = ? WHERE id = ?", [$dbPath, $userId]);
    Security::jsonOk(['message' => 'Avatar updated!', 'avatar_url' => $dbPath]);
} else {
    Security::jsonError('Failed to save file.');
}
