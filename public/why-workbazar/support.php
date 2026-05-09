<?php
/**
 * WorkBazar — 24/7 Support
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();
$pageTitle = "24/7 Support — WorkBazar";
require_once __DIR__ . '/../../includes/layouts/header.php';
require_once __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/public/why-workbazar/support.css'); ?>">

<div class="wb-page-header">
  <h1>24/7 Premium Support</h1>
  <p>We're here around the clock to help you with your projects, payments, and account needs.</p>
</div>

<div class="wb-content-section">
  <div class="wb-feature-row">
    <div class="wb-feature-text">
      <h2>Always online for you</h2>
      <p>Whether it's a dispute resolution or a technical query, our award-winning support team is available 24 hours a day, 7 days a week.</p>
    </div>
    <div class="wb-feature-img">
      <img src="https://images.unsplash.com/photo-1516387938699-a93567ec168e?w=800&q=80" alt="Customer Support">
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/layouts/footer.php'; ?>
