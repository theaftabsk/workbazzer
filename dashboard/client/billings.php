<?php
/**
 * Client Billings & Invoices
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();
Auth::requireRole('client');
$pageTitle = "Billings & Invoices — WorkBazar";
require_once __DIR__ . '/../../includes/layouts/header.php';
require_once __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/client/finance.css'); ?>">

<main class="dashboard-main">
  <div class="finance-container">
    <div class="f-header">
      <h1>Billings & Invoices</h1>
      <p>Manage your payment methods and download past invoices.</p>
    </div>

    <!-- Payment Methods -->
    <div class="f-card">
      <h2>Payment Methods</h2>
      <div class="pm-item">
        <i class="ri-visa-line pm-icon" style="color: #1a1f71;"></i>
        <div class="pm-details">
          <h4>Visa ending in 4242</h4>
          <p>Expires 12/2028 • Primary</p>
        </div>
      </div>
      <a href="#" class="btn-add"><i class="ri-add-line"></i> Add Payment Method</a>
    </div>

    <!-- Invoices -->
    <div class="f-card">
      <h2>Recent Invoices</h2>
      <table class="f-table">
        <thead>
          <tr>
            <th>Invoice ID</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>INV-2026-001</td>
            <td>Oct 15, 2026</td>
            <td>$1,500.00</td>
            <td><span class="status-badge status-paid">Paid</span></td>
            <td><a href="#" class="btn-download"><i class="ri-download-2-line"></i> PDF</a></td>
          </tr>
          <tr>
            <td>INV-2026-002</td>
            <td>Oct 01, 2026</td>
            <td>$450.00</td>
            <td><span class="status-badge status-paid">Paid</span></td>
            <td><a href="#" class="btn-download"><i class="ri-download-2-line"></i> PDF</a></td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</main>

<?php require_once __DIR__ . '/../../includes/layouts/footer.php'; ?>
