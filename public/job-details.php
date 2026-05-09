<?php
/**
 * WorkBazar — Project Details & Bidding
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

$jobId = (int)($_GET['id'] ?? 0);
$job   = DB::row("SELECT j.*, u.fullname as client_name, u.avatar as client_avatar, u.created_at as client_joined,
                  cp.total_jobs, cp.total_spent,
                  (SELECT COUNT(*) FROM proposals WHERE job_id = j.id) as proposal_count
                  FROM jobs j 
                  JOIN users u ON j.client_id = u.id 
                  LEFT JOIN client_profiles cp ON u.id = cp.user_id
                  WHERE j.id = ?", [$jobId]);

if (!$job) {
    redirect('find-work.php');
}

// Check if user already bid
$user = Auth::user();
$hasBid = false;
if ($user) {
    $hasBid = DB::row("SELECT id FROM proposals WHERE job_id = ? AND freelancer_id = ?", [$jobId, $user['id']]);
}

$proposalCost = App::setting('proposal_cost_coins', 2);

$pageTitle = $job['title'] . " — WorkBazar";
include __DIR__ . '/../includes/layouts/header.php';
include __DIR__ . '/../includes/layouts/navbar.php';

$isFreelancer = (Auth::role() === 'freelancer');
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/marketplace.css'); ?>">

<main class="job-details-wrap" style="max-width:1400px; margin:40px auto; padding:0 20px; display:grid; grid-template-columns: 1fr 400px; gap:40px;">
  
  <!-- Left: Main Content -->
  <div class="main-content">
    <div class="job-header-card" style="background:#fff; border-radius:24px; border:1px solid var(--border); padding:40px; margin-bottom:30px;">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px;">
        <span class="category-tag" style="background:#f0fdf4; color:var(--green); padding:8px 16px; border-radius:100px; font-size:0.8rem; font-weight:800; text-transform:uppercase;"><?=htmlspecialchars($job['category'])?></span>
        <div style="text-align:right;">
          <div style="font-size:1.8rem; font-weight:800; color:var(--green);">₹<?=number_format($job['budget_min'])?> - ₹<?=number_format($job['budget_max'])?></div>
          <small style="color:var(--muted); font-weight:700;"><?=ucfirst($job['budget_type'])?> Budget</small>
        </div>
      </div>

      <h1 style="font-size:2.2rem; font-weight:800; margin-bottom:20px; color:var(--ink);"><?=htmlspecialchars($job['title'])?></h1>
      
      <div class="job-meta-row" style="display:flex; gap:30px; color:var(--muted); font-weight:600; font-size:0.9rem; border-bottom:1px solid #f4f7f4; padding-bottom:20px;">
        <span><i class="ri-time-line"></i> Posted <?=time_ago($job['created_at'])?></span>
        <span><i class="ri-map-pin-user-line"></i> <?=ucfirst(str_replace('_', ' ', $job['work_type']))?></span>
        <span><i class="ri-group-line"></i> <?=$job['proposal_count']?> Proposals</span>
      </div>

      <div class="job-description" style="margin-top:30px; line-height:1.8; color:#4b5563; font-size:1.05rem;">
        <?=nl2br(htmlspecialchars($job['description']))?>
      </div>
    </div>

    <!-- Bidding Section -->
    <div class="proposal-box" style="background:#fff; border-radius:24px; border:1px solid var(--border); padding:40px;">
      <?php if (!$user): ?>
        <div style="text-align:center; padding:20px; background:#f9faf9; border-radius:16px;">
          Please <a href="/auth/login.php" style="color:var(--green); font-weight:800;">Log In</a> or <a href="/auth/register.php" style="color:var(--green); font-weight:800;">Register</a> as a Freelancer to bid.
        </div>
      <?php elseif ($hasBid): ?>
        <div style="text-align:center; padding:30px; border:2px dashed var(--green); border-radius:20px; background:#f0fdf4;">
          <i class="ri-checkbox-circle-fill" style="font-size:3rem; color:var(--green);"></i>
          <h3 style="margin-top:15px;">You have already submitted a proposal!</h3>
          <p>The client will contact you if they are interested.</p>
        </div>
      <?php elseif ($isFreelancer): ?>
        <h2 style="margin-bottom:30px; font-weight:800;">Submit a Proposal</h2>
        <form id="bidForm">
          <input type="hidden" name="job_id" value="<?=$job['id']?>">
          <div style="display:grid; grid-template-columns: 1fr 1fr; gap:25px; margin-bottom:25px;">
            <div class="form-group">
              <label class="form-label" style="font-weight:700; margin-bottom:10px; display:block;">Your Bid Amount (₹)</label>
              <input type="number" name="bid_amount" class="form-input" placeholder="e.g. 5000" required style="padding:15px;">
            </div>
            <div class="form-group">
              <label class="form-label" style="font-weight:700; margin-bottom:10px; display:block;">Estimated Days</label>
              <input type="number" name="delivery_days" class="form-input" placeholder="e.g. 7" required style="padding:15px;">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label" style="font-weight:700; margin-bottom:10px; display:block;">Cover Letter</label>
            <textarea name="cover_letter" class="form-textarea" placeholder="Explain why you are the best fit for this project..." required style="min-height:200px; padding:20px;"></textarea>
          </div>
          <div style="margin-top:30px; display:flex; justify-content:space-between; align-items:center;">
             <p style="color:var(--muted); font-size:0.9rem; font-weight:600;">Submitting this proposal costs <strong><?=$proposalCost?> Coins</strong></p>
             <button type="submit" class="btn-primary" id="bidBtn" style="padding:15px 40px; font-size:1rem;">Submit Proposal</button>
          </div>
        </form>
      <?php else: ?>
        <div style="text-align:center; padding:20px; background:#fff7ed; border-radius:16px; color:#9a3412;">
          You are logged in as a <strong>Client</strong>. Only freelancers can submit proposals.
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Right: Client Sidebar -->
  <aside>
    <div class="client-card" style="background:#fff; border-radius:24px; border:1px solid var(--border); padding:32px; position:sticky; top:20px;">
      <div style="display:flex; align-items:center; gap:16px; margin-bottom:24px;">
        <div class="client-avatar" style="width:64px; height:64px; border-radius:50%; overflow:hidden; background:#f0f2f0; display:flex; align-items:center; justify-content:center;">
          <?php if($job['client_avatar']): ?>
            <img src="<?=$job['client_avatar']?>" style="width:100%; height:100%; object-fit:cover;">
          <?php else: ?>
            <i class="ri-user-3-line" style="font-size:2rem; color:var(--muted);"></i>
          <?php endif; ?>
        </div>
        <div>
          <h3 style="font-size:1.1rem; font-weight:800; margin:0;"><?=htmlspecialchars($job['client_name'])?></h3>
          <p style="margin:0; font-size:0.8rem; color:var(--muted);">Member since <?=date('M Y', strtotime($job['client_joined']))?></p>
        </div>
      </div>

      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px; margin-bottom:30px;">
        <div style="background:#f9faf9; padding:15px; border-radius:16px; text-align:center;">
          <small style="display:block; color:var(--muted); margin-bottom:5px;">Jobs Posted</small>
          <strong style="font-size:1.2rem;"><?= (int)($job['total_jobs'] ?? 0) ?></strong>
        </div>
        <div style="background:#f9faf9; padding:15px; border-radius:16px; text-align:center;">
          <small style="display:block; color:var(--muted); margin-bottom:5px;">Hire Rate</small>
          <strong style="font-size:1.2rem;">95%</strong>
        </div>
      </div>

      <div style="border-top:1px solid #f4f7f4; padding-top:20px;">
        <h4 style="font-size:0.9rem; margin-bottom:15px;">Company Info</h4>
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px; font-weight:600; font-size:0.9rem;">
          <i class="ri-shield-check-line" style="color:var(--green);"></i> Payment Verified
        </div>
        <div style="display:flex; align-items:center; gap:10px; font-weight:600; font-size:0.9rem;">
          <i class="ri-global-line" style="color:var(--green);"></i> Located in India
        </div>
      </div>

      <button class="btn-outline" style="width:100%; margin-top:30px; border-radius:12px;">
        <i class="ri-flag-line"></i> Report this job
      </button>
    </div>
  </aside>
</main>

<script>
document.getElementById('bidForm').onsubmit = async (e) => {
  e.preventDefault();
  const btn = document.getElementById('bidBtn');
  btn.disabled = true;
  btn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Submitting...';

  const formData = new FormData(e.target);
  const payload = Object.fromEntries(formData.entries());

  try {
    const res = await fetch('/api/submit_proposal.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo Security::csrfToken(); ?>' },
      body: JSON.stringify(payload)
    });
    const result = await res.json();
    if (result.success) {
      alert('Your proposal has been submitted successfully!');
      window.location.reload();
    } else {
      alert(result.message);
      btn.disabled = false;
      btn.innerHTML = 'Submit Proposal';
    }
  } catch (err) {
    alert('Failed to connect to server.');
    btn.disabled = false;
    btn.innerHTML = 'Submit Proposal';
  }
};
</script>

<?php include __DIR__ . '/../includes/layouts/footer.php'; ?>
