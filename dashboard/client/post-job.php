<?php
/**
 * WorkBazar — Post a New Job (Lead)
 */
require_once __DIR__ . '/../../includes/app.php';
App::init();

Auth::requireRole('client');

$user = Auth::user();
$pageTitle = "Post a New Project — WorkBazar";
include __DIR__ . '/../../includes/layouts/header.php';
include __DIR__ . '/../../includes/layouts/navbar.php';
?>



<link rel="stylesheet" href="<?php echo asset('assets/css/dashboard/client/post-job.css'); ?>">

<main class="post-job-container">
  <div class="post-card">
    <div class="post-header">
      <h1>Post your project</h1>
      <p>Connect with the best freelancers in India and worldwide.</p>
    </div>

    <div class="post-body">
      <!-- Step Indicator -->
      <div class="step-indicator">
        <div class="step active" id="step1-tab">
          <div class="step-num">1</div> Details
        </div>
        <div class="step" id="step2-tab">
          <div class="step-num">2</div> Budget
        </div>
        <div class="step" id="step3-tab">
          <div class="step-num">3</div> Finish
        </div>
      </div>

      <form id="postJobForm">
        <!-- Step 1: Details -->
        <div class="form-section active" id="section1">
          <div class="form-group">
            <label class="form-label">Project Title</label>
            <input type="text" name="title" class="form-input" placeholder="e.g. Build a Premium E-commerce Website" required>
          </div>

          <div class="form-group">
            <label class="form-label">Category</label>
            <select name="category" class="form-select" required>
              <option value="">Select Category</option>
              <option>Web Development</option>
              <option>Mobile App Development</option>
              <option>UI/UX Design</option>
              <option>Graphic Design</option>
              <option>Digital Marketing</option>
              <option>Content Writing</option>
              <option>AI & Automation</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-textarea" placeholder="Describe your project requirements in detail..." required></textarea>
          </div>

          <div class="nav-btns">
            <span></span>
            <button type="button" class="btn-primary" onclick="nextStep(2)">
              Next: Budget <i class="ri-arrow-right-line"></i>
            </button>
          </div>
        </div>

        <!-- Step 2: Budget -->
        <div class="form-section" id="section2">
          <label class="form-label">Budget Type</label>
          <div class="budget-options">
            <div class="budget-card active" id="cardFixed" onclick="setBudgetType('fixed')">
              <i class="ri-price-tag-3-line"></i>
              <div class="budget-details">
                <strong>Fixed Price</strong>
                <span>Set a total budget for the project.</span>
              </div>
              <input type="radio" name="budget_type" value="fixed" checked style="display:none;">
            </div>
            <div class="budget-card" id="cardHourly" onclick="setBudgetType('hourly')">
              <i class="ri-time-line"></i>
              <div class="budget-details">
                <strong>Hourly Rate</strong>
                <span>Pay per hour of work done.</span>
              </div>
              <input type="radio" name="budget_type" value="hourly" style="display:none;">
            </div>
          </div>

          <div class="form-row" style="display:flex; gap:16px; margin-top:28px;">
            <div class="form-group" style="flex:1;">
              <label class="form-label">Min Budget (₹)</label>
              <input type="number" name="budget_min" class="form-input" placeholder="1000">
            </div>
            <div class="form-group" style="flex:1;">
              <label class="form-label">Max Budget (₹)</label>
              <input type="number" name="budget_max" class="form-input" placeholder="5000">
            </div>
          </div>

          <div class="nav-btns">
            <button type="button" class="btn-outline" onclick="nextStep(1)">Back</button>
            <button type="button" class="btn-primary" onclick="nextStep(3)">
              Next: Review <i class="ri-arrow-right-line"></i>
            </button>
          </div>
        </div>

        <!-- Step 3: Review & Submit -->
        <div class="form-section" id="section3">
          <div style="text-align:center; padding:20px 0;">
            <i class="ri-checkbox-circle-line" style="font-size:4rem; color:var(--green);"></i>
            <h2 style="margin-top:20px;">Ready to publish?</h2>
            <p style="color:var(--muted); margin-bottom:32px;">Your project will be visible to thousands of freelancers instantly.</p>
          </div>

          <div id="reviewSummary" style="background:#f9faf9; border-radius:16px; padding:24px; margin-bottom:32px; border:1px dashed var(--border);">
             <!-- JS will populate this -->
          </div>

          <div class="nav-btns">
            <button type="button" class="btn-outline" onclick="nextStep(2)">Back</button>
            <button type="submit" class="btn-primary" id="submitBtn">
              Publish Project Now <i class="ri-rocket-2-line"></i>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</main>

<script>
let currentStep = 1;

function nextStep(step) {
  // Simple validation for step 1
  if (step === 2 && currentStep === 1) {
    const title = document.querySelector('input[name="title"]').value;
    const desc = document.querySelector('textarea[name="description"]').value;
    if (!title || !desc) {
      alert('Please fill in the project title and description.');
      return;
    }
  }

  if (step === 3) {
    updateSummary();
  }

  // Update Tabs
  document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
  document.getElementById(`step${step}-tab`).classList.add('active');

  // Update Sections
  document.querySelectorAll('.form-section').forEach(s => s.classList.remove('active'));
  document.getElementById(`section${step}`).classList.add('active');

  currentStep = step;
  window.scrollTo({ top: 100, behavior: 'smooth' });
}

function setBudgetType(type) {
  document.querySelectorAll('.budget-card').forEach(c => c.classList.remove('active'));
  if (type === 'fixed') {
    document.getElementById('cardFixed').classList.add('active');
    document.querySelector('input[value="fixed"]').checked = true;
  } else {
    document.getElementById('cardHourly').classList.add('active');
    document.querySelector('input[value="hourly"]').checked = true;
  }
}

function updateSummary() {
  const title = document.querySelector('input[name="title"]').value;
  const cat = document.querySelector('select[name="category"]').value;
  const bType = document.querySelector('input[name="budget_type"]:checked').value;
  const bMin = document.querySelector('input[name="budget_min"]').value || '0';
  const bMax = document.querySelector('input[name="budget_max"]').value || '0';

  document.getElementById('reviewSummary').innerHTML = `
    <div style="margin-bottom:12px;"><strong>Title:</strong> ${title}</div>
    <div style="margin-bottom:12px;"><strong>Category:</strong> ${cat}</div>
    <div><strong>Budget:</strong> ₹${bMin} - ₹${bMax} (${bType})</div>
  `;
}

document.getElementById('postJobForm').onsubmit = async (e) => {
  e.preventDefault();
  const btn = document.getElementById('submitBtn');
  btn.disabled = true;
  btn.innerHTML = '<i class="ri-loader-4-line"></i> Publishing...';

  const formData = new FormData(e.target);
  const payload = {};
  formData.forEach((value, key) => payload[key] = value);

  try {
    const res = await fetch('../../api/post_job.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo Security::csrfToken(); ?>' },
      body: JSON.stringify(payload)
    });
    const result = await res.json();
    if (result.success) {
      window.location.href = result.redirect;
    } else {
      alert(result.message);
      btn.disabled = false;
      btn.innerHTML = 'Publish Project Now <i class="ri-rocket-2-line"></i>';
    }
  } catch (err) {
    alert('Failed to connect to server.');
    btn.disabled = false;
    btn.innerHTML = 'Publish Project Now <i class="ri-rocket-2-line"></i>';
  }
};
</script>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
