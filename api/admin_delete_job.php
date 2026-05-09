<?php
/** API: Admin Delete Job */
require_once __DIR__ . '/../includes/app.php';
App::init();
Auth::requireRole('admin');
Security::verifyCsrf();

$body  = json_decode(file_get_contents('php://input'), true) ?? [];
$jobId = Security::int($body['job_id'] ?? 0);
DB::query("DELETE FROM jobs WHERE id=?", [$jobId]);
Security::jsonOk(['message' => 'Job deleted.']);
