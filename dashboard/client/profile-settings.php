<?php
/**
 * WorkBazar — Client Profile Settings
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('client');

$user = Auth::user();
$profile = DB::row("SELECT * FROM client_profiles WHERE user_id = ?", [$user['id']]);

$pageTitle = "Company Profile Settings — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/client/index.css'); ?>">
<style>
    .settings-card { background: #fff; border-radius: 24px; border: 1px solid var(--border); padding: 40px; max-width: 800px; margin: 0 auto; }
    .form-group { margin-bottom: 24px; }
    .form-label { display: block; font-weight: 700; margin-bottom: 8px; color: var(--ink); }
    .form-input { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border); background: #f8fafc; font-size: 1rem; outline: none; transition: 0.3s; }
    .form-input:focus { border-color: var(--green); background: #fff; box-shadow: 0 0 0 4px var(--green-glow); }
</style>

<main class="dashboard-wrap">
    <div class="page-header" style="text-align:center;">
        <h1>Company Settings 💼</h1>
        <p>Manage your business profile and contact information.</p>
    </div>

    <div class="settings-card">
        <form id="settingsForm">
            <?= Security::csrfField() ?>
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="fullname" class="form-input" value="<?= htmlspecialchars($user['fullname']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Company Name</label>
                <input type="text" name="company" class="form-input" value="<?= htmlspecialchars($profile['company_name'] ?? '') ?>" placeholder="e.g. TechFlow Solutions">
            </div>

            <div class="form-group">
                <label class="form-label">Contact Phone</label>
                <input type="text" name="phone" class="form-input" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            </div>

            <div style="display:flex; gap:12px;">
                <button type="submit" id="saveBtn" class="btn-primary" style="flex:2;">Update Profile</button>
                <a href="/auth/logout.php" class="btn-primary" style="flex:1; background:#ef4444; display:flex; align-items:center; justify-content:center;">Log out</a>
            </div>
        </form>
    </div>
</main>

<script>
document.getElementById('settingsForm').onsubmit = async (e) => {
    e.preventDefault();
    const btn = document.getElementById('saveBtn');
    btn.disabled = true; btn.innerText = 'Updating...';
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    try {
        const res = await fetch('/api/update_profile.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': data._csrf },
            body: JSON.stringify(data)
        });
        const result = await res.json();
        if(result.success) {
            alert('Profile updated successfully!');
            window.location.reload();
        } else { alert(result.message); }
    } catch(err) { alert('Error updating profile.'); }
    btn.disabled = false; btn.innerText = 'Update Profile';
};
</script>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
