<?php
/**
 * WorkBazar — Freelancer: My Reviews & Feedback
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('freelancer');

$user = Auth::user();
$profile = Auth::freelancerProfile();

// Fetch All Reviews for this Freelancer
$reviews = DB::all("SELECT r.*, u.fullname as reviewer_name, j.title as job_title 
                    FROM reviews r 
                    JOIN users u ON r.reviewer_id = u.id 
                    JOIN jobs j ON r.job_id = j.id
                    WHERE r.reviewee_id = ? 
                    ORDER BY r.created_at DESC", [$user['id']]);

$pageTitle = "My Reviews — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>


<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/freelancer/reviews.css'); ?>">

<main class="dashboard-wrap">
  <div class="page-header">
    <h1>My Reviews & Ratings ⭐</h1>
    <p>Check what clients are saying about your work.</p>
  </div>

  <div class="reviews-stats-grid">
    <div class="stat-card">
       <i class="ri-star-fill"></i>
       <strong><?=$profile['rating'] ?? '0.0'?></strong>
       <span>Average Rating</span>
    </div>
    <div class="stat-card">
       <i class="ri-chat-check-line"></i>
       <strong><?=count($reviews)?></strong>
       <span>Total Reviews</span>
    </div>
    <div class="stat-card">
       <i class="ri-medal-line"></i>
       <strong><?=$profile['success_rate'] ?? '100'?>%</strong>
       <span>Job Success Rate</span>
    </div>
  </div>

  <div class="admin-table-card">
    <?php if (empty($reviews)): ?>
      <div class="empty-reviews">
        <i class="ri-chat-history-line"></i>
        <h3>No reviews yet. Complete your first job to get feedback!</h3>
      </div>
    <?php else: foreach($reviews as $r): ?>
      <div class="review-item">
        <div class="review-header">
          <div>
            <div class="reviewer-name"><?=htmlspecialchars($r['reviewer_name'])?></div>
            <div class="review-job-title">For: <?=htmlspecialchars($r['job_title'])?></div>
          </div>
          <div class="review-stars">
            <?php for($i=1; $i<=5; $i++): ?>
              <i class="ri-star-<?=$i <= $r['rating'] ? 'fill' : 'line'?>"></i>
            <?php endfor; ?>
          </div>
        </div>
        <p class="review-comment">"<?=htmlspecialchars($r['comment'])?>"</p>
        <div class="review-date"><?=date('M d, Y', strtotime($r['created_at']))?></div>
      </div>
    <?php endforeach; endif; ?>
  </div>
</main>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
