<?php
/**
 * WorkBazar — Admin: Manage All Jobs
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('admin');

$user = Auth::user();

// Fetch All Jobs with Client Info
$jobs = DB::all("SELECT j.*, u.fullname as client_name, 
                 (SELECT COUNT(*) FROM proposals WHERE job_id = j.id) as bid_count 
                 FROM jobs j 
                 JOIN users u ON j.client_id = u.id 
                 ORDER BY j.created_at DESC");

$pageTitle = "Manage Jobs — WorkBazar Admin";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>



<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/admin/jobs.css'); ?>">

<main class="dashboard-wrap">
  <div class="page-header">
    <h1>Project Management 🛠️</h1>
    <p>Monitor and moderate all projects posted on the platform.</p>
  </div>

  <div class="admin-table-card">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Project Title</th>
          <th>Client</th>
          <th>Bids</th>
          <th>Budget</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($jobs as $job): ?>
        <tr id="job-row-<?=$job['id']?>">
          <td>
            <strong><?=htmlspecialchars($job['title'])?></strong><br>
            <small class="job-date"><?=date('M d, Y', strtotime($job['created_at']))?></small>
          </td>
          <td><?=htmlspecialchars($job['client_name'])?></td>
          <td><?=$job['bid_count']?></td>
          <td>₹<?=$job['budget_min']?> - ₹<?=$job['budget_max']?></td>
          <td>
            <span class="status-pill status-<?=$job['status']?>" id="status-pill-<?=$job['id']?>">
              <?=str_replace('_', ' ', $job['status'])?>
            </span>
          </td>
          <td>
            <div class="job-actions">
              <?php if(!$job['is_approved']): ?>
              <button onclick="approveJob(<?=$job['id']?>)" class="btn-sm btn-approve">
                <i class="ri-check-line"></i> Approve
              </button>
              <?php endif; ?>
              <button onclick="toggleJob(<?=$job['id']?>)" class="btn-sm btn-outline">
                <i class="ri-refresh-line"></i> Toggle Status
              </button>
              <button onclick="deleteJob(<?=$job['id']?>)" class="btn-sm btn-delete">
                <i class="ri-delete-bin-line"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>

<script>
async function approveJob(id) {
  try {
    const res = await fetch('/api/admin_approve_job.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?= Security::csrfToken() ?>' },
      body: JSON.stringify({ job_id: id })
    });
    const result = await res.json();
    if(result.success) {
      window.location.reload();
    } else {
      alert(result.message);
    }
  } catch(e) { alert('Request failed.'); }
}

async function toggleJob(id) {
  if(!confirm('Are you sure you want to change this job status?')) return;
  try {
    const res = await fetch('/api/admin_toggle_job.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?= Security::csrfToken() ?>' },
      body: JSON.stringify({ job_id: id })
    });
    const result = await res.json();
    if(result.success) {
      const pill = document.getElementById(`status-pill-${id}`);
      pill.className = `status-pill status-${result.new_status}`;
      pill.textContent = result.new_status;
    } else {
      alert(result.message);
    }
  } catch(e) { alert('Request failed.'); }
}

async function deleteJob(id) {
  if(!confirm('DANGER: This will permanently delete the project and all its bids. Continue?')) return;
  try {
    const res = await fetch('/api/admin_delete_job.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?= Security::csrfToken() ?>' },
      body: JSON.stringify({ job_id: id })
    });
    const result = await res.json();
    if(result.success) {
      document.getElementById(`job-row-${id}`).remove();
    } else {
      alert(result.message);
    }
  } catch(e) { alert('Request failed.'); }
}
</script>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
