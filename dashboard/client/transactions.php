<?php
/**
 * Client Transactions
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();
Auth::requireRole('client');
$pageTitle = "Transaction History — WorkBazar";
require_once __DIR__ . '/../../includes/layouts/header.php';
require_once __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/client/finance.css'); ?>">

<main class="dashboard-main">
  <div class="finance-container">
    <div class="f-header">
      <h1>Transaction History</h1>
      <p>A detailed record of all payments, refunds, and adjustments.</p>
    </div>

    <div class="f-card">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
        <h2 style="margin:0; border:none; padding:0;">All Transactions</h2>
        <a href="#" class="btn-download"><i class="ri-file-excel-line"></i> Export CSV</a>
      </div>
      
      <table class="f-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Balance</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Oct 15, 2026</td>
            <td>Payment</td>
            <td>Milestone 1 - E-commerce Website</td>
            <td style="color:#ef4444;">-$1,500.00</td>
            <td>$0.00</td>
          </tr>
          <tr>
            <td>Oct 10, 2026</td>
            <td>Deposit</td>
            <td>Escrow Funding (Visa ending in 4242)</td>
            <td style="color:var(--green);">+$1,500.00</td>
            <td>$1,500.00</td>
          </tr>
          <tr>
            <td>Oct 01, 2026</td>
            <td>Payment</td>
            <td>Hourly Payment (Michael Chen)</td>
            <td style="color:#ef4444;">-$450.00</td>
            <td>$0.00</td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</main>

<?php require_once __DIR__ . '/../../includes/layouts/footer.php'; ?>
