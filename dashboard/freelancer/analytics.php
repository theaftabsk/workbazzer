<?php
/**
 * WorkBazar — Freelancer: Earnings & Performance Analytics
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('freelancer');

$user = Auth::user();
$profile = Auth::freelancerProfile();

// Fetch Monthly Transaction Summary
$monthlyData = DB::all("SELECT DATE_FORMAT(created_at, '%M') as month, 
                               SUM(CASE WHEN type = 'purchase' THEN amount ELSE 0 END) as recharged,
                               SUM(CASE WHEN type = 'bid' THEN amount ELSE 0 END) as spent
                        FROM coin_transactions 
                        WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                        GROUP BY month 
                        ORDER BY MIN(created_at)", [$user['id']]);

$pageTitle = "My Analytics — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>



<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/freelancer/analytics.css'); ?>">

<main class="dashboard-wrap">
  <div class="page-header">
    <h1>Business Analytics 📊</h1>
    <p>Track your growth, earnings, and platform activity.</p>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
       <i class="ri-funds-line"></i>
       <strong>₹0</strong>
       <span>Total Revenue</span>
    </div>
    <div class="stat-card">
       <i class="ri-flashlight-line"></i>
       <strong><?=$profile['success_rate'] ?? '100'?>%</strong>
       <span>Success Rate</span>
    </div>
    <div class="stat-card">
       <i class="ri-copper-coin-line"></i>
       <strong><?=DB::row("SELECT SUM(amount) as s FROM coin_transactions WHERE user_id = ? AND type='bid'", [$user['id']])['s'] ?? 0?></strong>
       <span>Coins Invested</span>
    </div>
    <div class="stat-card">
       <i class="ri-user-voice-line"></i>
       <strong><?=DB::row("SELECT COUNT(*) as c FROM proposals WHERE freelancer_id = ?", [$user['id']])['c']?></strong>
       <span>Total Bids</span>
    </div>
  </div>

  <div class="admin-table-card" style="margin-top:40px;">
    <h2 class="analytics-table-header">Monthly Activity Report (Last 6 Months)</h2>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Month</th>
          <th>Coins Recharged</th>
          <th>Coins Spent (Bidding)</th>
          <th>Growth Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($monthlyData)): ?>
          <tr><td colspan="4" class="empty-analytics-row">No data available yet.</td></tr>
        <?php else: foreach($monthlyData as $row): ?>
          <tr>
            <td class="table-cell-month"><?=$row['month']?></td>
            <td class="table-cell-recharged"><?=$row['recharged']?></td>
            <td class="table-cell-spent"><?=$row['spent']?></td>
            <td>
              <?php if($row['recharged'] > $row['spent']): ?>
                <span class="status-positive"><i class="ri-arrow-up-line"></i> Positive</span>
              <?php else: ?>
                <span class="status-stable">Stable</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</main>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
