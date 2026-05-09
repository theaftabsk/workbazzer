<?php
/**
 * WorkBazar — Top Verified Talent
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();
$pageTitle = "Top Verified Talent — WorkBazar";
require_once __DIR__ . '/../../includes/layouts/header.php';
require_once __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/public/why-workbazar/top-talent.css'); ?>">

<div class="wb-page-header">
  <h1>Top Verified Talent</h1>
  <p>Work with the top 1% of freelance professionals around the world.</p>
</div>

<div class="wb-content-section">
  <div class="wb-feature-row">
    <div class="wb-feature-text">
      <h2>Verified Top 1% Talent</h2>
      <p>Every freelancer on WorkBazar undergoes a rigorous identity and skill verification process. We ensure you only work with professionals who deliver results.</p>
    </div>
    <div class="wb-feature-img">
      <img src="https://images.unsplash.com/photo-1522071823991-b1ae657b00c5?w=800&q=80" alt="Verified Talent">
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../includes/layouts/footer.php'; ?>
