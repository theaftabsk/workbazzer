<?php
require_once __DIR__ . '/../includes/app.php';
App::init();

$pageTitle = "Pricing Plans — WorkBazar";
require_once __DIR__ . '/../includes/layouts/header.php';
require_once __DIR__ . '/../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/public/pricing.css'); ?>">

<main class="pricing-wrap">
    <div class="pricing-header">
        <h1>Choose the plan that's right for you</h1>
        <p>Scale your business with the power of WorkBazar</p>
    </div>
    
    <div class="pricing-toggle">
        <span class="toggle-label">Monthly</span>
        <label class="toggle-switch">
            <input type="checkbox" id="pricing-toggle-checkbox">
        </label>
        <span class="toggle-label">Annually <span class="save-badge">Save 20%</span></span>
    </div>

    <div class="pricing-grid">
        <!-- Basic Plan -->
        <div class="pricing-card">
            <div class="plan-name">Basic</div>
            <div class="plan-desc">Great for small projects</div>
            <div class="plan-price">Free</div>
            
            <ul class="plan-features">
                <li><i class="ri-check-line"></i> Access to talent marketplace</li>
                <li><i class="ri-check-line"></i> Standard payment protection</li>
                <li><i class="ri-check-line"></i> Basic customer support</li>
            </ul>
            
            <a href="/auth/register.php" class="btn-pricing outline">Get Started</a>
        </div>
        
        <!-- Plus Plan -->
        <div class="pricing-card popular">
            <div class="popular-badge">Most Popular</div>
            <div class="plan-name">Plus</div>
            <div class="plan-desc">For growing businesses</div>
            <div class="plan-price">$49.99<span>/mo</span></div>
            
            <ul class="plan-features">
                <li><i class="ri-check-line"></i> Everything in Basic</li>
                <li><i class="ri-check-line"></i> Premium support</li>
                <li><i class="ri-check-line"></i> Lower service fees</li>
                <li><i class="ri-check-line"></i> Verified talent badge</li>
            </ul>
            
            <a href="/auth/register.php" class="btn-pricing filled">Get Plus</a>
        </div>
        
        <!-- Enterprise Plan -->
        <div class="pricing-card">
            <div class="plan-name">Enterprise</div>
            <div class="plan-desc">Full-scale solutions</div>
            <div class="plan-price">Custom</div>
            
            <ul class="plan-features">
                <li><i class="ri-check-line"></i> Everything in Plus</li>
                <li><i class="ri-check-line"></i> Compliance tools</li>
                <li><i class="ri-check-line"></i> Dedicated account management</li>
                <li><i class="ri-check-line"></i> Unlimited users</li>
            </ul>
            
            <a href="/public/enterprise.php" class="btn-pricing outline" style="background:var(--ink); color:#fff; border-color:var(--ink);">Contact Sales</a>
        </div>
    </div>
</main>

<script>
    const toggle = document.getElementById('pricing-toggle-checkbox');
    const toggleContainer = document.querySelector('.pricing-toggle');
    const priceAmount = document.querySelector('.plan-price');
    
    toggle.addEventListener('change', () => {
        if(toggle.checked) {
            toggleContainer.classList.add('annual');
            priceAmount.innerHTML = '$479.90<span>/yr</span>';
        } else {
            toggleContainer.classList.remove('annual');
            priceAmount.innerHTML = '$49.99<span>/mo</span>';
        }
    });
</script>

<?php 
require_once __DIR__ . '/../includes/components/ticker.php'; 
require_once __DIR__ . '/../includes/layouts/footer.php'; 
?>
