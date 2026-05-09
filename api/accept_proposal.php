<?php
/**
 * API: Accept Proposal (Hire Freelancer)
 * POST /api/accept_proposal.php
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

Security::verifyCsrf();

if (Auth::role() !== 'client') {
    Security::jsonError('Only clients can accept proposals.', 403);
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$proposalId = (int)($body['proposal_id'] ?? 0);

if ($proposalId <= 0) {
    Security::jsonError('Invalid proposal ID.');
}

// ── Fetch Proposal & Job ────────────────────────
$proposal = DB::row("SELECT p.*, j.client_id, j.title as job_title 
                     FROM proposals p 
                     JOIN jobs j ON p.job_id = j.id 
                     WHERE p.id = ?", [$proposalId]);

if (!$proposal) {
    Security::jsonError('Proposal not found.');
}

// Security: Verify this client owns the job
if ($proposal['client_id'] !== Auth::id()) {
    Security::jsonError('You do not have permission to accept this proposal.', 403);
}

try {
    DB::beginTransaction();

    // 1. Accept this proposal
    DB::query("UPDATE proposals SET status = 'accepted' WHERE id = ?", [$proposalId]);

    // 2. Reject other proposals for this job (optional, but clean)
    DB::query("UPDATE proposals SET status = 'rejected' WHERE job_id = ? AND id != ?", [$proposal['job_id'], $proposalId]);

    // 3. Update Job status
    DB::query("UPDATE jobs SET status = 'in_progress' WHERE id = ?", [$proposal['job_id']]);

    // 4. Notify Freelancer
    DB::query(
        "INSERT INTO notifications (user_id, type, message, link, created_at)
         VALUES (?, 'hire_success', 'Congratulations! Your proposal for \"{$proposal['job_title']}\" has been accepted. You can now chat with the client.', ?, NOW())",
        [$proposal['freelancer_id'], "/dashboard/chat.php?proposal_id=" . $proposalId]
    );

    DB::commit();

    Logger::info("Client ID " . Auth::id() . " accepted Proposal ID $proposalId");

    Security::jsonOk(['message' => 'Freelancer hired successfully! The project is now in progress.']);

} catch (Exception $e) {
    DB::rollBack();
    Logger::error("Failed to accept proposal: " . $e->getMessage());
    Security::jsonError('Failed to process hiring. Please try again later.', 500);
}
