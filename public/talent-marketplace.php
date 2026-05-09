<?php
$q         = htmlspecialchars(trim($_GET['q'] ?? ''));
$pageTitle = $q ? "\"$q\" — WorkBazar Talent" : "Hire Top Freelancers — WorkBazar";
$pageDesc  = "Find verified freelancers for $q. Browse talent across 500+ skills on WorkBazar.";
require_once __DIR__ . '/../includes/app.php';
App::init();
require_once __DIR__ . '/../includes/layouts/header.php';
require_once __DIR__ . '/../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/marketplace.css'); ?>">

<!-- ── SEARCH BAR ── -->
<div class="mp-searchbar">
  <form action="" method="GET" id="mainSearchForm" onsubmit="event.preventDefault(); Search.applyFilters();">
    <input type="text" id="searchInput" placeholder="Search freelancers (e.g. Software Engineer, React…)"
           value="<?= $q ?>">
    <button type="submit"><i class="ri-search-line"></i> Search</button>
  </form>

  <div class="mp-sort">
    <label>Sort by:</label>
    <select onchange="Search.applyFilters()">
      <option value="top">Top Rated</option>
      <option value="newest">Newest</option>
      <option value="price_asc">Price: Low → High</option>
      <option value="price_desc">Price: High → Low</option>
    </select>
  </div>
</div>

<!-- ── LAYOUT ── -->
<div class="mp-layout">

  <!-- FILTER SIDEBAR -->
  <aside class="mp-sidebar">
    <form id="filterForm">

      <div class="filter-group">
        <h4>Category</h4>
        <div class="filter-radio"><input type="radio" name="category" id="cat_all" value="" checked><label for="cat_all">All Categories</label></div>
        <div class="filter-radio"><input type="radio" name="category" id="cat_ai" value="AI"><label for="cat_ai">AI & Automation</label></div>
        <div class="filter-radio"><input type="radio" name="category" id="cat_dev" value="Development"><label for="cat_dev">Development & IT</label></div>
        <div class="filter-radio"><input type="radio" name="category" id="cat_design" value="Design"><label for="cat_design">Design & Creative</label></div>
        <div class="filter-radio"><input type="radio" name="category" id="cat_mkt" value="Marketing"><label for="cat_mkt">Marketing</label></div>
        <div class="filter-radio"><input type="radio" name="category" id="cat_write" value="Writing"><label for="cat_write">Writing & Content</label></div>
      </div>

      <div class="filter-group">
        <h4>Hourly Rate ($/hr)</h4>
        <div class="filter-range">
          <input type="number" name="min_rate" id="min_rate" placeholder="Min" min="0">
          <span>—</span>
          <input type="number" name="max_rate" id="max_rate" placeholder="Max" min="0">
        </div>
      </div>

      <div class="filter-group">
        <h4>Minimum Rating</h4>
        <div class="filter-radio filter-stars"><input type="radio" name="rating" value=""> <label>Any rating</label></div>
        <div class="filter-radio filter-stars"><input type="radio" name="rating" value="4"> <label>★★★★ 4.0+</label></div>
        <div class="filter-radio filter-stars"><input type="radio" name="rating" value="4.5"> <label>★★★★½ 4.5+</label></div>
        <div class="filter-radio filter-stars"><input type="radio" name="rating" value="4.8"> <label>★★★★★ 4.8+</label></div>
      </div>

      <div class="filter-group">
        <h4>Availability</h4>
        <div class="filter-check"><input type="checkbox" name="available" id="avail_now" value="1"><label for="avail_now">Available Now</label></div>
        <div class="filter-check"><input type="checkbox" name="verified" id="verified" value="1"><label for="verified">✅ Verified Only</label></div>
      </div>

      <button type="button" class="btn-clear" onclick="clearFilters()">
        <i class="ri-refresh-line"></i> Clear All Filters
      </button>
    </form>
  </aside>

  <!-- RESULTS -->
  <main class="mp-results">
    <div class="mp-results-header">
      <h2><span id="resultsCount">Loading…</span>
        <?php if ($q): ?> for "<strong><?= $q ?></strong>"<?php endif; ?>
      </h2>
    </div>

    <div class="wb-results-grid" id="resultsGrid">
      <!-- Skeleton preload -->
      <?php for ($i = 0; $i < 5; $i++): ?>
      <div class="wb-skeleton-card">
        <div style="display:flex;gap:16px;margin-bottom:14px;">
          <div class="sk sk-avatar"></div>
          <div style="flex:1;">
            <div class="sk" style="width:50%"></div>
            <div class="sk" style="width:35%"></div>
          </div>
        </div>
        <div class="sk sk-line-sm" style="width:90%"></div>
        <div class="sk sk-line-sm" style="width:75%"></div>
      </div>
      <?php endfor; ?>
    </div>

    <div id="wbPagination"></div>
  </main>
</div>

<script>
function clearFilters() {
  document.getElementById('filterForm').reset();
  document.getElementById('searchInput').value = '';
  history.pushState({}, '', '?');
  Search.init();
}
</script>
<script src="/assets/js/search.js"></script>

<?php require_once '../includes/layouts/footer.php'; ?>
