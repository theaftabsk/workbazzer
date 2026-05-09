<?php
/**
 * WorkBazar — Premium Login Page (Password Based)
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
  <title>Login — WorkBazar</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo asset('assets/css/auth/login.css'); ?>">
</head>
<body>
<div class="auth-wrap">

  <!-- LEFT -->
  <div class="auth-left">
    <div class="auth-left-bg"></div>
    <div class="auth-left-overlay"></div>
    <div class="auth-left-content">
      <a href="/" class="auth-left-logo">Work<span>Bazar</span></a>
      <div class="auth-left-body">
        <h2>Your next great hire is one search away</h2>
        <p>Connect with 5 million+ verified freelancers across AI, development, design, marketing, and more.</p>
        <div class="auth-stats">
          <div class="auth-stat"><strong>5M+</strong><span>Freelancers</span></div>
          <div class="auth-stat"><strong>800K+</strong><span>Clients</span></div>
          <div class="auth-stat"><strong>98%</strong><span>Satisfaction</span></div>
        </div>
      </div>
      <div class="auth-left-footer">Developed by ITVEXO · Enterprise Software Solutions</div>
    </div>
  </div>

  <!-- RIGHT -->
  <div class="auth-right">
    <div class="auth-form-wrap">
      <a href="/" class="auth-back"><i class="ri-arrow-left-line"></i> Back to home</a>
      <h1 class="auth-title">Welcome back 👋</h1>
      <p class="auth-sub">Log in to your account to continue</p>

      <div class="role-tabs">
        <button class="role-tab active" onclick="setRole(this,'freelancer')">🧑‍💻 Freelancer</button>
        <button class="role-tab" onclick="setRole(this,'client')">💼 Client</button>
      </div>

      <div class="form-group">
        <label class="form-label">Email Address</label>
        <input class="form-input" type="email" id="email" placeholder="you@example.com" oninput="clearErr()">
      </div>

      <div class="form-group">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:7px;">
          <label class="form-label" style="margin-bottom:0;">Password</label>
          <a href="/auth/forgot-password.php" style="font-size:.78rem;font-weight:700;color:var(--green);text-decoration:none;">Forgot Password?</a>
        </div>
        <div style="position:relative;">
          <input class="form-input" type="password" id="password" placeholder="Your account password" oninput="clearErr()">
          <i class="ri-eye-off-line" id="togglePass" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:var(--muted);" onclick="togglePassword()"></i>
        </div>
      </div>

      <button class="btn-primary" id="loginBtn" onclick="login()">
        <i class="ri-login-box-line"></i> Log In
      </button>

      <div class="error-banner" id="errorBanner">
        <i class="ri-error-warning-line"></i><span id="errorMsg"></span>
      </div>

      <div class="auth-divider"><span>New to WorkBazar?</span></div>
      <div class="auth-footer-link"><a href="/auth/register.php">Create a free account →</a></div>
      <div class="itvexo-credit">Developed by <a href="https://itvexo.com" target="_blank">ITVEXO</a></div>
    </div>
  </div>
</div>

<script>
window._role = 'freelancer';
function setRole(el,role){document.querySelectorAll('.role-tab').forEach(t=>t.classList.remove('active'));el.classList.add('active');window._role=role;}

function togglePassword() {
  const p = document.getElementById('password');
  const i = document.getElementById('togglePass');
  if (p.type === 'password') {
    p.type = 'text'; i.classList.replace('ri-eye-off-line', 'ri-eye-line');
  } else {
    p.type = 'password'; i.classList.replace('ri-eye-line', 'ri-eye-off-line');
  }
}

function showErr(msg){document.getElementById('errorMsg').textContent=msg;document.getElementById('errorBanner').classList.add('show');setTimeout(()=>document.getElementById('errorBanner').classList.remove('show'),5000);}
function clearErr(){document.getElementById('errorBanner').classList.remove('show');}
function csrf(){return document.querySelector('meta[name="csrf-token"]').content;}

async function login(){
  const email=document.getElementById('email').value.trim();
  const password=document.getElementById('password').value;
  
  if(!email || !password){showErr('Please enter both email and password.');return;}
  if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){showErr('Please enter a valid email address.');return;}

  const btn=document.getElementById('loginBtn');
  btn.disabled=true; btn.innerHTML='<i class="ri-loader-4-line"></i> Logging in…';

  try{
    const res=await fetch('/api/login.php',{
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf()},
      body:JSON.stringify({email, password, role:window._role})
    });
    const data=await res.json();
    if(data.success){
      btn.innerHTML='<i class="ri-check-line"></i> Success! Redirecting…';
      window.location.href=data.redirect;
    } else {
      showErr(data.message||'Login failed. Please check your credentials.');
      btn.disabled=false; btn.innerHTML='<i class="ri-login-box-line"></i> Log In';
    }
  } catch(e){
    showErr('Cannot reach server. Please check your connection.');
    btn.disabled=false; btn.innerHTML='<i class="ri-login-box-line"></i> Log In';
  }
}

// Allow login on Enter key
document.addEventListener('keypress', (e) => {
  if(e.key === 'Enter') {
      if(document.activeElement.tagName !== 'TEXTAREA') login();
  }
});
</script>
</body>
</html>
