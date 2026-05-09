<?php
/**
 * API: Update Profile
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

Auth::requireLogin();
Security::verifyCsrf();

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$userId = Auth::id();

$fullname = Security::clean($body['fullname'] ?? '');
$phone    = Security::clean($body['phone'] ?? '');
$country  = Security::clean($body['country'] ?? 'India');
$title    = Security::clean($body['title'] ?? '');
$bio      = Security::clean($body['bio'] ?? '');

if (empty($fullname)) {
    Security::jsonError('Full name is required.');
}

try {
    DB::beginTransaction();

    // 1. Update Users Table
    DB::query("UPDATE users SET fullname = ?, phone = ?, country = ?, title = ?, bio = ? WHERE id = ?", 
               [$fullname, $phone, $country, $title, $bio, $userId]);

    // 2. If Freelancer, update hourly rate
    if (Auth::role() === 'freelancer' && isset($body['hourly_rate'])) {
        DB::query("UPDATE freelancer_profiles SET hourly_rate = ? WHERE user_id = ?", 
                   [(float)$body['hourly_rate'], $userId]);
    }

    DB::commit();
    Security::jsonOk(['message' => 'Profile updated successfully.']);

} catch (Exception $e) {
    DB::rollBack();
    Logger::error("Profile Update Failed: " . $e->getMessage());
    Security::jsonError('Update failed. Please try again.');
}
