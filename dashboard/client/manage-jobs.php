<?php
/**
 * WorkBazar — Client: Manage Posted Jobs
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('client');

$user = Auth::user();

// Fetch All Jobs by this Client
$jobs = DB::all("SELECT j.*, 
                 (SELECT COUNT(*) FROM proposals WHERE job_id = j.id) as bid_count 
                 FROM jobs j 
                 WHERE j.client_id = ? 
                 ORDER BY j.created_at DESC", [$user['id']]);

$pageTitle = "Manage My Jobs — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>



<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/client/manage-jobs.css'); ?>">

<main class="manage-jobs-wrap">
  <div class="page-header">
    <h1>Manage Your Projects</h1>
    <a href="/dashboard/client/post-job.php" class="btn-post-job">+ Post New Job</a>
  </div>

  <?php if (empty($jobs)): ?>
    <div class="empty-manage-jobs">
      <i class="ri-folder-open-line"></i>
      <h3>You haven't posted any jobs yet.</h3>
    </div>
  <?php else: foreach($jobs as $job): ?>
    <div class="job-card-manage">
      <div class="job-main">
        <h3><?=htmlspecialchars($job['title'])?></h3>
        <p><i class="ri-calendar-line"></i> Posted on <?=date('M d, Y', strtotime($job['created_at']))?></p>
      </div>

      <div class="job-stat">
        <strong><?=$job['bid_count']?></strong>
        <span>Proposals Received</span>
      </div>

      <div class="job-stat">
        <div class="status-pill status-<?=$job['status']?>"><?=str_replace('_', ' ', $job['status'])?></div>
      </div>

      <div style="text-align:right;">
        <a href="/dashboard/client/view-proposals.php?job_id=<?=$job['id']?>" class="btn-view-bids">Manage Bids</a>
      </div>
    </div>
  <?php endforeach; endif; ?>
</main>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
