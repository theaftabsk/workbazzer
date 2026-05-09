<?php
/**
 * WorkBazar — Premium Register Page (Email OTP Only)
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

if (Auth::check()) {
    header("Location: " . Auth::dashboardUrl(Auth::role()));
    exit;
}

// Dynamic signup bonus from admin settings
$bonusEnabled = App::setting('signup_bonus_enabled', '1') === '1';
$bonusCoins   = (int) App::setting('signup_coins', 20);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?php echo Security::csrfToken(); ?>">
  <title>Create Account — WorkBazar</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo asset('assets/css/auth/register.css'); ?>">
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
        <h2>Start earning or hiring today — it's free</h2>
        <p>Connect with a global network of verified experts and growing businesses.</p>
        <div class="auth-perks">
          <div class="auth-perk"><div class="auth-perk-icon"><i class="ri-gift-line"></i></div><div class="auth-perk-text"><strong>20 Free Coins on Signup</strong><span>Start bidding on projects instantly</span></div></div>
          <div class="auth-perk"><div class="auth-perk-icon"><i class="ri-shield-check-line"></i></div><div class="auth-perk-text"><strong>100% Payment Protection</strong><span>Get paid securely for every project</span></div></div>
          <div class="auth-perk"><div class="auth-perk-icon"><i class="ri-robot-line"></i></div><div class="auth-perk-text"><strong>Smart Job Matching</strong><span>Our system finds the best opportunities</span></div></div>
          <div class="auth-perk"><div class="auth-perk-icon"><i class="ri-global-line"></i></div><div class="auth-perk-text"><strong>Work With Global Clients</strong><span>Access 800,000+ businesses worldwide</span></div></div>
        </div>
      </div>
      <div class="auth-left-footer">Developed by ITVEXO · Enterprise Software Solutions</div>
    </div>
  </div>

  <!-- RIGHT -->
  <div class="auth-right">
    <div class="auth-form-wrap">
      <a href="/" class="auth-back"><i class="ri-arrow-left-line"></i> Back to home</a>
      <h1 class="auth-title">Create Account 🚀</h1>
      <p class="auth-sub">Join 5M+ freelancers and 800K+ clients on WorkBazar</p>

      <div class="role-tabs">
        <button class="role-tab active" onclick="setRole(this,'freelancer')">🧑‍💻 Freelancer</button>
        <button class="role-tab" onclick="setRole(this,'client')">💼 Client / Business</button>
      </div>

      <?php if ($bonusEnabled): ?>
      <div class="bonus-banner show" id="bonusBanner">
        <i class="ri-gift-2-fill" style="font-size:1.1rem;color:#059669;"></i>
        You'll get <strong style="margin:0 4px;"><?= $bonusCoins ?> free coins</strong> on signup!
      </div>
      <?php endif; ?>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">First Name</label>
          <input class="form-input" type="text" id="firstName" placeholder="Rahul">
        </div>
        <div class="form-group">
          <label class="form-label">Last Name</label>
          <input class="form-input" type="text" id="lastName" placeholder="Sharma">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Email Address</label>
        <input class="form-input" type="email" id="email" placeholder="you@example.com" oninput="clearErr()">
      </div>

      <div class="form-group">
        <label class="form-label">Create Password</label>
        <div style="position:relative;">
          <input class="form-input" type="password" id="password" placeholder="At least 8 characters" oninput="clearErr()">
          <i class="ri-eye-off-line" id="togglePass" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:var(--muted);" onclick="togglePassword()"></i>
        </div>
      </div>

      <div class="form-group" id="skillGroup">
        <label class="form-label">Primary Skill</label>
        <select class="form-select" id="skill">
          <option value="">Select your primary skill</option>
          <option>Flutter App Development</option>
          <option>React / Next.js Development</option>
          <option>Laravel / PHP Backend</option>
          <option>WordPress / Shopify</option>
          <option>UI/UX Design (Figma)</option>
          <option>Python / AI / Data Science</option>
          <option>Digital Marketing / Meta Ads</option>
          <option>SEO & Content Marketing</option>
          <option>Video Editing</option>
          <option>Graphic Design / Logo</option>
          <option>Chatbot / AI Automation</option>
          <option>Prompt Engineering</option>
        </select>
      </div>

      <div class="form-group" id="companyGroup" style="display:none;">
        <label class="form-label">Company Name <span style="color:var(--muted);font-weight:400;">(optional)</span></label>
        <input class="form-input" type="text" id="company" placeholder="Your company or project name">
      </div>

      <button class="btn-primary" id="sendOtpBtn" onclick="sendOtp()">
        <i class="ri-mail-send-line"></i> Send OTP to Email
      </button>

      <!-- OTP Section -->
      <div class="otp-section" id="otpSection">
        <div class="otp-sent-banner show" id="otpBanner" style="margin-top:16px;">
          <i class="ri-checkbox-circle-fill" style="font-size:1.1rem;color:#059669;"></i>
          OTP sent to <strong id="emailMask" style="margin-left:4px;"></strong>
        </div>
        <div class="form-group" style="margin-top:4px;">
          <label class="form-label" style="text-align:center;display:block;">Enter 6-digit OTP from your email</label>
          <div class="otp-inputs">
            <input class="otp-digit" type="text" maxlength="1" id="r1" oninput="otpMove(this,'r2')">
            <input class="otp-digit" type="text" maxlength="1" id="r2" oninput="otpMove(this,'r3')" onkeydown="otpBack(event,this,'r1')">
            <input class="otp-digit" type="text" maxlength="1" id="r3" oninput="otpMove(this,'r4')" onkeydown="otpBack(event,this,'r2')">
            <input class="otp-digit" type="text" maxlength="1" id="r4" oninput="otpMove(this,'r5')" onkeydown="otpBack(event,this,'r3')">
            <input class="otp-digit" type="text" maxlength="1" id="r5" oninput="otpMove(this,'r6')" onkeydown="otpBack(event,this,'r4')">
            <input class="otp-digit" type="text" maxlength="1" id="r6" oninput="otpFinish()" onkeydown="otpBack(event,this,'r5')">
          </div>
        </div>
        <button class="btn-primary" id="verifyBtn" onclick="verifyOtp()">
          <i class="ri-user-add-line"></i> Create My Account
        </button>
        <div class="resend-row">
          <span id="resendTimer">Resend in <b id="countdown">60</b>s</span>
          <button class="resend-btn" id="resendBtn" disabled onclick="sendOtp()">Resend OTP</button>
        </div>
      </div>

      <div class="error-banner" id="errorBanner">
        <i class="ri-error-warning-line"></i><span id="errorMsg"></span>
      </div>

      <div class="auth-terms">
        By creating an account you agree to our <a href="/public/terms.php">Terms of Service</a> and <a href="/public/privacy.php">Privacy Policy</a>.
      </div>
      <div class="divider"><span>Already have an account?</span></div>
      <div class="auth-footer-link"><a href="/auth/login.php">Log in to your account →</a></div>
      <div class="itvexo-credit">Developed by <a href="https://itvexo.com" target="_blank">ITVEXO</a></div>
    </div>
  </div>
</div>

<script>
let currentRole = 'freelancer';
function setRole(el, role) {
  document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
  el.classList.add('active'); currentRole = role;
  document.getElementById('skillGroup').style.display   = role==='freelancer' ? 'block' : 'none';
  document.getElementById('companyGroup').style.display = role==='client'     ? 'block' : 'none';
  document.getElementById('bonusBanner').classList.toggle('show', role==='freelancer');
}
function otpMove(cur,nextId){if(cur.value.length===1){const n=document.getElementById(nextId);if(n)n.focus();}}
function otpBack(e,cur,prevId){if(e.key==='Backspace'&&!cur.value){const p=document.getElementById(prevId);if(p){p.value='';p.focus();}}}
function otpFinish(){if(getOtp().length===6)verifyOtp();}
function getOtp(){return['r1','r2','r3','r4','r5','r6'].map(id=>document.getElementById(id).value).join('');}
function showErr(msg){document.getElementById('errorMsg').textContent=msg;document.getElementById('errorBanner').classList.add('show');setTimeout(()=>document.getElementById('errorBanner').classList.remove('show'),5000);}
function clearErr(){document.getElementById('errorBanner').classList.remove('show');}
function csrf(){return document.querySelector('meta[name="csrf-token"]').content;}

function togglePassword() {
  const p = document.getElementById('password');
  const i = document.getElementById('togglePass');
  if (p.type === 'password') {
    p.type = 'text'; i.classList.replace('ri-eye-off-line', 'ri-eye-line');
  } else {
    p.type = 'password'; i.classList.replace('ri-eye-line', 'ri-eye-off-line');
  }
}

let cdInterval;
function startCountdown(sec=60){
  document.getElementById('countdown').textContent=sec;
  document.getElementById('resendTimer').style.display='inline';
  document.getElementById('resendBtn').disabled=true;
  clearInterval(cdInterval);
  cdInterval=setInterval(()=>{sec--;document.getElementById('countdown').textContent=sec;if(sec<=0){clearInterval(cdInterval);document.getElementById('resendTimer').style.display='none';document.getElementById('resendBtn').disabled=false;}},1000);
}

async function sendOtp(){
  const email = document.getElementById('email').value.trim();
  const first = document.getElementById('firstName').value.trim();
  const pass  = document.getElementById('password').value;

  if(!first){showErr('Please enter your first name.');return;}
  if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){showErr('Please enter a valid email address.');return;}
  if(pass.length < 8){showErr('Password must be at least 8 characters long.');return;}

  const btn = document.getElementById('sendOtpBtn');
  btn.disabled=true; btn.innerHTML='<i class="ri-loader-4-line"></i> Sending…';

  try {
    const res = await fetch('/api/send_otp.php',{
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf()},
      body:JSON.stringify({email, context: 'register'})
    });
    const data = await res.json();
    if(data.success){
      const masked = email.replace(/(.{2})(.*)(@.*)/, '$1***$3');
      document.getElementById('emailMask').textContent = masked;
      document.getElementById('otpSection').classList.add('show');
      document.getElementById('r1').focus();
      startCountdown(60);
      btn.innerHTML='<i class="ri-refresh-line"></i> Resend OTP';
      btn.disabled=false;
      if(data.dev_otp) console.log('DEV OTP:',data.dev_otp);
    } else {
      showErr(data.message||'Failed to send OTP. Please try again.');
      btn.disabled=false; btn.innerHTML='<i class="ri-mail-send-line"></i> Send OTP to Email';
    }
  } catch(e){
    showErr('Cannot connect to server. Please check your internet connection.');
    btn.disabled=false; btn.innerHTML='<i class="ri-mail-send-line"></i> Send OTP to Email';
  }
}

async function verifyOtp(){
  const otp   = getOtp();
  const email = document.getElementById('email').value.trim();
  const name  = (document.getElementById('firstName').value.trim()+' '+document.getElementById('lastName').value.trim()).trim()||'User';
  if(otp.length!==6){showErr('Please enter the complete 6-digit OTP.');return;}

  const vBtn = document.getElementById('verifyBtn');
  vBtn.disabled=true; vBtn.innerHTML='<i class="ri-loader-4-line"></i> Creating account…';

  try {
    const res = await fetch('/api/verify_otp.php',{
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf()},
      body:JSON.stringify({email, otp, name, role:currentRole, contact:email, password: document.getElementById('password').value})
    });
    const data = await res.json();
    if(data.success){
      vBtn.innerHTML='<i class="ri-check-line"></i> Account created! Redirecting…';
      window.location.href=data.redirect;
    } else {
      showErr(data.message||'Invalid OTP. Please try again.');
      vBtn.disabled=false; vBtn.innerHTML='<i class="ri-user-add-line"></i> Create My Account';
    }
  } catch(e){
    showErr('Something went wrong. Please try again.');
    vBtn.disabled=false; vBtn.innerHTML='<i class="ri-user-add-line"></i> Create My Account';
  }
}
</script>
</body>
</html>
