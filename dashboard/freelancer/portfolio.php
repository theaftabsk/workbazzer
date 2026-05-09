<?php
/**
 * WorkBazar — Freelancer Portfolio Management
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('freelancer');

$user = Auth::user();

// Fetch Portfolios
$portfolios = DB::all("SELECT * FROM portfolios WHERE user_id = ? ORDER BY created_at DESC", [$user['id']]);

$pageTitle = "My Portfolio — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>


<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/freelancer/portfolio.css'); ?>">

<main class="dashboard-wrap">
  <div class="page-header">
    <div>
      <h1>My Portfolio 📁</h1>
      <p>Showcase your best work to impress potential clients.</p>
    </div>
    <button class="btn-primary" onclick="openModal()">+ Add New Project</button>
  </div>

  <div class="portfolio-grid">
    <?php foreach($portfolios as $p): ?>
    <div class="portfolio-card">
      <div class="portfolio-img">
        <?php if($p['image_url']): ?>
          <img src="<?=$p['image_url']?>">
        <?php else: ?>
          <div class="portfolio-img-placeholder">
            <i class="ri-image-2-line"></i>
          </div>
        <?php endif; ?>
      </div>
      <div class="portfolio-content">
        <h3><?=htmlspecialchars($p['title'])?></h3>
        <p><?=htmlspecialchars($p['description'])?></p>
        <div class="portfolio-actions">
          <a href="<?=$p['project_url']?>" target="_blank">View Live →</a>
          <button onclick="deletePortfolio(<?=$p['id']?>)"><i class="ri-delete-bin-line"></i></button>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <?php if(empty($portfolios)): ?>
      <div class="empty-portfolio">
        <i class="ri-folder-add-line"></i>
        <h3>Your portfolio is empty.</h3>
        <p>Adding projects increases your hiring chances by 80%!</p>
      </div>
    <?php endif; ?>
  </div>
</main>

<!-- Modal -->
<div id="portfolioModal" class="modal-overlay">
  <div class="modal-content">
    <h2 style="margin-bottom:20px;">Add Portfolio Project</h2>
    <form id="portfolioForm">
      <div class="form-group">
        <label class="form-label">Project Title</label>
        <input type="text" name="title" class="form-input" required placeholder="e.g. E-commerce Website">
      </div>
      <div class="form-group" style="margin-top:15px;">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-textarea" style="height:80px;" placeholder="Briefly describe what you did..."></textarea>
      </div>
      <div class="form-group" style="margin-top:15px;">
        <label class="form-label">Project URL (Optional)</label>
        <input type="url" name="project_url" class="form-input" placeholder="https://...">
      </div>
      <div class="form-group" style="margin-top:15px;">
        <label class="form-label">Project Image</label>
        <input type="file" name="image" class="form-input" accept="image/*">
      </div>
      <div style="margin-top:30px; display:flex; gap:12px;">
        <button type="button" class="btn-outline" style="flex:1;" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn-primary" style="flex:1;" id="saveBtn">Save Project</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal() { document.getElementById('portfolioModal').style.display = 'flex'; }
function closeModal() { document.getElementById('portfolioModal').style.display = 'none'; }

document.getElementById('portfolioForm').onsubmit = async (e) => {
    e.preventDefault();
    const btn = document.getElementById('saveBtn');
    btn.disabled = true;
    btn.innerText = 'Saving...';

    const formData = new FormData(e.target);

    try {
        const res = await fetch('/api/add_portfolio.php', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '<?= Security::csrfToken() ?>' },
            body: formData
        });
        const result = await res.json();
        if(result.success) {
            window.location.reload();
        } else {
            alert(result.message);
        }
    } catch(err) { alert('Failed to add project.'); }
    btn.disabled = false;
    btn.innerText = 'Save Project';
};

async function deletePortfolio(id) {
    if(!confirm('Delete this project from portfolio?')) return;
    try {
        const res = await fetch('/api/delete_portfolio.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?= Security::csrfToken() ?>' },
            body: JSON.stringify({ portfolio_id: id })
        });
        const result = await res.json();
        if(result.success) window.location.reload();
    } catch(e) {}
}
</script>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
