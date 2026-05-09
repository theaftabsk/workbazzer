<?php
/**
 * WorkBazar — Master Admin Dashboard
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('admin');

$user = Auth::user();

// Fetch Global Stats
$stats = [
    'users'     => DB::row("SELECT COUNT(*) as count FROM users")['count'],
    'clients'   => DB::row("SELECT COUNT(*) as count FROM users WHERE role='client'")['count'],
    'freelancers' => DB::row("SELECT COUNT(*) as count FROM users WHERE role='freelancer'")['count'],
    'jobs'      => DB::row("SELECT COUNT(*) as count FROM jobs")['count'],
    'proposals' => DB::row("SELECT COUNT(*) as count FROM proposals")['count'],
    'coins_issued' => DB::row("SELECT SUM(coin_balance) as total FROM freelancer_profiles")['total'] ?? 0
];

$recentUsers = DB::all("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");

$pageTitle = "Admin Master Panel — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>





<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/admin/index.css'); ?>">

<main class="dashboard-wrap">
  <div class="dash-banner-admin">
    <div class="welcome-text-admin">
      <h1>Master Control Panel</h1>
      <p>Welcome, System Administrator. Everything is running smoothly.</p>
    </div>
    <div class="engine-status">
      <div class="engine-version">WorkBazar Engine v1.0</div>
      <div class="engine-health">Status: Operational ✅</div>
    </div>
  </div>

  <div class="stats-grid">
    <div class="stat-card stat-card-admin">
      <div class="stat-text-admin">
        <strong><?=$stats['users']?></strong>
        <span>Total Registered Users</span>
      </div>
      <i class="ri-group-line stat-icon-admin"></i>
    </div>
    <div class="stat-card stat-card-admin">
      <div class="stat-text-admin">
        <strong><?=$stats['jobs']?></strong>
        <span>Total Projects Posted</span>
      </div>
      <i class="ri-briefcase-line stat-icon-admin"></i>
    </div>
    <div class="stat-card stat-card-admin">
      <div class="stat-text-admin">
        <strong><?=$stats['proposals']?></strong>
        <span>Total Proposals</span>
      </div>
      <i class="ri-send-plane-line stat-icon-admin"></i>
    </div>
  </div>

  <!-- Recent Users Table -->
  <div class="admin-table-card">
    <div class="table-header">
      <h2>Recently Joined Users</h2>
      <div class="table-header-actions">
        <a href="/dashboard/admin/jobs.php" class="link-manage">Manage All Jobs →</a>
        <a href="/dashboard/admin/users.php" class="link-manage">View All Users →</a>
      </div>
    </div>

    <table class="admin-table">
      <thead>
        <tr>
          <th>Full Name</th>
          <th>Email Address</th>
          <th>Account Role</th>
          <th>Joined Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($recentUsers as $ru): ?>
        <tr>
          <td><strong><?=htmlspecialchars($ru['fullname'])?></strong></td>
          <td><?=htmlspecialchars($ru['email'])?></td>
          <td><span class="role-badge role-<?=$ru['role']?>"><?=$ru['role']?></span></td>
          <td><?=date('M d, Y', strtotime($ru['created_at']))?></td>
          <td><a href="#" class="link-manage">Manage</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
