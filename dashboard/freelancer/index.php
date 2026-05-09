<?php
/**
 * WorkBazar — Freelancer Dashboard (Enterprise Index)
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('freelancer');

$user = Auth::user();
if (!$user) {
    Auth::logout();
    header("Location: /auth/login.php");
    exit;
}

$profile = Auth::freelancerProfile();

// Fetch My Proposals (With Client Contact Info for accepted ones)
$proposals = [];
try {
    $proposals = DB::all("SELECT p.*, j.title as job_title, j.budget_type, j.status as job_status, 
                                 u.fullname as client_name, u.email as client_email, u.phone as client_phone
                          FROM proposals p 
                          JOIN jobs j ON p.job_id = j.id 
                          JOIN users u ON j.client_id = u.id
                          WHERE p.freelancer_id = ? 
                          ORDER BY p.created_at DESC LIMIT 10", [$user['id']]);
} catch (Exception $e) {
    Logger::error("Dashboard Proposals Fetch Failed: " . $e->getMessage());
}

// Count Active Jobs
$activeJobsCount = 0;
try {
    $activeJobsRow = DB::row("SELECT COUNT(*) as count FROM jobs j 
                                 JOIN proposals p ON j.id = p.job_id 
                                 WHERE p.freelancer_id = ? AND p.status = 'accepted' AND j.status = 'in_progress'", [$user['id']]);
    $activeJobsCount = $activeJobsRow['count'] ?? 0;
} catch (Exception $e) {
    Logger::error("Dashboard Active Jobs Count Failed: " . $e->getMessage());
}

$pageTitle = "Freelancer Dashboard — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/freelancer/index.css'); ?>">

<main class="dashboard-wrap">
  <div class="dash-banner">
    <div class="banner-text">
      <h1>Welcome, <?=htmlspecialchars(explode(' ', $user['fullname'] ?? 'User')[0])?>!</h1>
      <p>Your current stats and project updates are here. Let's find your next gig.</p>
      <a href="/public/find-work.php" class="btn-primary-white">Browse Job Marketplace</a>
    </div>
    
    <a href="/dashboard/freelancer/wallet.php" class="wallet-card">
      <span>My Coins</span>
      <strong><i class="ri-coin-line"></i> <?= $profile ? ($profile['coin_balance'] ?? 0) : 0 ?></strong>
      <div class="wallet-footer">Recharge Wallet →</div>
    </a>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <i class="ri-send-plane-fill"></i>
      <strong><?=count($proposals)?></strong>
      <span>Sent Proposals</span>
    </div>
    <a href="/dashboard/freelancer/active-jobs.php" class="stat-card">
      <i class="ri-hammer-line"></i>
      <strong><?=$activeJobsCount?></strong>
      <span>Active Jobs</span>
    </a>
    <a href="/dashboard/freelancer/reviews.php" class="stat-card">
      <i class="ri-star-line"></i>
      <strong><?= $profile ? ($profile['rating'] ?? '0.0') : '0.0' ?></strong>
      <span>Rating</span>
    </a>
    <a href="/dashboard/freelancer/portfolio.php" class="stat-card">
      <i class="ri-folder-user-line"></i>
      <strong><?php 
        try {
            echo DB::row("SELECT COUNT(*) as count FROM portfolios WHERE user_id = ?", [$user['id']])['count'] ?? 0;
        } catch(Exception $e) { echo 0; }
      ?></strong>
      <span>Portfolio Items</span>
    </a>
  </div>

  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <h2 style="font-size:1.5rem; font-weight:800;">My Recent Bids</h2>
    <a href="/dashboard/freelancer/my-proposals.php" class="view-all-link">View All Bids →</a>
  </div>

  <?php if (empty($proposals)): ?>
    <div class="empty-state">
      <i class="ri-mail-open-line"></i>
      <h3>No bids yet.</h3>
      <a href="/public/find-work.php">Find projects to bid on →</a>
    </div>
  <?php else: foreach($proposals as $p): ?>
    <div class="proposal-item" style="display:block;">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <div>
          <h4 style="font-size:1.1rem; font-weight:700; margin-bottom:6px;"><?=htmlspecialchars($p['job_title'] ?? 'Job Title')?></h4>
          <div style="font-size:0.85rem; color:var(--muted);">My Bid: <b>₹<?=number_format($p['bid_amount'] ?? 0)?></b> · Submitted <?=time_ago($p['created_at'] ?? date('Y-m-d H:i:s'))?></div>
        </div>
        <div class="status-badge status-<?=htmlspecialchars($p['status'] ?? 'pending')?>"><?=htmlspecialchars($p['status'] ?? 'pending')?></div>
      </div>

      <?php if (($p['status'] ?? '') === 'accepted'): ?>
        <div class="contact-reveal">
          <b>🎉 Congratulations! You are hired.</b>
          <div class="contact-row">
             <span><i class="ri-mail-line"></i> <?=htmlspecialchars($p['client_email'] ?? 'N/A')?></span>
             <?php if(!empty($p['client_phone'])): ?>
              <span><i class="ri-phone-line"></i> <?=htmlspecialchars($p['client_phone'])?></span>
             <?php endif; ?>
          </div>
          <a href="/dashboard/chat.php?proposal_id=<?=$p['id']?>" class="btn-chat" style="margin-top:12px; display:inline-flex;"><i class="ri-chat-1-line"></i> Open Project Chat</a>
        </div>
      <?php endif; ?>
    </div>
  <?php endforeach; endif; ?>

</main>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
