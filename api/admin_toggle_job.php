<?php
/** API: Admin Toggle Job */
require_once __DIR__ . '/../includes/app.php';
App::init();
Auth::requireRole('admin');
Security::verifyCsrf();

$body  = json_decode(file_get_contents('php://input'), true) ?? [];
$jobId = Security::int($body['job_id'] ?? 0);
$job   = DB::row("SELECT id, status FROM jobs WHERE id=?", [$jobId]);
if (!$job) Security::jsonError('Job not found.', 404);

$new = $job['status'] === 'open' ? 'closed' : 'open';
DB::query("UPDATE jobs SET status=? WHERE id=?", [$new, $jobId]);
Security::jsonOk(['message' => "Job set to $new.", 'new_status' => $new]);
