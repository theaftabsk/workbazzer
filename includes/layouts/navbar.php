<nav class="wb-nav" id="wbNav">
  <a href="/" class="wb-logo">Work<span>Bazar</span></a>

  <div class="wb-links">

    <?php if(!Auth::check()): ?>
      <!-- ── PUBLIC LINKS (GUESTS ONLY) ── -->

      <!-- Hire Talent -->
      <div class="wb-link" id="linkHire" onmouseenter="openMega('hire')" onmouseleave="closeMega('hire')">
        Hire talent <i class="ri-arrow-down-s-line"></i>
        <div class="wb-mega" id="megaHire">
          <div class="wb-mega-inner">
            <div class="wb-mega-cats">
              <h4>Categories</h4>
              <div class="wb-cat-item active" onmouseenter="switchCat('ai')">AI & Automation <i class="ri-arrow-right-s-line"></i></div>
              <div class="wb-cat-item" onmouseenter="switchCat('dev')">Development & IT <i class="ri-arrow-right-s-line"></i></div>
              <div class="wb-cat-item" onmouseenter="switchCat('design')">Design & Creative <i class="ri-arrow-right-s-line"></i></div>
              <div class="wb-cat-item" onmouseenter="switchCat('marketing')">Marketing <i class="ri-arrow-right-s-line"></i></div>
              <div class="wb-cat-item" onmouseenter="switchCat('writing')">Writing & Content <i class="ri-arrow-right-s-line"></i></div>
              <div class="wb-cat-item" onmouseenter="switchCat('admin')">Admin & Support <i class="ri-arrow-right-s-line"></i></div>
            </div>

            <div class="wb-mega-skills">
              <div id="catSkills"></div>
            </div>

            <div class="wb-mega-aside">
              <h4>Featured</h4>
              <div class="wb-aside-card">
                <h5>✅ Top Rated</h5>
                <p>Work with verified high-success freelancers</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Get Outcomes -->
      <div class="wb-link" onmouseenter="this.classList.add('open')" onmouseleave="this.classList.remove('open')">
        Get outcomes <i class="ri-arrow-down-s-line"></i>
        <div class="wb-dropdown">
          <div class="wb-dd-simple">
            <a href="<?php echo url('public/talent-marketplace.php?q=website'); ?>"><i class="ri-global-line"></i> Build my website</a>
            <a href="<?php echo url('public/talent-marketplace.php?q=design'); ?>"><i class="ri-palette-line"></i> Design my brand</a>
            <a href="<?php echo url('public/talent-marketplace.php?q=marketing'); ?>"><i class="ri-megaphone-line"></i> Scale my ad campaigns</a>
            <a href="<?php echo url('public/talent-marketplace.php?q=automation'); ?>"><i class="ri-robot-line"></i> Automate my workflows</a>
            <a href="<?php echo url('public/talent-marketplace.php?q=support'); ?>"><i class="ri-customer-service-line"></i> Handle customer support</a>
            <a href="<?php echo url('public/talent-marketplace.php?q=sales'); ?>"><i class="ri-bar-chart-2-line"></i> Build my sales pipeline</a>
          </div>
        </div>
      </div>

      <!-- Find Work -->
      <div class="wb-link" onmouseenter="this.classList.add('open')" onmouseleave="this.classList.remove('open')">
        Find work <i class="ri-arrow-down-s-line"></i>
        <div class="wb-dropdown">
          <div class="wb-dd-simple">
            <a href="<?php echo url('public/find-work.php'); ?>"><i class="ri-search-line"></i> Find jobs for your skills</a>
            <a href="<?php echo url('public/find-work/ways-to-earn.php'); ?>"><i class="ri-money-dollar-circle-line"></i> Ways to earn</a>
            <a href="<?php echo url('auth/register.php'); ?>"><i class="ri-user-add-line"></i> Create a freelancer profile</a>
            <a href="<?php echo url('public/find-work/promoted-ads.php'); ?>"><i class="ri-advertisement-line"></i> Win work with promoted ads</a>
            <div class="dd-footer"><a href="<?php echo url('public/find-work.php'); ?>">Explore all opportunities <i class="ri-arrow-right-line"></i></a></div>
          </div>
        </div>
      </div>

      <!-- Why WorkBazar -->
      <div class="wb-link" onmouseenter="this.classList.add('open')" onmouseleave="this.classList.remove('open')">
        Why WorkBazar <i class="ri-arrow-down-s-line"></i>
        <div class="wb-dropdown" style="min-width:320px;">
          <div class="wb-dd-simple">
            <a href="<?php echo url('public/why-workbazar/trusted-secure.php'); ?>"><i class="ri-shield-check-line"></i> Trusted & Secure Platform</a>
            <a href="<?php echo url('public/why-workbazar/top-talent.php'); ?>"><i class="ri-award-line"></i> Top Verified Talent</a>
            <a href="<?php echo url('public/why-workbazar/support.php'); ?>"><i class="ri-customer-service-2-line"></i> 24/7 Support</a>
            <a href="<?php echo url('public/why-workbazar/success-stories.php'); ?>"><i class="ri-file-text-line"></i> Success Stories</a>
            <a href="<?php echo url('public/why-workbazar/blog.php'); ?>"><i class="ri-book-open-line"></i> Blog & Resources</a>
          </div>
        </div>
      </div>

      <a class="wb-link" href="<?php echo url('public/pricing.php'); ?>">Pricing</a>
      <a class="wb-link" href="<?php echo url('public/enterprise.php'); ?>">Enterprise</a>

    <?php elseif(Auth::role() === 'freelancer'): ?>
      <!-- ── FREELANCER LINKS ── -->
      
      <div class="wb-link" onmouseenter="this.classList.add('open')" onmouseleave="this.classList.remove('open')">
        Find Work <i class="ri-arrow-down-s-line"></i>
        <div class="wb-dropdown">
          <div class="wb-dd-simple">
            <a href="<?php echo url('public/find-work.php'); ?>">Find Work</a>
            <a href="<?php echo url('dashboard/freelancer/saved-jobs.php'); ?>">Saved Jobs</a>
            <a href="<?php echo url('dashboard/freelancer/my-proposals.php'); ?>">Proposals</a>
            <a href="<?php echo url('dashboard/freelancer/portfolio.php'); ?>">Profile</a>
          </div>
        </div>
      </div>

      <div class="wb-link" onmouseenter="this.classList.add('open')" onmouseleave="this.classList.remove('open')">
        My Jobs <i class="ri-arrow-down-s-line"></i>
        <div class="wb-dropdown">
          <div class="wb-dd-simple">
            <a href="<?php echo url('dashboard/freelancer/active-jobs.php'); ?>">Active Contracts</a>
            <a href="<?php echo url('dashboard/freelancer/all-contracts.php'); ?>">All Contracts</a>
          </div>
        </div>
      </div>

      <div class="wb-link" onmouseenter="this.classList.add('open')" onmouseleave="this.classList.remove('open')">
        Manage Finances <i class="ri-arrow-down-s-line"></i>
        <div class="wb-dropdown">
          <div class="wb-dd-simple">
            <a href="<?php echo url('dashboard/freelancer/wallet.php'); ?>">Financial Overview</a>
            <a href="<?php echo url('dashboard/freelancer/analytics.php'); ?>">Billings & Earnings</a>
          </div>
        </div>
      </div>

      <a class="wb-link" href="<?php echo url('dashboard/chat.php'); ?>">Messages</a>

    <?php elseif(Auth::role() === 'client'): ?>
      <!-- ── CLIENT LINKS ── -->
      
      <div class="wb-link" onmouseenter="this.classList.add('open')" onmouseleave="this.classList.remove('open')">
        Jobs <i class="ri-arrow-down-s-line"></i>
        <div class="wb-dropdown">
          <div class="wb-dd-simple">
            <a href="<?php echo url('dashboard/client/post-job.php'); ?>">Post a Job</a>
            <a href="<?php echo url('dashboard/client/manage-jobs.php'); ?>">All Job Posts</a>
            <a href="<?php echo url('dashboard/client/active-jobs.php'); ?>">Active Contracts</a>
          </div>
        </div>
      </div>

      <div class="wb-link" onmouseenter="this.classList.add('open')" onmouseleave="this.classList.remove('open')">
        Talent <i class="ri-arrow-down-s-line"></i>
        <div class="wb-dropdown">
          <div class="wb-dd-simple">
            <a href="<?php echo url('public/talent-marketplace.php'); ?>">Search Talent</a>
            <a href="<?php echo url('dashboard/client/saved-talent.php'); ?>">Saved Talent</a>
          </div>
        </div>
      </div>

      <div class="wb-link" onmouseenter="this.classList.add('open')" onmouseleave="this.classList.remove('open')">
        Reports <i class="ri-arrow-down-s-line"></i>
        <div class="wb-dropdown">
          <div class="wb-dd-simple">
            <a href="<?php echo url('dashboard/client/billings.php'); ?>">Billings & Invoices</a>
            <a href="<?php echo url('dashboard/client/transactions.php'); ?>">Transaction History</a>
          </div>
        </div>
      </div>

      <a class="wb-link" href="<?php echo url('dashboard/chat.php'); ?>">Messages</a>

    <?php elseif(Auth::role() === 'admin'): ?>
      <!-- ── ADMIN LINKS ── -->
      <a class="wb-link" href="<?php echo url('dashboard/admin/index.php'); ?>">Dashboard</a>
      <a class="wb-link" href="<?php echo url('dashboard/admin/users.php'); ?>">Users</a>
      <a class="wb-link" href="<?php echo url('dashboard/admin/jobs.php'); ?>">Jobs</a>
      <a class="wb-link" href="<?php echo url('dashboard/admin/settings.php'); ?>">Settings</a>
    <?php endif; ?>
  </div>

  <div class="wb-nav-right">
    <div class="wb-search">
      <i class="ri-search-line"></i>
      <?php 
        $searchPlaceholder = "Search for talent";
        $searchUrl = url('public/talent-marketplace.php');
        if(Auth::check() && Auth::role() === 'freelancer') {
            $searchPlaceholder = "Search for jobs";
            $searchUrl = url('public/find-work.php');
        }
      ?>
      <input type="text" placeholder="<?= $searchPlaceholder ?>" id="navSearch" onkeydown="if(event.key==='Enter'){window.location='<?= $searchUrl ?>?q='+this.value}">
    </div>

    <?php if(Auth::check()): 
        $u = Auth::user();
        try {
            $unreadCount = DB::row("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0", [$u['id']])['count'];
        } catch(Exception $e) { $unreadCount = 0; }
        $profile = (Auth::role() === 'freelancer') ? Auth::freelancerProfile() : null;
    ?>
      <!-- Notifications -->
      <div class="wb-nav-icon-link" onclick="window.location.href='<?php echo url('dashboard/'.Auth::role().'/notifications.php'); ?>'">
        <i class="ri-notification-3-line"></i>
        <?php if($unreadCount > 0): ?><span class="wb-noti-dot"><?=$unreadCount?></span><?php endif; ?>
      </div>

      <?php if(Auth::role() === 'freelancer'): ?>
        <a href="<?php echo url('dashboard/freelancer/wallet.php'); ?>" class="wb-nav-coins">
          <i class="ri-coin-line"></i> <span><?= $profile ? ($profile['coin_balance'] ?? 0) : 0 ?></span>
        </a>
      <?php endif; ?>

      <!-- User Dropdown -->
      <div class="wb-nav-profile" onmouseenter="this.classList.add('open')" onmouseleave="this.classList.remove('open')">
        <a href="<?php 
            if(Auth::role() === 'freelancer') echo url('dashboard/freelancer/profile-settings.php');
            elseif(Auth::role() === 'client') echo url('dashboard/client/profile-settings.php');
            else echo url('dashboard/admin/settings.php');
        ?>" class="wb-avatar">
          <?php if(!empty($u['avatar'])): ?>
            <img src="<?=$u['avatar']?>" alt="Avatar">
          <?php else: ?>
            <i class="ri-user-3-fill"></i>
          <?php endif; ?>
        </a>
        <div class="wb-dropdown wb-profile-dd">
          <div class="wb-dd-header">
            <strong><?=htmlspecialchars($u['fullname'])?></strong>
            <span><?=ucfirst($u['role'])?> Account</span>
          </div>
          <div class="wb-dd-simple">
            <a href="<?=url(ltrim(Auth::dashboardUrl($u['role']), '/'))?>"><i class="ri-dashboard-line"></i> Dashboard</a>
            <?php if(Auth::role() === 'freelancer'): ?>
              <a href="<?php echo url('dashboard/freelancer/portfolio.php'); ?>"><i class="ri-folder-user-line"></i> My Portfolio</a>
            <?php endif; ?>
            <?php if(Auth::role() === 'freelancer'): ?>
              <a href="<?php echo url('dashboard/freelancer/profile-settings.php'); ?>"><i class="ri-settings-3-line"></i> Settings</a>
            <?php elseif(Auth::role() === 'client'): ?>
              <a href="<?php echo url('dashboard/client/profile-settings.php'); ?>"><i class="ri-settings-3-line"></i> Settings</a>
            <?php else: ?>
              <a href="<?php echo url('dashboard/admin/settings.php'); ?>"><i class="ri-settings-3-line"></i> Settings</a>
            <?php endif; ?>
            <div class="dd-divider"></div>
            <a href="<?php echo url('auth/logout.php'); ?>" style="color:#ef4444;"><i class="ri-logout-box-r-line"></i> Log out</a>
          </div>
        </div>
      </div>
    <?php else: ?>
      <a href="<?php echo url('auth/login.php'); ?>" class="btn-login">Log in</a>
      <a href="<?php echo url('auth/register.php'); ?>" class="btn-signup">Sign up</a>
    <?php endif; ?>
  </div>
</nav>



<script>
// Category skills data
const catData = {
  ai: {
    title: 'AI & AUTOMATION',
    skills: ['Artificial Intelligence','AI Model Training','ChatGPT & OpenAI','Prompt Engineering','AI Content Creation','Generative AI','Machine Learning','AI Video Generation','Chatbot Development','Automation & Workflows']
  },
  dev: {
    title: 'DEVELOPMENT & IT',
    skills: ['Full-Stack Development','Web Development','WordPress','Mobile Apps','Shopify','Webflow','Front-End Development','React & Next.js','Bubble.io','Ecommerce Stores']
  },
  design: {
    title: 'DESIGN & CREATIVE',
    skills: ['Logo Design','Brand Identity','UI/UX Design','Illustration','Motion Graphics','Figma','Adobe XD','Print Design','Social Media Design','Presentation Design']
  },
  marketing: {
    title: 'MARKETING',
    skills: ['Social Media Marketing','SEO','Google Ads','Email Marketing','Content Marketing','Meta Ads','Influencer Marketing','Analytics','Copywriting','Growth Hacking']
  },
  writing: {
    title: 'WRITING & CONTENT',
    skills: ['Blog Writing','Copywriting','Technical Writing','Ghostwriting','Proofreading','Resume Writing','Product Descriptions','Press Releases','UX Writing','Scriptwriting']
  },
  admin: {
    title: 'ADMIN & SUPPORT',
    skills: ['Virtual Assistant','Data Entry','Customer Support','Project Management','Research','Scheduling','Email Management','HR Support','Legal Documents','Bookkeeping']
  }
};

function renderCat(cat) {
  const d = catData[cat];
  const box = document.getElementById('catSkills');
  if(!box || !d) return;
  box.innerHTML = `
    <h4>${d.title}</h4>
    <div class="wb-skills-grid">
      ${d.skills.map(s=>`<a href="/public/talent-marketplace.php?q=${encodeURIComponent(s)}" class="wb-skill-link">${s}</a>`).join('')}
    </div>
    <div class="dd-footer">
      <a href="/public/talent-marketplace.php">See all skills <i class="ri-arrow-right-line"></i></a>
    </div>`;
}

function switchCat(cat) {
  document.querySelectorAll('.wb-cat-item').forEach(el=>el.classList.remove('active'));
  event.currentTarget.classList.add('active');
  renderCat(cat);
}

function openMega(id) {
  document.getElementById('linkHire').classList.add('open');
  renderCat('ai');
}
function closeMega(id) {
  document.getElementById('linkHire').classList.remove('open');
}

// Navbar scroll
window.addEventListener('scroll',()=>{
  document.getElementById('wbNav').classList.toggle('scrolled', window.scrollY>20);
});

// Init first category
document.addEventListener('DOMContentLoaded',()=>renderCat('ai'));
</script>
