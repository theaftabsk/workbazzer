<?php
/**
 * API: Complete Job & Submit Review
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

Auth::requireRole('client');
Security::verifyCsrf();

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$jobId = (int)($body['job_id'] ?? 0);
$freelancerId = (int)($body['freelancer_id'] ?? 0);
$rating = (int)($body['rating'] ?? 5);
$comment = Security::clean($body['comment'] ?? '');

if (!$jobId || !$freelancerId) {
    Security::jsonError('Missing data.');
}

try {
    DB::beginTransaction();

    // 1. Update Job Status
    DB::query("UPDATE jobs SET status = 'completed' WHERE id = ? AND client_id = ?", [$jobId, Auth::id()]);

    // 2. Insert Review
    DB::query("INSERT INTO reviews (job_id, reviewer_id, reviewee_id, rating, comment) 
               VALUES (?, ?, ?, ?, ?)", 
               [$jobId, Auth::id(), $freelancerId, $rating, $comment]);

    // 3. Update Freelancer Profile Stats (Average Rating)
    $stats = DB::row("SELECT COUNT(*) as total, AVG(rating) as avg_rating FROM reviews WHERE reviewee_id = ?", [$freelancerId]);
    DB::query("UPDATE freelancer_profiles SET rating = ?, total_reviews = ? WHERE user_id = ?", 
               [$stats['avg_rating'], $stats['total'], $freelancerId]);

    // 4. Notify Freelancer
    DB::query("INSERT INTO notifications (user_id, type, message, link, is_read, created_at) 
               VALUES (?, 'job_completed', ?, ?, 0, NOW())", 
               [$freelancerId, "Project completed! You received a {$rating}-star review.", "/dashboard/freelancer/reviews.php"]);

    DB::commit();
    Security::jsonOk(['message' => 'Job completed and review submitted.']);

} catch (Exception $e) {
    DB::rollBack();
    Logger::error("Complete Job Failed: " . $e->getMessage());
    Security::jsonError('Failed to process. Please try again.');
}
