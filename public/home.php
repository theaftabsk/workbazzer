<?php
/**
 * WorkBazar — Home Page
 */
require_once __DIR__ . '/../includes/app.php';
App::init();
require_once __DIR__ . '/../includes/layouts/header.php';
require_once __DIR__ . '/../includes/layouts/navbar.php';
?>

<!-- ── HERO ─────────────────────────────────── -->
<section class="wb-hero">
  <div class="wb-hero-bg"></div>
  <div class="wb-hero-inner">
    <div class="wb-hero-toggle">
      <a href="#" class="active" onclick="heroToggle(event,'hire')">Hire</a>
      <a href="#" onclick="heroToggle(event,'work')">Work</a>
    </div>
    <h1 id="heroH1">Grow at the speed <br>of your <em>ambition</em></h1>
    <p id="heroP">Hire experts who use AI to amplify their skills — turning complex projects into real results.</p>
    <form class="wb-hero-search" action="<?php echo url('public/talent-marketplace.php'); ?>" method="GET">
      <input type="text" name="q" id="heroInput" placeholder="I need to build a website…">
      <button type="submit" id="heroBtn">Find talent</button>
    </form>
    <div class="wb-hero-tags">
      <span>Trending:</span>
      <a href="<?php echo url('public/talent-marketplace.php?q=AI+Automation'); ?>">AI Automation</a>
      <a href="<?php echo url('public/talent-marketplace.php?q=Chatbot'); ?>">Chatbot Dev</a>
      <a href="<?php echo url('public/talent-marketplace.php?q=Full+Stack'); ?>">Full-Stack</a>
      <a href="<?php echo url('public/talent-marketplace.php?q=Brand+Design'); ?>">Brand Design</a>
    </div>
  </div>
</section>

<!-- ── TRUSTED BY ───────────────────────────── -->
<div class="wb-trusted">
  <div class="wb-trusted-inner">
    <div class="wb-trusted-label">Developed & Powered by</div>
    <div class="wb-trusted-logos">
      <span style="font-size:1.6rem; color:var(--ink); opacity:.8;">ITVEXO</span>
      <span style="font-size:.85rem; color:var(--muted); font-weight:500; letter-spacing:.3px;">Enterprise Software & Digital Solutions</span>
    </div>
    <div style="margin-left:auto;">
      <a href="https://itvexo.com" target="_blank" style="font-size:.82rem; font-weight:700; color:var(--green); display:flex; align-items:center; gap:5px;">
        itvexo.com <i class="ri-arrow-right-up-line"></i>
      </a>
    </div>
  </div>
</div>

<!-- ── HIRE FOR WHERE WORK IS HEADED ─────────── -->
<div class="wb-section">
  <div class="wb-tag">Hire for where work is headed</div>
  <h2 class="wb-heading">Roles & skills driving growth</h2>
  <p class="wb-subheading">From AI engineering to brand design, see the key roles powering growth across every vertical.</p>

  <div class="wb-roles-grid">
    <!-- Emerging Roles -->
    <div class="wb-card-dark">
      <h3>🚀 Emerging Roles</h3>
      <div class="wb-role">
        <div class="wb-role-top">
          <span class="wb-role-name">AI Trainer</span>
          <span class="wb-role-pct"><i class="ri-arrow-right-up-line"></i> +56%</span>
        </div>
        <div class="wb-role-desc">Evaluates model outputs and improves AI performance</div>
      </div>
      <div class="wb-role">
        <div class="wb-role-top">
          <span class="wb-role-name">Chatbot Developer</span>
          <span class="wb-role-pct"><i class="ri-arrow-right-up-line"></i> +31%</span>
        </div>
        <div class="wb-role-desc">Creates conversational AI experiences for users</div>
      </div>
      <div class="wb-role">
        <div class="wb-role-top">
          <span class="wb-role-name">Prompt Engineer</span>
          <span class="wb-role-pct"><i class="ri-arrow-right-up-line"></i> +44%</span>
        </div>
        <div class="wb-role-desc">Crafts precise instructions to get optimal AI outputs</div>
      </div>
      <div class="wb-role">
        <div class="wb-role-top">
          <span class="wb-role-name">AI Video Creator</span>
          <span class="wb-role-pct"><i class="ri-arrow-right-up-line"></i> +82%</span>
        </div>
        <div class="wb-role-desc">Produces stunning video content using AI generation tools</div>
      </div>
    </div>

    <!-- In-demand Skills -->
    <div class="wb-card-light">
      <h3>⚡ In-demand Skills</h3>
      <div class="wb-skill-group">
        <h4>Development</h4>
        <div class="wb-tag-list">
          <a href="<?php echo url('public/talent-marketplace.php?q=Full+Stack'); ?>" class="wb-tag-pill">Full-Stack</a>
          <a href="<?php echo url('public/talent-marketplace.php?q=Mobile+Apps'); ?>" class="wb-tag-pill">Mobile Apps</a>
          <a href="<?php echo url('public/talent-marketplace.php?q=WordPress'); ?>" class="wb-tag-pill">WordPress</a>
          <a href="<?php echo url('public/talent-marketplace.php?q=Webflow'); ?>" class="wb-tag-pill">Webflow</a>
          <a href="<?php echo url('public/talent-marketplace.php?q=Shopify'); ?>" class="wb-tag-pill">Shopify</a>
        </div>
      </div>
      <div class="wb-skill-group">
        <h4>Design</h4>
        <div class="wb-tag-list">
          <a href="<?php echo url('public/talent-marketplace.php?q=UI+UX'); ?>" class="wb-tag-pill">UX/UI</a>
          <a href="<?php echo url('public/talent-marketplace.php?q=Logo+Design'); ?>" class="wb-tag-pill">Logo Design</a>
          <a href="<?php echo url('public/talent-marketplace.php?q=Brand+Identity'); ?>" class="wb-tag-pill">Brand Identity</a>
          <a href="<?php echo url('public/talent-marketplace.php?q=Figma'); ?>" class="wb-tag-pill">Figma</a>
        </div>
      </div>
      <div class="wb-skill-group">
        <h4>Marketing</h4>
        <div class="wb-tag-list">
          <a href="<?php echo url('public/talent-marketplace.php?q=SEO'); ?>" class="wb-tag-pill">SEO</a>
          <a href="<?php echo url('public/talent-marketplace.php?q=Google+Ads'); ?>" class="wb-tag-pill">Google Ads</a>
          <a href="<?php echo url('public/talent-marketplace.php?q=Social+Media'); ?>" class="wb-tag-pill">Social Media</a>
          <a href="<?php echo url('public/talent-marketplace.php?q=Email+Marketing'); ?>" class="wb-tag-pill">Email</a>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- ── HOW IT WORKS ─────────────────────────── -->
<div class="wb-section">
  <div class="wb-tag">How it works</div>
  <h2 class="wb-heading">Start getting results in 3 steps</h2>
  <p class="wb-subheading">Whether you're hiring or looking for work, WorkBazar makes it fast and reliable.</p>
  <div class="wb-steps">
    <div class="wb-step">
      <div class="wb-step-num">1</div>
      <h4>Post your project</h4>
      <p>Describe your needs in minutes. Our platform helps you write a clear, effective job post.</p>
    </div>
    <div class="wb-step">
      <div class="wb-step-num">2</div>
      <h4>Get matched instantly</h4>
      <p>Our smart system surfaces top-matched freelancers or you can browse and invite directly.</p>
    </div>
    <div class="wb-step">
      <div class="wb-step-num">3</div>
      <h4>Collaborate securely</h4>
      <p>Work, communicate, and track progress all in one place with full payment protection.</p>
    </div>
    <div class="wb-step">
      <div class="wb-step-num">4</div>
      <h4>Pay with confidence</h4>
      <p>Release payment only when you're 100% satisfied. No risk, ever.</p>
    </div>
  </div>
</div>

<!-- ── TESTIMONIALS ─────────────────────────── -->
<div class="wb-section" style="padding-top:0;">
  <div class="wb-tag">Client Reviews</div>
  <h2 class="wb-heading">What our clients say</h2>
  <div class="wb-testimonials">
    <div class="wb-testimonial">
      <div class="wb-testimonial-stars">★★★★★</div>
      <p>"Found a top AI developer within 24 hours. The quality of work was exceptional and the whole process was seamless from start to finish."</p>
      <div class="wb-testimonial-author">
        <div class="wb-testimonial-avatar">RM</div>
        <div class="wb-testimonial-info">
          <h5>Rahul M.</h5>
          <span>Startup Founder, Bengaluru</span>
        </div>
      </div>
    </div>
    <div class="wb-testimonial">
      <div class="wb-testimonial-stars">★★★★★</div>
      <p>"WorkBazar's matching system connected me with the perfect brand designer. Our company identity was completely transformed in under two weeks."</p>
      <div class="wb-testimonial-author">
        <div class="wb-testimonial-avatar">SA</div>
        <div class="wb-testimonial-info">
          <h5>Sara A.</h5>
          <span>Marketing Director, Dubai</span>
        </div>
      </div>
    </div>
    <div class="wb-testimonial">
      <div class="wb-testimonial-stars">★★★★★</div>
      <p>"As a freelancer, WorkBazar gave me access to global clients instantly. My income doubled in the first 3 months. Highly recommended!"</p>
      <div class="wb-testimonial-author">
        <div class="wb-testimonial-avatar">KP</div>
        <div class="wb-testimonial-info">
          <h5>Karim P.</h5>
          <span>Freelance Developer, Cairo</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ── FAQ ─────────────────────────────────── -->
<div class="wb-section" style="padding-top:0; padding-bottom:120px;">
  <div class="wb-faq-grid">
    <div class="wb-faq-left">
      <h2>Common questions, answered</h2>
      <p>Everything you need to know to get started on WorkBazar confidently.</p>
    </div>
    <div>
      <div class="wb-faq-item">
        <div class="wb-faq-q">What is WorkBazar?</div>
        <div class="wb-faq-a">WorkBazar is an enterprise freelance marketplace connecting businesses with verified expert talent across 500+ skill categories. We use intelligent algorithms to match projects with the right professionals.</div>
      </div>
      <div class="wb-faq-item">
        <div class="wb-faq-q">How does payment protection work?</div>
        <div class="wb-faq-a">All payments are held securely in escrow and only released when you approve the completed work. You're fully protected — <a href="<?php echo url('public/pricing.php'); ?>">see how it works</a>.</div>
      </div>
      <div class="wb-faq-item">
        <div class="wb-faq-q">Can I hire teams, not just individuals?</div>
        <div class="wb-faq-a">Yes! Our Enterprise plan lets you assemble full teams, manage contracts, and get dedicated account management. <a href="#">Learn about Enterprise</a>.</div>
      </div>
      <div class="wb-faq-item">
        <div class="wb-faq-q">How do I get started as a freelancer?</div>
        <div class="wb-faq-a">Simply <a href="<?php echo url('auth/register.php'); ?>">create a free profile</a>, showcase your skills, and start applying to jobs immediately. No setup fees ever.</div>
      </div>
    </div>
  </div>
</div>

<!-- ── TICKER ───────────────────────────────── -->
<div class="wb-ticker">
  <div class="wb-ticker-label"><i class="ri-circle-fill"></i> Live Trending</div>
  <div class="wb-ticker-track" id="tickerTrack"></div>
</div>

<script>
// Hero toggle
const heroData = {
  hire: { h1:'Grow at the speed <br>of your <em>ambition</em>', p:'Hire experts who use AI to amplify their skills — turning complex projects into real results.', btn:'Find talent', ph:'I need to build a website…', action:'<?php echo url('public/talent-marketplace.php'); ?>' },
  work: { h1:'Find work that <br>fuels your <em>growth</em>', p:'Join 5 million+ freelancers earning on their own terms — with projects that match your exact skills.', btn:'Find opportunities', ph:'Tell us your top skill…', action:'<?php echo url('public/find-work.php'); ?>' }
};
function heroToggle(e, mode) {
  e.preventDefault();
  document.querySelectorAll('.wb-hero-toggle a').forEach(a=>a.classList.remove('active'));
  e.currentTarget.classList.add('active');
  const d = heroData[mode];
  document.getElementById('heroH1').innerHTML = d.h1;
  document.getElementById('heroP').textContent = d.p;
  document.getElementById('heroBtn').textContent = d.btn;
  document.getElementById('heroInput').placeholder = d.ph;
  document.querySelector('.wb-hero-search').setAttribute('action', d.action);
}

// Ticker
const ticks = [
  {label:'Higgsfield', pct:'+18350%', up:true},{label:'AEO', pct:'+2684%', up:true},
  {label:'Base44', pct:'+1690%', up:true},{label:'AI UGC', pct:'+1138%', up:true},
  {label:'Claude', pct:'+438%', up:true},{label:'Open AI', pct:'-71%', up:false},
  {label:'Bland AI', pct:'+318%', up:true},{label:'AI Video', pct:'+285%', up:true},
  {label:'Midjourney', pct:'+190%', up:true},{label:'Chatbot Dev', pct:'+210%', up:true}
];
const html = ticks.map(t=>`<div class="wb-ticker-item">${t.label} <span class="pct ${t.up?'up':'dn'}">${t.pct}</span></div>`).join('');
const track = document.getElementById('tickerTrack');
if(track) track.innerHTML = html + html; // duplicate for loop
</script>

<?php require_once __DIR__ . '/../includes/layouts/footer.php'; ?>
