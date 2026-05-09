<?php
/**
 * WorkBazar — Blog & Resources
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();
$pageTitle = "Blog & Resources — WorkBazar";
require_once __DIR__ . '/../../includes/layouts/header.php';
require_once __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/public/why-workbazar/blog.css'); ?>">

<div class="wb-page-header">
  <h1>Blog & Resources</h1>
  <p>Read the latest insights on freelancing, hiring, and remote work.</p>
</div>

<div class="wb-content-section">
  <i class="ri-article-line"></i>
  <h2>Coming Soon</h2>
  <p>We are currently writing amazing content to help you succeed on WorkBazar. Check back soon for guides, tips, and industry reports.</p>
</div>

<?php require_once __DIR__ . '/../../includes/layouts/footer.php'; ?>
