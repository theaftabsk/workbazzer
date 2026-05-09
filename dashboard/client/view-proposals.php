<?php
/**
 * WorkBazar — View Proposals for a Job
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('client');

$jobId = (int)($_GET['job_id'] ?? 0);
$user  = Auth::user();

// Verify job belongs to this client
$job = DB::row("SELECT * FROM jobs WHERE id = ? AND client_id = ?", [$jobId, $user['id']]);
if (!$job) {
    redirect('/dashboard/client/index.php');
}

// Fetch Proposals (With Freelancer Contact for accepted ones)
$proposals = DB::all("SELECT p.*, u.fullname as freelancer_name, u.avatar as freelancer_avatar, u.title as freelancer_title, 
                             u.email as freelancer_email, u.phone as freelancer_phone,
                             f.rating as freelancer_rating, f.total_reviews 
                      FROM proposals p 
                      JOIN users u ON p.freelancer_id = u.id 
                      JOIN freelancer_profiles f ON p.freelancer_id = f.user_id 
                      WHERE p.job_id = ? 
                      ORDER BY p.created_at DESC", [$jobId]);

$pageTitle = "Proposals for: " . $job['title'] . " — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>



<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/client/view-proposals.css'); ?>">

<main class="proposals-container">
  <a href="/dashboard/client/index.php" class="back-btn"><i class="ri-arrow-left-line"></i> Back to Dashboard</a>

  <div class="job-summary">
    <span>Manage Proposals</span>
    <h1><?=htmlspecialchars($job['title'])?></h1>
    <p>Total <?=count($proposals)?> freelancers submitted proposals for this project.</p>
  </div>

  <?php if (empty($proposals)): ?>
    <div class="empty-proposals">
      <i class="ri-user-voice-line"></i>
      <h3>No proposals received yet.</h3>
    </div>
  <?php else: foreach($proposals as $p): ?>
    <div class="proposal-card">
      <!-- Avatar -->
      <div class="freelancer-avatar">
        <?php if($p['freelancer_avatar']): ?>
          <img src="<?=$p['freelancer_avatar']?>">
        <?php else: ?>
          <i class="ri-user-3-line"></i>
        <?php endif; ?>
      </div>

      <!-- Info -->
      <div class="freelancer-info">
        <h3><?=htmlspecialchars($p['freelancer_name'])?></h3>
        <div class="freelancer-title"><?=htmlspecialchars($p['freelancer_title'] ?? 'Verified Freelancer')?></div>
        
        <div class="rating-badge">
          <i class="ri-star-fill"></i> <?=$p['freelancer_rating']?> (<?=$p['total_reviews']?> reviews)
        </div>

        <div class="cover-letter">
          <?=nl2br(htmlspecialchars($p['cover_letter']))?>
        </div>
      </div>

      <!-- Bid Side -->
      <div class="bid-details">
        <div>
          <div class="bid-amount">₹<?=number_format($p['bid_amount'])?></div>
          <div class="bid-days">Deliver in <?=$p['delivery_days']?> Days</div>
        </div>

        <div>
          <?php if($p['status'] === 'accepted'): ?>
            <div class="contact-reveal">
              <b>🎉 Hired & Verified</b>
              <div class="contact-row"><i class="ri-mail-line"></i> <?=$p['freelancer_email']?></div>
              <div class="contact-row"><i class="ri-phone-line"></i> <?=$p['freelancer_phone'] ?? 'No phone'?></div>
            </div>
          <?php else: ?>
            <button class="btn-hire" onclick="hireFreelancer(<?=$p['id']?>)">Hire Now</button>
            <a href="/dashboard/chat.php?proposal_id=<?=$p['id']?>" class="btn-chat"><i class="ri-chat-1-line"></i> Chat</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endforeach; endif; ?>
</main>

<script>
async function hireFreelancer(proposalId) {
  if (!confirm('Are you sure you want to hire this freelancer for this project?')) return;
  
  try {
    const res = await fetch('/api/accept_proposal.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo Security::csrfToken(); ?>' },
      body: JSON.stringify({ proposal_id: proposalId })
    });
    const result = await res.json();
    if (result.success) {
      alert(result.message);
      window.location.href = '/dashboard/client/index.php';
    } else {
      alert(result.message);
    }
  } catch (err) {
    alert('Failed to connect to server.');
  }
}
</script>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
