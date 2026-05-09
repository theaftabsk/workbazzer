<?php
/** API: Admin Approve Job */
require_once __DIR__ . '/../includes/app.php';
App::init();

Auth::requireRole('admin');
Security::verifyCsrf();

$body  = json_decode(file_get_contents('php://input'), true) ?? [];
$jobId = (int)($body['job_id'] ?? 0);

if (!$jobId) {
    Security::jsonError('Missing Job ID.');
}

// Update the job status
DB::query("UPDATE jobs SET is_approved = 1 WHERE id = ?", [$jobId]);

// Notify the client that their job is live
$job = DB::row("SELECT client_id, title FROM jobs WHERE id = ?", [$jobId]);
if ($job) {
    // Notification logic (Assuming a Notification class exists or simple DB query)
    DB::query("INSERT INTO notifications (user_id, type, message, is_read, created_at) 
               VALUES (?, 'job_approved', ?, 0, NOW())", 
               [$job['client_id'], "Your project '{$job['title']}' has been approved and is now live!"]);
}

Security::jsonOk(['message' => 'Job approved successfully.']);
