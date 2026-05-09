<?php
/**
 * API: Submit Proposal (Bid)
 * POST /api/submit_proposal.php
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

Security::verifyCsrf();

if (Auth::role() !== 'freelancer') {
    Security::jsonError('Only freelancers can submit proposals.', 403);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Security::jsonError('Method not allowed.', 405);
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];

$jobId      = (int)($body['job_id'] ?? 0);
$bidAmount  = (float)($body['bid_amount'] ?? 0);
$days       = (int)($body['delivery_days'] ?? 0);
$cover      = Security::clean($body['cover_letter'] ?? '');

// ── Validation ──────────────────────────────────
if ($jobId <= 0 || $bidAmount <= 0 || $days <= 0 || empty($cover)) {
    Security::jsonError('Please fill in all the required fields correctly.');
}

$job = DB::row("SELECT * FROM jobs WHERE id = ?", [$jobId]);
if (!$job || $job['status'] !== 'open') {
    Security::jsonError('This project is no longer accepting proposals.');
}

$freelancerId = Auth::id();

// ── Check if already bid ────────────────────────
$exists = DB::row("SELECT id FROM proposals WHERE job_id = ? AND freelancer_id = ?", [$jobId, $freelancerId]);
if ($exists) {
    Security::jsonError('You have already submitted a proposal for this project.');
}

// ── Check Coins ─────────────────────────────────
$profile = Auth::freelancerProfile();
$cost    = (int) App::setting('proposal_cost_coins', 2);

if (!$profile || $profile['coin_balance'] < $cost) {
    Security::jsonError("Insufficient coins. You need {$cost} coins to bid. Please recharge your wallet.");
}

try {
    DB::beginTransaction();

    // 1. Insert Proposal
    DB::query(
        "INSERT INTO proposals (job_id, freelancer_id, bid_amount, delivery_days, cover_letter, status, created_at)
         VALUES (?, ?, ?, ?, ?, 'pending', NOW())",
        [$jobId, $freelancerId, $bidAmount, $days, $cover]
    );

    // 2. Deduct Coins
    DB::query(
        "UPDATE freelancer_profiles SET coin_balance = coin_balance - ? WHERE user_id = ?",
        [$cost, $freelancerId]
    );

    // 3. Log Transaction
    DB::query(
        "INSERT INTO coin_transactions (user_id, amount, type, description, created_at)
         VALUES (?, ?, 'spend', 'Bid on project: #{$jobId}', NOW())",
        [$freelancerId, -$cost]
    );

    // 4. Notify Client
    DB::query(
        "INSERT INTO notifications (user_id, type, message, link, created_at)
         VALUES (?, 'new_proposal', 'A new freelancer has bid on your project: {$job['title']}', ?, NOW())",
        [$job['client_id'], "dashboard/client/view-proposals.php?job_id={$jobId}"]
    );

    DB::commit();

    Logger::info("Freelancer ID {$freelancerId} submitted a proposal for Job ID {$jobId}");

    Security::jsonOk(['message' => 'Proposal submitted successfully!']);

} catch (Exception $e) {
    DB::rollBack();
    Logger::error("Failed to submit proposal: " . $e->getMessage());
    Security::jsonError('Failed to submit proposal. Please try again later.', 500);
}
