<?php
/** API: Get Chat Messages */
require_once __DIR__ . '/../includes/app.php';
App::init();

Auth::requireLogin();

$proposalId = (int)($_GET['proposal_id'] ?? 0);
$lastId     = (int)($_GET['last_id'] ?? 0);
$user       = Auth::user();

// Verify access
$p = DB::row("SELECT client_id, freelancer_id FROM proposals p JOIN jobs j ON p.job_id = j.id WHERE p.id = ?", [$proposalId]);
if (!$p || ($user['id'] != $p['client_id'] && $user['id'] != $p['freelancer_id'])) {
    Security::jsonError('Unauthorized.');
}

$messages = DB::all("SELECT id, sender_id, message, DATE_FORMAT(created_at, '%h:%i %p') as time 
                     FROM messages 
                     WHERE proposal_id = ? AND id > ? 
                     ORDER BY id ASC", [$proposalId, $lastId]);

Security::jsonOk(['messages' => $messages]);
