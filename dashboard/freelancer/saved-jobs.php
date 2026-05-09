<?php
/**
 * Freelancer Saved Jobs
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();
Auth::requireRole('freelancer');
$pageTitle = "Saved Jobs — WorkBazar";
require_once __DIR__ . '/../../includes/layouts/header.php';
require_once __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/freelancer/saved-jobs.css'); ?>">

<main class="dashboard-main">
  <div class="saved-jobs-container">
    <div class="sj-header">
      <h1>Saved Jobs</h1>
      <p>Keep track of jobs you're interested in applying for.</p>
    </div>

    <div class="sj-list">
      <!-- Dummy Job 1 -->
      <div class="sj-item">
        <div class="sj-info">
          <h3>Full-Stack React & Node.js Developer Needed</h3>
          <p>We are looking for an experienced developer to build our MVP. Must know React, Node, and MySQL.</p>
          <div class="sj-meta">
            <span><i class="ri-money-dollar-circle-line"></i> Est. Budget: $1,500</span>
            <span><i class="ri-time-line"></i> Posted 2 hours ago</span>
          </div>
        </div>
        <div class="sj-actions">
          <a href="#" class="btn-remove"><i class="ri-heart-3-fill"></i> Unsave</a>
          <a href="/public/job-details.php?id=1" class="btn-apply">Apply Now</a>
        </div>
      </div>

      <!-- Dummy Job 2 -->
      <div class="sj-item">
        <div class="sj-info">
          <h3>WordPress Theme Customization</h3>
          <p>Need someone to customize a premium WordPress theme and set up WooCommerce.</p>
          <div class="sj-meta">
            <span><i class="ri-money-dollar-circle-line"></i> Hourly: $20 - $40/hr</span>
            <span><i class="ri-time-line"></i> Posted 1 day ago</span>
          </div>
        </div>
        <div class="sj-actions">
          <a href="#" class="btn-remove"><i class="ri-heart-3-fill"></i> Unsave</a>
          <a href="/public/job-details.php?id=2" class="btn-apply">Apply Now</a>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/../../includes/layouts/footer.php'; ?>
