<?php
/**
 * WorkBazar — Job Marketplace (Find Work)
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

// Load Jobs with Filters
require_once __DIR__ . '/../core/Job.php';
$jobs = Job::getMarketplace($_GET);

$pageTitle = "Find Work — WorkBazar Marketplace";
include __DIR__ . '/../includes/layouts/header.php';
include __DIR__ . '/../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="/assets/css/marketplace.css">

<div class="marketplace-header" style="background: linear-gradient(135deg, #1dbf73 0%, #000 100%); color:#fff; padding:60px 20px; text-align:center;">
  <h1 style="font-size:2.5rem; font-weight:800; margin-bottom:15px;">Find Your Next Big Opportunity 🚀</h1>
  <p style="opacity:0.9; max-width:600px; margin:0 auto;">Browse thousands of verified projects and start earning today.</p>
</div>

<div class="search-container" style="max-width:800px; margin:-35px auto 40px; padding:0 20px;">
  <form class="search-bar" action="" method="GET" style="display:flex; background:#fff; padding:10px; border-radius:100px; box-shadow:0 10px 30px rgba(0,0,0,0.1);">
    <input type="text" name="q" placeholder="Search for jobs (e.g. Website Developer, UI Designer)" 
           value="<?=htmlspecialchars($_GET['q'] ?? '')?>" style="flex:1; border:none; padding:15px 25px; outline:none; font-size:1rem; border-radius:100px;">
    <button type="submit" style="background:var(--green); color:#fff; border:none; padding:0 30px; border-radius:100px; font-weight:700; cursor:pointer;">Search</button>
  </form>
</div>

<main class="marketplace-grid" style="display:grid; grid-template-columns: 320px 1fr; gap:40px; max-width:1400px; margin:0 auto; padding:20px;">
  
  <!-- Advanced Sidebar Filters -->
  <aside>
    <form action="" method="GET" id="filterForm">
      <div class="filters-card" style="background:#fff; border:1px solid var(--border); border-radius:24px; padding:24px; position:sticky; top:20px;">
        
        <div class="filter-group" style="margin-bottom:24px;">
          <h4 style="margin-bottom:15px; font-size:1rem; font-weight:800;">Categories</h4>
          <div style="display:flex; flex-direction:column; gap:10px;">
            <?php 
              $selectedCat = $_GET['category'] ?? ''; 
              $cats = ['', 'Web Development', 'UI/UX Design', 'Mobile App', 'Marketing'];
              foreach($cats as $c):
                $label = $c ?: 'All Categories';
            ?>
              <label class="filter-check-label">
                <input type="radio" name="category" value="<?= $c ?>" <?= $selectedCat == $c ? 'checked' : '' ?> onchange="this.form.submit()"> 
                <?= $label ?>
              </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="filter-group" style="margin-bottom:24px; border-top:1px solid #f4f7f4; padding-top:20px;">
          <h4 style="margin-bottom:15px; font-size:1rem; font-weight:800;">Work Type</h4>
          <div style="display:flex; flex-direction:column; gap:10px;">
            <?php 
              $selectedTypes = (array)($_GET['work_type'] ?? []); 
              $types = ['remote' => 'Remote Work', 'full_time' => 'Full-Time', 'on_site' => 'On-site'];
              foreach($types as $val => $label):
            ?>
              <label class="filter-check-label">
                <input type="checkbox" name="work_type[]" value="<?= $val ?>" <?= in_array($val, $selectedTypes) ? 'checked' : '' ?> onchange="this.form.submit()"> 
                <?= $label ?>
              </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="filter-group" style="margin-bottom:24px; border-top:1px solid #f4f7f4; padding-top:20px;">
          <h4 style="margin-bottom:15px; font-size:1rem; font-weight:800;">Budget Range (₹)</h4>
          <div style="display:flex; gap:10px; align-items:center;">
            <input type="number" name="min_budget" placeholder="Min" value="<?= (int)($_GET['min_budget'] ?? '') ?: '' ?>" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
            <span>-</span>
            <input type="number" name="max_budget" placeholder="Max" value="<?= (int)($_GET['max_budget'] ?? '') ?: '' ?>" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:8px;">
          </div>
          <button type="submit" class="btn-outline" style="width:100%; margin-top:15px; border-radius:8px;">Apply Budget</button>
        </div>

        <a href="/public/find-work.php" style="display:block; text-align:center; color:var(--muted); font-weight:700; text-decoration:none; margin-top:10px; font-size:0.85rem;">Clear All Filters</a>
      </div>
    </form>
  </aside>

  <!-- Job List -->
  <section>
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
      <h3 style="font-size:1.2rem; font-weight:800;"><?=count($jobs)?> jobs found</h3>
      <div style="font-size:0.9rem; color:var(--muted);">Sort by: <strong>Newest</strong></div>
    </div>

    <?php if (empty($jobs)): ?>
      <div class="empty-state" style="text-align:center; padding:100px 0; background:#fff; border-radius:24px; border:1px dashed var(--border);">
        <i class="ri-search-line" style="font-size:4rem; color:var(--border);"></i>
        <h3>No jobs found matching your criteria.</h3>
        <p>Try adjusting your filters or search keywords.</p>
      </div>
    <?php else: foreach($jobs as $job): ?>
      <div class="job-card" style="background:#fff; border:1px solid var(--border); border-radius:24px; padding:30px; margin-bottom:20px; transition:.2s; cursor:pointer;" onmouseover="this.style.borderColor='var(--green)'" onmouseout="this.style.borderColor='var(--border)'" onclick="window.location.href='/public/job-details.php?id=<?=$job['id']?>'">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:15px;">
           <span class="category-badge" style="background:#f0fdf4; color:var(--green); padding:6px 14px; border-radius:100px; font-size:0.75rem; font-weight:800; text-transform:uppercase;"><?=$job['category']?></span>
           <div style="font-size:1.2rem; font-weight:800; color:var(--green);">₹<?=number_format($job['budget_min'])?> - ₹<?=number_format($job['budget_max'])?></div>
        </div>
        
        <h3 style="font-size:1.4rem; font-weight:800; margin-bottom:10px;"><?=htmlspecialchars($job['title'])?></h3>
        <p style="color:var(--muted); line-height:1.6; margin-bottom:20px;"><?=substr(htmlspecialchars($job['description']), 0, 180)?>...</p>
        
        <div style="display:flex; flex-wrap:wrap; gap:20px; border-top:1px solid #f4f7f4; padding-top:20px;">
            <div style="display:flex; align-items:center; gap:8px; font-size:0.85rem; color:var(--muted); font-weight:600;">
              <i class="ri-map-pin-user-line"></i> <?=ucfirst(str_replace('_', ' ', $job['work_type']))?>
            </div>
            <div style="display:flex; align-items:center; gap:8px; font-size:0.85rem; color:var(--muted); font-weight:600;">
              <i class="ri-time-line"></i> <?=time_ago($job['created_at'])?>
            </div>
            <div style="display:flex; align-items:center; gap:8px; font-size:0.85rem; color:var(--muted); font-weight:600;">
              <i class="ri-group-line"></i> <?= $job['proposal_count'] ?? 0 ?> Proposals
            </div>
        </div>
      </div>
    <?php endforeach; endif; ?>
  </section>
</main>

<style>
.filter-check-label { display:flex; align-items:center; gap:10px; font-size:0.9rem; color:var(--ink); font-weight:600; cursor:pointer; }
.filter-check-label input { width:16px; height:16px; accent-color:var(--green); }
</style>

<?php include __DIR__ . '/../includes/layouts/footer.php'; ?>
