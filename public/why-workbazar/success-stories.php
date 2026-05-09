<?php
/**
 * WorkBazar — Success Stories
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();
$pageTitle = "Success Stories — WorkBazar";
require_once __DIR__ . '/../../includes/layouts/header.php';
require_once __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/public/why-workbazar/success-stories.css'); ?>">

<div class="wb-page-header">
  <h1>Success Stories</h1>
  <p>Discover how leading enterprises and startups scaled their operations with WorkBazar talent.</p>
</div>

<div class="wb-content-section">
  <div class="wb-feature-row">
    <div class="wb-feature-text">
      <h2>Scaling IT Infrastructure</h2>
      <p>"WorkBazar helped us find a team of top-tier cloud architects in under 48 hours, enabling us to migrate our legacy systems without downtime." — TechCorp Inc.</p>
    </div>
    <div class="wb-feature-img">
      <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=800&q=80" alt="Success Story">
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/layouts/footer.php'; ?>
