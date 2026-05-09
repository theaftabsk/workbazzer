<?php
/**
 * WorkBazar — Freelancer Profile Settings
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('freelancer');

$user = Auth::user();
$profile = Auth::freelancerProfile();

$pageTitle = "My Profile Settings — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/freelancer/index.css'); ?>">
<style>
    .settings-card { background: #fff; border-radius: 24px; border: 1px solid var(--border); padding: 40px; max-width: 800px; margin: 0 auto; }
    .form-group { margin-bottom: 24px; }
    .form-label { display: block; font-weight: 700; margin-bottom: 8px; color: var(--ink); }
    .form-input, .form-textarea { width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--border); background: #f8fafc; font-size: 1rem; outline: none; transition: 0.3s; }
    .form-input:focus, .form-textarea:focus { border-color: var(--green); background: #fff; box-shadow: 0 0 0 4px var(--green-glow); }
</style>

<main class="dashboard-wrap">
    <div class="page-header" style="text-align:center;">
        <h1>Profile Settings 👤</h1>
        <p>Update your professional identity and public profile details.</p>
    </div>

    <div class="settings-card">
        <form id="settingsForm">
            <?= Security::csrfField() ?>
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="fullname" class="form-input" value="<?= htmlspecialchars($user['fullname']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Professional Title</label>
                <input type="text" name="title" class="form-input" value="<?= htmlspecialchars($profile['title'] ?? '') ?>" placeholder="e.g. Senior Full-Stack Developer">
            </div>

            <div class="form-group">
                <label class="form-label">Hourly Rate (₹)</label>
                <input type="number" name="hourly_rate" class="form-input" value="<?= htmlspecialchars($profile['hourly_rate'] ?? 0) ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Professional Bio</label>
                <textarea name="bio" class="form-textarea" style="height:150px;"><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
            </div>

            <div style="display:flex; gap:12px;">
                <button type="submit" id="saveBtn" class="btn-primary" style="flex:2;">Save Changes</button>
                <a href="/auth/logout.php" class="btn-primary" style="flex:1; background:#ef4444; display:flex; align-items:center; justify-content:center;">Log out</a>
            </div>
        </form>
    </div>
</main>

<script>
document.getElementById('settingsForm').onsubmit = async (e) => {
    e.preventDefault();
    const btn = document.getElementById('saveBtn');
    btn.disabled = true; btn.innerText = 'Saving...';
    
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
            alert('Profile updated!');
            window.location.reload();
        } else { alert(result.message); }
    } catch(err) { alert('Error updating profile.'); }
    btn.disabled = false; btn.innerText = 'Save Changes';
};
</script>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
