<?php
/**
 * WorkBazar — Freelancer: Active Contracts
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('freelancer');

$user = Auth::user();
$activeJobs = [];

try {
    // Fetch Active Jobs (Where Proposal is Accepted)
    $activeJobs = DB::all("SELECT p.*, j.title as job_title, j.budget_min, j.budget_max, 
                                   u.fullname as client_name, u.email as client_email, u.phone as client_phone
                            FROM proposals p 
                            JOIN jobs j ON p.job_id = j.id 
                            JOIN users u ON j.client_id = u.id
                            WHERE p.freelancer_id = ? AND p.status = 'accepted' AND j.status = 'in_progress'
                            ORDER BY p.updated_at DESC", [$user['id']]);
} catch (Exception $e) {
    Logger::error("Freelancer Active Jobs Fetch Failed: " . $e->getMessage());
}

$pageTitle = "Active Jobs — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>


<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/freelancer/active-jobs.css'); ?>">

<main class="dashboard-wrap">
  <div class="page-header">
    <h1>Running Projects 🏗️</h1>
    <p>Manage your active contracts and stay in touch with clients.</p>
  </div>

  <?php if (empty($activeJobs)): ?>
    <div class="empty-active-jobs" style="padding: 60px 20px; text-align: center; background: #fff; border-radius: 16px; border: 1px dashed var(--border);">
      <i class="ri-hammer-line" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 16px; display: block;"></i>
      <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--ink); margin-bottom: 12px;">You don't have any active jobs yet.</h3>
      <a href="/public/find-work.php" style="color: var(--green); font-weight: 700; text-decoration: none;">Browse jobs to get hired →</a>
    </div>
  <?php else: foreach($activeJobs as $aj): ?>
    <div class="active-job-card">
      <div class="job-card-header">
        <div class="job-card-title">
          <h2><?=htmlspecialchars($aj['job_title'] ?? 'Job Title')?></h2>
          <div class="job-card-client">Hired by: <strong><?=htmlspecialchars($aj['client_name'] ?? 'Client Name')?></strong></div>
        </div>
        <div class="status-badge">In Progress</div>
      </div>

      <div class="contact-reveal">
        <div class="contact-reveal-title">Direct Client Contact:</div>
        <div class="contact-details">
          <span><i class="ri-mail-line"></i> <?=htmlspecialchars($aj['client_email'] ?? 'N/A')?></span>
          <?php if(!empty($aj['client_phone'])): ?>
            <span><i class="ri-phone-line"></i> <?=htmlspecialchars($aj['client_phone'])?></span>
          <?php endif; ?>
        </div>
      </div>

      <div class="job-card-footer">
        <div class="job-agreement">Agreement: <strong>₹<?=number_format($aj['bid_amount'] ?? 0)?></strong></div>
        <a href="/dashboard/chat.php?proposal_id=<?= $aj['id'] ?>" class="workspace-link">View Workspace →</a>
      </div>
    </div>
  <?php endforeach; endif; ?>
</main>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
