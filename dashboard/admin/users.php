<?php
/**
 * WorkBazar — Admin: User Management Center
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('admin');

// Fetch All Users with their profile summaries
$users = DB::all("SELECT u.*, 
                 (CASE 
                    WHEN u.role = 'freelancer' THEN (SELECT coin_balance FROM freelancer_profiles WHERE user_id = u.id)
                    WHEN u.role = 'client' THEN (SELECT total_spent FROM client_profiles WHERE user_id = u.id)
                    ELSE 0 
                  END) as meta_val
                 FROM users u 
                 ORDER BY u.created_at DESC");

$pageTitle = "User Management — WorkBazar Admin";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>





<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/admin/users.css'); ?>">

<main class="dashboard-wrap">
  <div class="page-header">
    <h1>User Management</h1>
    <div class="user-count"><?=count($users)?> Total Users</div>
  </div>

  <div class="admin-table-card">
    <table class="admin-table">
      <thead>
        <tr>
          <th>User Info</th>
          <th>Account Role</th>
          <th>Wallet / Spent</th>
          <th>Status</th>
          <th>Joined</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($users as $u): ?>
        <tr>
          <td>
            <div class="user-info">
              <div class="user-avatar">
                <?php if($u['avatar']): ?>
                  <img src="<?=$u['avatar']?>" alt="Avatar">
                <?php else: ?>
                  <i class="ri-user-3-fill"></i>
                <?php endif; ?>
              </div>
              <div>
                <div class="user-name"><?=htmlspecialchars($u['fullname'])?></div>
                <div class="user-email"><?=htmlspecialchars($u['email'])?></div>
              </div>
            </div>
          </td>
          <td><span class="role-badge role-<?=$u['role']?>"><?=$u['role']?></span></td>
          <td>
            <?php if($u['role'] === 'freelancer'): ?>
              <span class="user-meta-val"><i class="ri-coin-line"></i> <?=$u['meta_val']?></span>
            <?php elseif($u['role'] === 'client'): ?>
              <span class="user-meta-val">₹<?=number_format($u['meta_val'])?></span>
            <?php else: ?>
              —
            <?php endif; ?>
          </td>
          <td><span class="status-dot status-active"></span> <span class="status-active-label">Active</span></td>
          <td><span class="user-joined-date"><?=date('M d, Y', strtotime($u['created_at']))?></span></td>
          <td>
            <a href="#" class="btn-action">Manage</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
