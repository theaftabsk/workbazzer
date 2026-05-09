<?php
/**
 * WorkBazar — Trusted & Secure Platform
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();
$pageTitle = "Trusted & Secure Platform — WorkBazar";
require_once __DIR__ . '/../../includes/layouts/header.php';
require_once __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/public/why-workbazar/trusted-secure.css'); ?>">

<div class="wb-page-header">
  <h1>Trusted & Secure Platform</h1>
  <p>We're building the world's most secure and intelligent marketplace for enterprise-grade talent.</p>
</div>

<div class="wb-content-section">
  <div class="wb-feature-row">
    <div class="wb-feature-text">
      <h2>Secure Escrow Payments</h2>
      <p>Your money is safe with us. We hold funds in escrow and only release them to the freelancer once you've reviewed and approved the completed work.</p>
    </div>
    <div class="wb-feature-img">
      <img src="https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=800&q=80" alt="Secure Payments">
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/layouts/footer.php'; ?>
