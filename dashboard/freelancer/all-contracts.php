<?php
/**
 * Freelancer All Contracts
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();
Auth::requireRole('freelancer');
$pageTitle = "All Contracts — WorkBazar";
require_once __DIR__ . '/../../includes/layouts/header.php';
require_once __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/freelancer/all-contracts.css'); ?>">

<main class="dashboard-main">
  <div class="contracts-container">
    <div class="c-header">
      <h1>All Contracts</h1>
      <p>View your active and past project history.</p>
    </div>

    <div class="c-list">
      <!-- Active Contract -->
      <div class="c-item">
        <div class="c-info">
          <h3>E-commerce Website Development</h3>
          <p>Client: TechFlow Inc.</p>
          <div class="c-meta">
            <span><i class="ri-money-dollar-circle-line"></i> Amount: $2,000</span>
            <span><i class="ri-calendar-line"></i> Started: Oct 15, 2026</span>
            <span class="status-badge status-active">Active</span>
          </div>
        </div>
        <div class="c-actions">
          <a href="#" class="btn-view">View Details</a>
        </div>
      </div>

      <!-- Completed Contract -->
      <div class="c-item">
        <div class="c-info">
          <h3>Logo and Branding Design</h3>
          <p>Client: Studio Nine</p>
          <div class="c-meta">
            <span><i class="ri-money-dollar-circle-line"></i> Amount: $500</span>
            <span><i class="ri-calendar-line"></i> Completed: Sep 20, 2026</span>
            <span class="status-badge status-completed">Completed</span>
          </div>
        </div>
        <div class="c-actions">
          <a href="#" class="btn-view">View Details</a>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/../../includes/layouts/footer.php'; ?>
