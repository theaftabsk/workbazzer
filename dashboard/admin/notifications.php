<?php
/**
 * WorkBazar — Admin: Notification Center
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('admin');

$user = Auth::user();

// Mark all as read
DB::query("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0", [$user['id']]);

// Fetch Notifications
$notifications = DB::all("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 50", [$user['id']]);

$pageTitle = "Admin Alerts — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>



<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/admin/notifications.css'); ?>">

<main class="dashboard-wrap">
  <div class="page-header">
    <h1>System Notifications 🔔</h1>
    <p>Manage system alerts and administrative updates.</p>
  </div>

  <div class="noti-list">
    <?php if (empty($notifications)): ?>
      <div class="empty-notifications">
        <i class="ri-notification-off-line"></i>
        <h3>No system alerts yet.</h3>
      </div>
    <?php else: foreach($notifications as $n): ?>
      <div class="noti-item">
        <div class="noti-dot <?= $n['is_read'] ? '' : 'unread' ?>"></div>
        <div class="noti-content">
          <div class="noti-title"><?=htmlspecialchars($n['message'])?></div>
          <div class="noti-time"><?=time_ago($n['created_at'])?></div>
        </div>
      </div>
    <?php endforeach; endif; ?>
  </div>
</main>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
