<?php
/**
 * WorkBazar — Freelancer: My Submitted Proposals
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('freelancer');

$user = Auth::user();

// Fetch All Proposals submitted by this Freelancer
$proposals = DB::all("SELECT p.*, j.title as job_title, j.budget_min, j.budget_max, j.status as job_status,
                             u.fullname as client_name 
                      FROM proposals p 
                      JOIN jobs j ON p.job_id = j.id 
                      JOIN users u ON j.client_id = u.id
                      WHERE p.freelancer_id = ? 
                      ORDER BY p.created_at DESC", [$user['id']]);

$pageTitle = "My Proposals — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>



<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/freelancer/my-proposals.css'); ?>">

<main class="my-proposals-wrap">
  <div class="page-header">
    <h1>My Proposals</h1>
    <p>Track all your active and past bids in one place.</p>
  </div>

  <?php if (empty($proposals)): ?>
    <div class="empty-proposals">
      <i class="ri-mail-send-line"></i>
      <h3>You haven't submitted any proposals yet.</h3>
      <a href="/public/find-work.php">Find projects to bid on →</a>
    </div>
  <?php else: foreach($proposals as $p): ?>
    <div class="proposal-row">
      <div class="job-info">
        <h3><?=htmlspecialchars($p['job_title'])?></h3>
        <p>Client: <b><?=htmlspecialchars($p['client_name'])?></b> · Submitted <?=time_ago($p['created_at'])?></p>
      </div>

      <div class="bid-val">
        <strong>₹<?=number_format($p['bid_amount'])?></strong>
        <span>My Bid Amount</span>
      </div>

      <div class="bid-val">
        <div class="status-badge status-<?=$p['status']?>"><?=$p['status']?></div>
      </div>

      <div style="text-align:right;">
        <a href="/job-details.php?id=<?=$p['job_id']?>" class="btn-view-job">View Job Details <i class="ri-arrow-right-s-line"></i></a>
      </div>
    </div>
  <?php endforeach; endif; ?>
</main>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
