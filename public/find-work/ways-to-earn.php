<?php
/**
 * WorkBazar — Ways to Earn
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();
$pageTitle = "Ways to Earn — WorkBazar";
require_once __DIR__ . '/../../includes/layouts/header.php';
require_once __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/public/find-work-pages.css'); ?>">

<main>
  <div class="wb-page-header">
    <h1>Multiple Ways to Earn</h1>
    <p>Discover how WorkBazar gives you the flexibility to build your career and scale your income on your own terms.</p>
  </div>

  <div class="wb-content-section">
    <div class="wb-feature-row">
      <div class="wb-feature-text">
        <h2>Hourly Projects</h2>
        <p>Work on complex, long-term projects with guaranteed payment for every hour you work. Track your time easily with our desktop app.</p>
        <ul class="feature-list">
          <li><i class="ri-check-line"></i> Weekly automatic payouts</li>
          <li><i class="ri-check-line"></i> Built-in time tracker protection</li>
          <li><i class="ri-check-line"></i> Ideal for ongoing client relationships</li>
        </ul>
      </div>
      <div class="wb-feature-img">
        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800&q=80" alt="Hourly Projects">
      </div>
    </div>

    <div class="wb-feature-row">
      <div class="wb-feature-text">
        <h2>Fixed-Price Contracts</h2>
        <p>Agree on a price before the work begins. Funds are held safely in escrow and released as you hit milestones.</p>
        <ul class="feature-list">
          <li><i class="ri-check-line"></i> Milestone-based payments</li>
          <li><i class="ri-check-line"></i> Escrow protection</li>
          <li><i class="ri-check-line"></i> Total control over your pricing</li>
        </ul>
      </div>
      <div class="wb-feature-img">
        <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=800&q=80" alt="Fixed Price Contracts">
      </div>
    </div>

    <div class="wb-cta-banner">
      <h2>Ready to start earning?</h2>
      <p>Join millions of top professionals who are building their dream careers on WorkBazar.</p>
      <a href="/auth/register.php" class="btn-white">Create Your Profile</a>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/../../includes/layouts/footer.php'; ?>
