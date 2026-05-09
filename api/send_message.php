<?php
/** API: Send Chat Message */
require_once __DIR__ . '/../includes/app.php';
App::init();

Auth::requireLogin();
Security::verifyCsrf();

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$proposalId = (int)($body['proposal_id'] ?? 0);
$message    = Security::clean($body['message'] ?? '');
$user       = Auth::user();

if (!$proposalId || empty($message)) {
    Security::jsonError('Message cannot be empty.');
}

// Verify access and get receiver
$p = DB::row("SELECT p.*, j.client_id FROM proposals p JOIN jobs j ON p.job_id = j.id WHERE p.id = ?", [$proposalId]);
if (!$p || ($user['id'] != $p['client_id'] && $user['id'] != $p['freelancer_id'])) {
    Security::jsonError('Unauthorized.');
}

$receiverId = ($user['id'] == $p['client_id']) ? $p['freelancer_id'] : $p['client_id'];

DB::query("INSERT INTO messages (proposal_id, sender_id, receiver_id, message) VALUES (?, ?, ?, ?)", 
           [$proposalId, $user['id'], $receiverId, $message]);

// Optional: Send Notification to receiver
DB::query("INSERT INTO notifications (user_id, type, message, link, is_read, created_at) 
           VALUES (?, 'new_message', 'You have a new message regarding your project.', ?, 0, NOW())", 
           [$receiverId, "/dashboard/chat.php?proposal_id=$proposalId"]);

Security::jsonOk(['message' => 'Sent.']);
