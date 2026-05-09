<?php
/**
 * WorkBazar — Promoted Ads
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();
$pageTitle = "Win Work with Promoted Ads — WorkBazar";
require_once __DIR__ . '/../../includes/layouts/header.php';
require_once __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/public/find-work-pages.css'); ?>">

<main>
  <div class="wb-page-header">
    <h1>Win work with Promoted Ads</h1>
    <p>Boost your visibility and get hired faster. Put your profile at the top of client search results.</p>
  </div>

  <div class="wb-content-section">
    <div class="wb-feature-row">
      <div class="wb-feature-text">
        <h2>Stand out from the crowd</h2>
        <p>With thousands of freelancers on the platform, getting noticed is the first step to winning a job. Promoted Ads highlight your profile to high-value clients.</p>
        <ul class="feature-list">
          <li><i class="ri-rocket-line"></i> Appear at the top of searches</li>
          <li><i class="ri-eye-line"></i> Increase profile views by 300%</li>
          <li><i class="ri-briefcase-line"></i> Get invited to more interviews</li>
        </ul>
      </div>
      <div class="wb-feature-img">
        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&q=80" alt="Promoted Ads Analytics">
      </div>
    </div>

    <div class="wb-cta-banner">
      <h2>Take control of your growth</h2>
      <p>Log in to your dashboard to activate Promoted Ads and start winning more work today.</p>
      <a href="/auth/login.php" class="btn-white">Go to Dashboard</a>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/../../includes/layouts/footer.php'; ?>
