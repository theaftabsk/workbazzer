<?php
/**
 * WorkBazar — Client: Active Contracts
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('client');

$user = Auth::user();

// Fetch Active Jobs (Where Proposal is Accepted and job is in progress)
$activeJobs = DB::all("SELECT j.*, p.bid_amount, u.fullname as freelancer_name, 
                              u.email as freelancer_email, u.phone as freelancer_phone,
                              u.id as freelancer_id
                       FROM jobs j
                       JOIN proposals p ON j.id = p.job_id
                       JOIN users u ON p.freelancer_id = u.id
                       WHERE j.client_id = ? AND p.status = 'accepted' AND j.status = 'in_progress'
                       ORDER BY j.updated_at DESC", [$user['id']]);

$pageTitle = "Active Contracts — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>



<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/client/active-jobs.css'); ?>">

<main class="dashboard-wrap">
  <div class="page-header">
    <h1>Active Contracts 🤝</h1>
    <p>Monitor your ongoing projects and communicate with hired talent.</p>
  </div>

  <?php if (empty($activeJobs)): ?>
    <div class="empty-active-contracts">
      <i class="ri-user-star-line"></i>
      <h3>No active contracts found.</h3>
      <a href="/dashboard/client/manage-jobs.php">Review proposals to hire →</a>
    </div>
  <?php else: foreach($activeJobs as $aj): ?>
    <div class="active-contract-card">
      <div class="contract-header">
        <div class="contract-title">
          <h2><?=htmlspecialchars($aj['title'])?></h2>
          <div class="contract-freelancer">Working with: <strong><?=htmlspecialchars($aj['freelancer_name'])?></strong></div>
        </div>
        <div class="status-badge status-in_progress">Active</div>
      </div>

      <div class="contact-reveal">
        <b>Direct Freelancer Contact:</b>
        <div class="contact-row">
          <span><i class="ri-mail-line"></i> <?=htmlspecialchars($aj['freelancer_email'])?></span>
          <?php if($aj['freelancer_phone']): ?>
            <span><i class="ri-phone-line"></i> <?=htmlspecialchars($aj['freelancer_phone'])?></span>
          <?php endif; ?>
        </div>
      </div>

      <div class="contract-footer">
        <div class="contract-budget">Budget: <strong>₹<?=number_format($aj['bid_amount'])?></strong></div>
        <div class="contract-actions">
           <a href="/public/talent-marketplace.php?id=<?=$aj['freelancer_id']?>" class="link-profile">View Profile</a>
           <button onclick="completeProject(<?=$aj['id']?>, <?=$aj['freelancer_id']?>)" class="btn-primary" style="padding:10px 20px; font-size:0.85rem;">Complete & Review</button>
        </div>
      </div>
    </div>
  <?php endforeach; endif; ?>
</main>
<!-- Review Modal -->
<div id="reviewModal" class="modal-overlay">
  <div class="modal-content">
    <h2>Review Freelancer ⭐️</h2>
    <p>Mark the project as complete and share your feedback.</p>
    <form id="reviewForm">
      <input type="hidden" name="job_id" id="modalJobId">
      <input type="hidden" name="freelancer_id" id="modalFreelancerId">
      
      <div class="form-group">
        <label class="form-label">Rating (1-5)</label>
        <select name="rating" class="form-select" required>
          <option value="5">★★★★★ (5 - Excellent)</option>
          <option value="4">★★★★☆ (4 - Good)</option>
          <option value="3">★★★☆☆ (3 - Average)</option>
          <option value="2">★★☆☆☆ (2 - Poor)</option>
          <option value="1">★☆☆☆☆ (1 - Terrible)</option>
        </select>
      </div>

      <div class="form-group" style="margin-top:15px;">
        <label class="form-label">Private/Public Comment</label>
        <textarea name="comment" class="form-textarea" style="height:100px;" placeholder="Describe your experience working with this freelancer..." required></textarea>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn-outline" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn-primary" id="submitReviewBtn">Finish & Pay</button>
      </div>
    </form>
  </div>
</div>

<script>
function completeProject(jobId, freelancerId) {
    document.getElementById('modalJobId').value = jobId;
    document.getElementById('modalFreelancerId').value = freelancerId;
    document.getElementById('reviewModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('reviewModal').style.display = 'none';
}

document.getElementById('reviewForm').onsubmit = async (e) => {
    e.preventDefault();
    const btn = document.getElementById('submitReviewBtn');
    btn.disabled = true;
    btn.innerText = 'Processing...';

    const formData = new FormData(e.target);
    const payload = Object.fromEntries(formData.entries());

    try {
        const res = await fetch('/api/complete_job.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?= Security::csrfToken() ?>' },
            body: JSON.stringify(payload)
        });
        const result = await res.json();
        if(result.success) {
            alert('Project marked as completed! Review submitted.');
            window.location.reload();
        } else {
            alert(result.message);
        }
    } catch(err) { alert('Request failed.'); }
    btn.disabled = false;
    btn.innerText = 'Finish & Pay';
};
</script>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
