<?php
/**
 * WorkBazar — Admin: Platform Settings
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('admin');

$user = Auth::user();

// Load current settings
$signupBonusEnabled = App::setting('signup_bonus_enabled', '1');
$signupCoins        = App::setting('signup_coins', '20');

// Handle save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_bonus'])) {
    Security::verifyCsrf();
    $enabled = isset($_POST['signup_bonus_enabled']) ? '1' : '0';
    $coins   = max(0, (int)($_POST['signup_coins'] ?? 0));

    DB::query("INSERT INTO settings (`key`, value) VALUES ('signup_bonus_enabled', ?)
               ON DUPLICATE KEY UPDATE value = ?", [$enabled, $enabled]);
    DB::query("INSERT INTO settings (`key`, value) VALUES ('signup_coins', ?)
               ON DUPLICATE KEY UPDATE value = ?", [$coins, $coins]);

    header("Location: settings.php?saved=1");
    exit;
}

$pageTitle = "Admin Settings — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>



<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/admin/settings.css'); ?>">

<main class="dashboard-wrap">
  <div class="page-header">
    <h1>Platform Settings ⚙️</h1>
    <p>Control your platform's behavior from one place.</p>
  </div>

  <?php if (isset($_GET['saved'])): ?>
  <div class="success-banner">
    <i class="ri-checkbox-circle-fill"></i> Settings saved successfully!
  </div>
  <?php endif; ?>

  <div class="settings-grid">

    <!-- ── Signup Bonus Card ───────────────────────── -->
    <div class="settings-card">
      <h3>🎁 Signup Bonus Coins</h3>
      <p class="card-desc">Control the free coin bonus given to new freelancers on registration. Turn it on/off anytime and set any amount.</p>

      <form method="POST">
        <?= Security::csrfField() ?>
        <input type="hidden" name="save_bonus" value="1">

        <div class="toggle-row">
          <span class="toggle-label">Bonus Active</span>
          <label class="toggle-switch">
            <input type="checkbox" name="signup_bonus_enabled"
              <?= $signupBonusEnabled === '1' ? 'checked' : '' ?>>
            <span class="toggle-slider"></span>
          </label>
        </div>

        <label class="input-label">
          Coins to give on signup
        </label>
        <input type="number" name="signup_coins" class="s-input"
               value="<?= htmlspecialchars($signupCoins) ?>" min="0" max="9999"
               placeholder="e.g. 20">

        <div class="status-chip">
          <i class="ri-coin-line"></i>
          Status: <strong><?= $signupBonusEnabled === '1' ? '✅ ON' : '❌ OFF' ?></strong>
          &nbsp;·&nbsp; Amount: <strong><?= htmlspecialchars($signupCoins) ?> coins</strong>
        </div>

        <button type="submit" class="save-btn save-btn-green">
          <i class="ri-save-line"></i> Save Bonus Settings
        </button>
      </form>
    </div>

    <!-- ── Admin Profile Card ─────────────────────── -->
    <div class="settings-card">
      <h3>👤 Admin Profile</h3>
      <p class="card-desc">Update your administrative identity and contact details.</p>

      <form id="profileForm">
        <?= Security::csrfField() ?>
        <label style="display:block; font-weight:700; margin-bottom:8px; font-size:0.88rem; color:var(--muted); text-transform:uppercase; letter-spacing:.5px;">Full Name</label>
        <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>"
               class="s-input" style="font-size:0.95rem;" required>

        <label style="display:block; font-weight:700; margin-bottom:8px; font-size:0.88rem; color:var(--muted); text-transform:uppercase; letter-spacing:.5px;">Admin Phone</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
               class="s-input" style="font-size:0.95rem;">

        <div style="display:flex; gap:12px; margin-top:20px;">
          <button type="submit" id="profileBtn" class="save-btn save-btn-dark" style="flex:1; cursor:pointer;">
            <i class="ri-save-line"></i> Save Admin Profile
          </button>
          <a href="/auth/logout.php" class="save-btn" style="background:#ef4444; color:#fff; text-decoration:none; display:flex; align-items:center; justify-content:center; flex:1; cursor:pointer; font-weight:700;">
            <i class="ri-logout-box-r-line"></i> Log out
          </a>
        </div>
      </form>
    </div>

  </div>
</main>

<script>
document.getElementById('profileForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('profileBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="ri-loader-4-line"></i> Saving...';
    const fd   = new FormData(e.target);
    const data = Object.fromEntries(fd.entries());
    try {
        const res    = await fetch('/api/update_profile.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': data._csrf },
            body: JSON.stringify(data)
        });
        const result = await res.json();
        if (result.success) {
            window.location.href = 'settings.php?saved=1';
        } else {
            alert(result.message || 'Failed to update.');
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-save-line"></i> Save Admin Profile';
        }
    } catch (err) {
        alert('Connection error. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<i class="ri-save-line"></i> Save Admin Profile';
    }
});
</script>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
