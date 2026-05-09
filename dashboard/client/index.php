<?php
/**
 * WorkBazar — Client Dashboard (Enterprise Index)
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('client');

$user = Auth::user();
$profile = null;
$jobs = [];
$activeContractsCount = 0;

try {
    $profile = DB::row("SELECT * FROM client_profiles WHERE user_id = ?", [$user['id']]);

    // Fetch Posted Jobs
    $jobs = DB::all("SELECT j.*, (SELECT COUNT(*) FROM proposals WHERE job_id = j.id) as bid_count 
                     FROM jobs j
                     WHERE j.client_id = ? 
                     ORDER BY j.created_at DESC", [$user['id']]);

    // Count Active Contracts
    $activeContractsRow = DB::row("SELECT COUNT(*) as count FROM jobs WHERE client_id = ? AND status = 'in_progress'", [$user['id']]);
    $activeContractsCount = $activeContractsRow['count'] ?? 0;
} catch (Exception $e) {
    Logger::error("Client Dashboard Data Fetch Failed: " . $e->getMessage());
}

$pageTitle = "Client Dashboard — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>





<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/client/index.css'); ?>">

<main class="dashboard-wrap">
  <!-- Top Banner -->
  <div class="dash-banner-dark">
    <div class="welcome-text">
      <h1>Hello, <?=htmlspecialchars(explode(' ', $user['fullname'])[0])?>! 👋</h1>
      <p>Welcome back to your client dashboard. Ready to build something amazing?</p>
    </div>
    <a href="/dashboard/client/post-job.php" class="btn-primary-white">
      <i class="ri-add-line"></i> Post a New Project
    </a>
  </div>

  <!-- Stats -->
  <div class="stats-grid">
    <div class="stat-card stat-card-flex">
      <div class="stat-text">
        <strong><?= $profile ? ($profile['total_jobs'] ?? 0) : 0 ?></strong>
        <span>Projects Posted</span>
      </div>
      <i class="ri-briefcase-line"></i>
    </div>
    <a href="/dashboard/client/active-jobs.php" class="stat-card stat-card-flex">
      <div class="stat-text">
        <strong><?=$activeContractsCount?></strong>
        <span>Active Contracts</span>
      </div>
      <i class="ri-user-follow-line"></i>
    </a>
    <div class="stat-card stat-card-flex">
      <div class="stat-text">
        <strong>₹<?= number_format($profile ? ($profile['total_spent'] ?? 0) : 0) ?></strong>
        <span>Total Investment</span>
      </div>
      <i class="ri-wallet-3-line"></i>
    </div>
  </div>

  <!-- Recent Projects -->
  <div class="section-header">
    <h2>Your Recent Projects</h2>
  </div>

  <?php if (empty($jobs)): ?>
    <div class="empty-state">
      <i class="ri-folder-open-line"></i>
      <h3>You haven't posted any projects yet.</h3>
      <a href="/dashboard/client/post-job.php">Post your first project →</a>
    </div>
  <?php else: foreach($jobs as $job): ?>
    <div class="job-item">
      <div class="job-info">
        <h4><?=htmlspecialchars($job['title'])?></h4>
        <div class="job-meta">
          <span><i class="ri-calendar-line"></i> <?=date('M d, Y', strtotime($job['created_at']))?></span>
          <span><i class="ri-price-tag-3-line"></i> ₹<?=$job['budget_min']?> - ₹<?=$job['budget_max']?></span>
          <span><i class="ri-focus-2-line"></i> Status: <b style="text-transform:capitalize; color:var(--green);"><?=$job['status']?></b></span>
        </div>
      </div>
      
      <?php if ($job['bid_count'] > 0): ?>
        <a href="/dashboard/client/view-proposals.php?job_id=<?=$job['id']?>" class="bid-badge">
          View <?=$job['bid_count']?> Proposals <i class="ri-arrow-right-s-line"></i>
        </a>
      <?php else: ?>
        <span class="waiting-badge">Waiting for proposals...</span>
      <?php endif; ?>
    </div>
  <?php endforeach; endif; ?>
</main>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
