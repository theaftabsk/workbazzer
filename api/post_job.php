<?php
/**
 * API: Post a New Job
 * POST /api/post_job.php
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

// Load Core Logic
require_once __DIR__ . '/../core/Job.php';

// Security: Check Role & CSRF
Security::verifyCsrf();
if (Auth::role() !== 'client') {
    Security::jsonError('Unauthorized. Only clients can post projects.', 403);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Security::jsonError('Method not allowed.', 405);
}

// Get Data
$body = json_decode(file_get_contents('php://input'), true) ?? [];

// Basic Validation
if (empty($body['title']) || strlen($body['title']) < 5) {
    Security::jsonError('Please enter a descriptive project title.');
}
if (empty($body['description']) || strlen($body['description']) < 20) {
    Security::jsonError('Please provide a more detailed description (min 20 chars).');
}
if (empty($body['category'])) {
    Security::jsonError('Please select a project category.');
}

try {
    // Create the Job
    $jobId = Job::create($body);

    // Create a Notification (optional for now, but good practice)
    // Notification::send(Auth::id(), 'job_posted', 'Your project has been published successfully!');

    Security::jsonOk([
        'message' => 'Project published successfully!',
        'job_id'  => $jobId,
        'redirect' => '/dashboard/client/index.php?success=job_posted'
    ]);

} catch (Exception $e) {
    Logger::error("Failed to post job: " . $e->getMessage());
    Security::jsonError('Failed to publish project. Please try again later.', 500);
}
