<?php
/**
 * WorkBazar — Reset Password Page
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

$token = $_GET['token'] ?? '';
if (empty($token)) {
    header("Location: /auth/login.php");
    exit;
}

// Optional: Pre-verify token on page load
$resetRow = DB::row("SELECT email FROM password_reset_tokens WHERE token = ? AND used = 0 AND expires_at > NOW()", [$token]);
$isValid = (bool)$resetRow;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo Security::csrfToken(); ?>">
  <title>Set New Password — WorkBazar</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo asset('assets/css/auth/reset.css'); ?>">
</head>
<body>

<div class="auth-card">
  <?php if (!$isValid): ?>
    <div class="invalid-state">
      <i class="ri-error-warning-fill"></i>
      <h2 class="auth-title">Invalid Link</h2>
      <p class="auth-sub">This password reset link is invalid, has expired, or has already been used.</p>
      <a href="/auth/forgot-password.php" class="btn-primary" style="text-decoration:none;">Request a New Link</a>
    </div>
  <?php else: ?>
    <div id="resetForm">
      <h1 class="auth-title">New Password 🛡️</h1>
      <p class="auth-sub">Set a secure password for your account (<strong><?php echo htmlspecialchars($resetRow['email']); ?></strong>)</p>

      <div class="error-banner" id="errorBanner">
        <i class="ri-error-warning-line"></i><span id="errorMsg"></span>
      </div>

      <div class="form-group">
        <label class="form-label">New Password</label>
        <div style="position:relative;">
          <input class="form-input" type="password" id="password" placeholder="Min 8 characters">
          <i class="ri-eye-off-line" id="t1" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:var(--muted);" onclick="toggle('password','t1')"></i>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Confirm New Password</label>
        <div style="position:relative;">
          <input class="form-input" type="password" id="confirmPassword" placeholder="Repeat password">
          <i class="ri-eye-off-line" id="t2" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:var(--muted);" onclick="toggle('confirmPassword','t2')"></i>
        </div>
      </div>

      <button class="btn-primary" id="saveBtn" onclick="savePassword()">
        <i class="ri-shield-keyhole-line"></i> Update Password
      </button>
    </div>
  <?php endif; ?>
</div>

<script>
const token = '<?php echo $token; ?>';
function showErr(msg){document.getElementById('errorMsg').textContent=msg;document.getElementById('errorBanner').classList.add('show');}
function csrf(){return document.querySelector('meta[name="csrf-token"]').content;}

function toggle(id,tid) {
  const p = document.getElementById(id);
  const i = document.getElementById(tid);
  if (p.type === 'password') {
    p.type = 'text'; i.classList.replace('ri-eye-off-line', 'ri-eye-line');
  } else {
    p.type = 'password'; i.classList.replace('ri-eye-line', 'ri-eye-off-line');
  }
}

async function savePassword(){
  const password = document.getElementById('password').value;
  const confirm_password = document.getElementById('confirmPassword').value;

  if(password.length < 8){showErr('Password must be at least 8 characters.');return;}
  if(password !== confirm_password){showErr('Passwords do not match.');return;}

  const btn = document.getElementById('saveBtn');
  btn.disabled = true; btn.innerHTML = '<i class="ri-loader-4-line"></i> Saving…';

  try {
    const res = await fetch('/api/reset_password.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf() },
      body: JSON.stringify({ token, password, confirm_password })
    });
    const data = await res.json();
    if(data.success){
      alert('Password updated successfully! You can now log in.');
      window.location.href = '/auth/login.php';
    } else {
      showErr(data.message || 'Failed to update password.');
      btn.disabled = false; btn.innerHTML = '<i class="ri-shield-keyhole-line"></i> Update Password';
    }
  } catch(e) {
    showErr('Network error. Please try again.');
    btn.disabled = false; btn.innerHTML = '<i class="ri-shield-keyhole-line"></i> Update Password';
  }
}
</script>

</body>
</html>
