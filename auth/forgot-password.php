<?php
/**
 * WorkBazar — Forgot Password Page
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

if (Auth::check()) {
    header("Location: " . Auth::dashboardUrl(Auth::role()));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo Security::csrfToken(); ?>">
  <title>Forgot Password — WorkBazar</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo asset('assets/css/auth/reset.css'); ?>">
</head>
<body>

<div class="auth-card">
  <div id="formContent">
    <a href="/auth/login.php" class="auth-back"><i class="ri-arrow-left-line"></i> Back to login</a>
    <h1 class="auth-title">Reset Password 🔐</h1>
    <p class="auth-sub">Enter your email address and we'll send you a link to reset your password.</p>

    <div class="form-group">
      <label class="form-label">Email Address</label>
      <input class="form-input" type="email" id="email" placeholder="you@example.com">
    </div>

    <button class="btn-primary" id="resetBtn" onclick="requestReset()">
      <i class="ri-mail-send-line"></i> Send Reset Link
    </button>

    <div class="error-banner" id="errorBanner">
      <i class="ri-error-warning-line"></i><span id="errorMsg"></span>
    </div>
  </div>

  <div class="success-banner" id="successBanner">
    <i class="ri-checkbox-circle-fill"></i>
    <h3>Email Sent!</h3>
    <p>If an account exists for <strong id="sentEmail"></strong>, you will receive a password reset link shortly.</p>
    <a href="/auth/login.php" class="btn-primary" style="margin-top:24px;text-decoration:none;">Return to Login</a>
  </div>
</div>

<script>
function showErr(msg){document.getElementById('errorMsg').textContent=msg;document.getElementById('errorBanner').classList.add('show');setTimeout(()=>document.getElementById('errorBanner').classList.remove('show'),5000);}
function csrf(){return document.querySelector('meta[name="csrf-token"]').content;}

async function requestReset(){
  const email=document.getElementById('email').value.trim();
  if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){showErr('Please enter a valid email address.');return;}

  const btn=document.getElementById('resetBtn');
  btn.disabled=true; btn.innerHTML='<i class="ri-loader-4-line"></i> Sending…';

  try{
    const res=await fetch('/api/forgot_password.php',{
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf()},
      body:JSON.stringify({email})
    });
    const data=await res.json();
    if(data.success){
      document.getElementById('formContent').style.display='none';
      document.getElementById('sentEmail').textContent=email;
      document.getElementById('successBanner').classList.add('show');
    } else {
      showErr(data.message||'Failed to send reset link.');
      btn.disabled=false; btn.innerHTML='<i class="ri-mail-send-line"></i> Send Reset Link';
    }
  } catch(e){
    showErr('Cannot reach server. Please check your connection.');
    btn.disabled=false; btn.innerHTML='<i class="ri-mail-send-line"></i> Send Reset Link';
  }
}
</script>

</body>
</html>
