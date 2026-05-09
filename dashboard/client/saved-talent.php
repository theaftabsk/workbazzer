<?php
/**
 * Client Saved Talent
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();
Auth::requireRole('client');
$pageTitle = "Saved Talent — WorkBazar";
require_once __DIR__ . '/../../includes/layouts/header.php';
require_once __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/client/saved-talent.css'); ?>">

<main class="dashboard-main">
  <div class="saved-talent-container">
    <div class="st-header">
      <h1>Saved Talent</h1>
      <p>Your shortlisted freelancers for future projects.</p>
    </div>

    <div class="st-list">
      <!-- Dummy Talent 1 -->
      <div class="st-item">
        <div class="st-profile">
          <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop" class="st-avatar" alt="User">
          <div class="st-info">
            <h3>Sarah Jenkins</h3>
            <p>Senior UI/UX Designer & Product Strategist</p>
            <div class="st-meta">
              <span><i class="ri-star-fill"></i> 4.9 (120 reviews)</span>
              <span><i class="ri-money-dollar-circle-line"></i> $45/hr</span>
            </div>
          </div>
        </div>
        <div class="st-actions">
          <a href="#" class="btn-remove"><i class="ri-heart-3-fill"></i> Unsave</a>
          <a href="#" class="btn-invite">Invite to Job</a>
        </div>
      </div>

      <!-- Dummy Talent 2 -->
      <div class="st-item">
        <div class="st-profile">
          <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=100&h=100&fit=crop" class="st-avatar" alt="User">
          <div class="st-info">
            <h3>Michael Chen</h3>
            <p>Full-Stack Web Developer (React/Node)</p>
            <div class="st-meta">
              <span><i class="ri-star-fill"></i> 5.0 (85 reviews)</span>
              <span><i class="ri-money-dollar-circle-line"></i> $60/hr</span>
            </div>
          </div>
        </div>
        <div class="st-actions">
          <a href="#" class="btn-remove"><i class="ri-heart-3-fill"></i> Unsave</a>
          <a href="#" class="btn-invite">Invite to Job</a>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/../../includes/layouts/footer.php'; ?>
