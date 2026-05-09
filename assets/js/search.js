/**
 * WorkBazar Search JS
 * Handles live filtering & AJAX calls on talent-marketplace.php
 */

const Search = (() => {

  let debounceTimer;

  // Read URL params
  function getParams() {
    const p = new URLSearchParams(window.location.search);
    return {
      q:        p.get('q')        || '',
      category: p.get('category') || '',
      min_rate: p.get('min_rate') || '',
      max_rate: p.get('max_rate') || '',
      rating:   p.get('rating')   || '',
      page:     p.get('page')     || '1',
    };
  }

  // Update URL without reload
  function pushState(params) {
    const qs = new URLSearchParams(Object.fromEntries(
      Object.entries(params).filter(([,v]) => v !== '' && v !== '1' || (v === '1' && false))
    )).toString();
    history.pushState({}, '', '?' + qs);
  }

  // Build freelancer card HTML from JSON
  function buildCard(f) {
    const stars = '★'.repeat(Math.round(f.rating)) + '☆'.repeat(5 - Math.round(f.rating));
    const skills = (f.skills || []).slice(0, 5).map(s =>
      `<span class="wb-skill-chip">${s}</span>`
    ).join('');
    const avatar = f.avatar
      ? `<img src="uploads/avatars/${f.avatar}" alt="">`
      : `<span>${(f.fullname || 'U')[0].toUpperCase()}</span>`;
    const badge = f.verified ? `<span class="wb-badge-verified" title="Verified"><i class="ri-shield-check-fill"></i></span>` : '';
    const dot   = f.available ? `<div class="wb-fcard-dot"></div>` : '';

    return `
    <div class="wb-fcard" onclick="window.location='/public/profile.php?id=${f.id}'">
      <div class="wb-fcard-top">
        <div class="wb-fcard-avatar">${avatar}${dot}</div>
        <div class="wb-fcard-meta">
          <h3 class="wb-fcard-name">${f.fullname || 'Freelancer'} ${badge}</h3>
          <p class="wb-fcard-title">${f.title || 'Expert Freelancer'}</p>
          <div class="wb-fcard-location"><i class="ri-map-pin-line"></i> ${f.country || 'Global'}</div>
        </div>
        <div class="wb-fcard-rate">$${f.hourly_rate}<span>/hr</span></div>
      </div>
      <p class="wb-fcard-bio">${(f.bio || '').substring(0, 110)}…</p>
      <div class="wb-fcard-skills">${skills}</div>
      <div class="wb-fcard-footer">
        <div class="wb-fcard-stats">
          <span><i class="ri-star-fill"></i> ${f.rating.toFixed(1)}</span>
          <span><i class="ri-checkbox-circle-line"></i> ${f.success_rate || 98}% success</span>
        </div>
        <div class="wb-fcard-actions">
          <a href="/public/profile.php?id=${f.id}" class="wb-btn-view" onclick="event.stopPropagation()">View Profile</a>
          <a href="/auth/register.php" class="wb-btn-hire" onclick="event.stopPropagation()">Hire Now</a>
        </div>
      </div>
    </div>`;
  }

  // Build skeleton loaders
  function showSkeleton(count = 6) {
    const grid = document.getElementById('resultsGrid');
    if (!grid) return;
    grid.innerHTML = Array(count).fill(`
      <div class="wb-skeleton-card">
        <div class="sk sk-avatar"></div>
        <div class="sk sk-line" style="width:60%"></div>
        <div class="sk sk-line" style="width:40%"></div>
        <div class="sk sk-line sk-line-sm" style="width:80%"></div>
        <div class="sk sk-line sk-line-sm" style="width:70%"></div>
      </div>`).join('');
  }

  // Fetch & render
  async function fetchResults(params) {
    showSkeleton();
    document.getElementById('resultsCount').textContent = 'Searching…';

    try {
      const qs = new URLSearchParams(params).toString();
      const res = await fetch(`/api/search.php?${qs}`);
      const data = await res.json();
      const grid = document.getElementById('resultsGrid');

      if (!data.success || !data.results.length) {
        grid.innerHTML = `
          <div class="wb-empty-state">
            <i class="ri-search-2-line"></i>
            <h3>No freelancers found</h3>
            <p>Try a different search term or remove some filters.</p>
            <a href="/public/talent-marketplace.php" class="wb-btn-hire">Clear Filters</a>
          </div>`;
        document.getElementById('resultsCount').textContent = '0 results';
        renderPagination(0, 1, 1);
        return;
      }

      grid.innerHTML = data.results.map(buildCard).join('');
      document.getElementById('resultsCount').textContent =
        `${data.total.toLocaleString()} freelancers found`;
      renderPagination(data.total, data.page, data.pages);

    } catch (e) {
      document.getElementById('resultsGrid').innerHTML =
        `<div class="wb-empty-state"><i class="ri-wifi-off-line"></i><h3>Connection error</h3><p>Please refresh and try again.</p></div>`;
    }
  }

  // Pagination builder
  function renderPagination(total, current, pages) {
    const el = document.getElementById('wbPagination');
    if (!el || pages <= 1) { if(el) el.innerHTML=''; return; }

    let html = '';
    const params = getParams();

    const btn = (p, label, active = false, disabled = false) => {
      const cls = ['wb-page-btn', active ? 'active' : '', disabled ? 'disabled' : ''].filter(Boolean).join(' ');
      return `<button class="${cls}" onclick="Search.goPage(${p})">${label}</button>`;
    };

    html += btn(current - 1, '<i class="ri-arrow-left-s-line"></i>', false, current === 1);
    for (let i = 1; i <= pages; i++) {
      if (i === 1 || i === pages || (i >= current - 2 && i <= current + 2)) {
        html += btn(i, i, i === current);
      } else if (i === current - 3 || i === current + 3) {
        html += `<span class="wb-page-dots">…</span>`;
      }
    }
    html += btn(current + 1, '<i class="ri-arrow-right-s-line"></i>', false, current === pages);
    el.innerHTML = html;
  }

  // Public: go to page
  function goPage(p) {
    const params = getParams();
    params.page = p;
    pushState(params);
    fetchResults(params);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // Bind filter form
  function bindFilters() {
    const form = document.getElementById('filterForm');
    if (!form) return;

    form.addEventListener('change', () => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(applyFilters, 300);
    });

    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
      searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(applyFilters, 500);
      });
    }
  }

  function applyFilters() {
    const f  = document.getElementById('filterForm');
    const si = document.getElementById('searchInput');
    const params = {
      q:        si ? si.value.trim() : '',
      category: f.category ? f.category.value : '',
      min_rate: f.min_rate ? f.min_rate.value : '',
      max_rate: f.max_rate ? f.max_rate.value : '',
      rating:   f.rating   ? f.rating.value   : '',
      page:     '1',
    };
    pushState(params);
    fetchResults(params);
  }

  // Init
  function init() {
    bindFilters();
    const params = getParams();

    // Pre-fill form from URL
    const si = document.getElementById('searchInput');
    if (si && params.q) si.value = params.q;

    fetchResults(params);
  }

  return { init, goPage, applyFilters };
})();

document.addEventListener('DOMContentLoaded', Search.init);
