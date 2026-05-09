<?php
/**
 * WorkBazar — Secure Admin Login Portal
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

if (Auth::check() && Auth::role() === 'admin') {
    header("Location: /dashboard/admin/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo Security::csrfToken(); ?>">
  <title>Admin Portal — WorkBazar</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo asset('assets/css/auth/login.css'); ?>">
  <style>
    body { background: #0d1117; }
    .auth-right { background: #0d1117; color: #fff; }
    .auth-title { color: #fff; }
    .auth-sub { color: #8b949e; }
    .form-label { color: #8b949e; }
    .form-input { background: #161b22; border-color: #30363d; color: #fff; }
    .form-input:focus { background: #0d1117; border-color: var(--green); }
    .auth-card-admin { 
        border: 1px solid #30363d;
        background: #161b22;
        padding: 40px;
        border-radius: 24px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }
    .admin-badge {
        display: inline-block;
        padding: 4px 12px;
        background: rgba(29, 191, 115, 0.1);
        color: var(--green);
        border-radius: 100px;
        font-size: 0.75rem;
        font-weight: 800;
        margin-bottom: 16px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
  </style>
</head>
<body>

<div class="auth-wrap" style="justify-content: center; align-items: center;">
  <div class="auth-form-wrap auth-card-admin">
    <div style="text-align: center;">
        <div class="admin-badge">Secure Admin Access</div>
        <h1 class="auth-title">Admin Login</h1>
        <p class="auth-sub">Enter credentials to access management console</p>
    </div>

    <div class="form-group" style="margin-top: 30px;">
      <label class="form-label">Admin Email</label>
      <input class="form-input" type="email" id="email" placeholder="admin@workbazar.com">
    </div>

    <div class="form-group">
      <label class="form-label">Password</label>
      <input class="form-input" type="password" id="password" placeholder="••••••••">
    </div>

    <button class="btn-primary" id="loginBtn" onclick="login()">
      <i class="ri-shield-keyhole-line"></i> Secure Login
    </button>

    <div class="error-banner" id="errorBanner" style="background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2);">
      <i class="ri-error-warning-line"></i><span id="errorMsg"></span>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="/auth/login.php" style="color: #8b949e; font-size: 0.9rem; text-decoration: none;">
            <i class="ri-arrow-left-line"></i> Back to User Login
        </a>
    </div>
  </div>
</div>

<script>
function showErr(msg){document.getElementById('errorMsg').textContent=msg;document.getElementById('errorBanner').classList.add('show');}
function csrf(){return document.querySelector('meta[name="csrf-token"]').content;}

async function login(){
  const email=document.getElementById('email').value.trim();
  const password=document.getElementById('password').value;
  if(!email || !password){showErr('Required fields missing.');return;}

  const btn=document.getElementById('loginBtn');
  btn.disabled=true; btn.innerHTML='Authenticating…';

  try{
    const res=await fetch('/api/login.php',{
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf()},
      body:JSON.stringify({email, password, role:'admin'})
    });
    const data=await res.json();
    if(data.success){
      window.location.href=data.redirect;
    } else {
      showErr(data.message||'Access Denied.');
      btn.disabled=false; btn.innerHTML='<i class="ri-shield-keyhole-line"></i> Secure Login';
    }
  } catch(e){
    showErr('Server error.');
    btn.disabled=false; btn.innerHTML='Secure Login';
  }
}
</script>

</body>
</html>
