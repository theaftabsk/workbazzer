<?php
/**
 * WorkBazar — Privacy Policy
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

$pageTitle = "Privacy Policy — WorkBazar";
include __DIR__ . '/../includes/layouts/header.php';
include __DIR__ . '/../includes/layouts/navbar.php';
?>

<div class="wb-container" style="max-width:800px; margin:80px auto; padding:0 20px;">
    <h1 style="font-size:3rem; font-weight:800; margin-bottom:20px;">Privacy Policy</h1>
    <p style="color:var(--muted); font-weight:600; margin-bottom:40px;">Last updated: <?=date('F d, Y')?></p>

    <div style="line-height:1.8; color:#334155;">
        <h2 style="font-size:1.5rem; font-weight:800; margin-top:40px;">1. Information We Collect</h2>
        <p>At WorkBazar, we collect information to provide better services to our users. This includes account data, profile information, and transaction history.</p>

        <h2 style="font-size:1.5rem; font-weight:800; margin-top:40px;">2. How We Use Data</h2>
        <p>Your data is used to facilitate matches between clients and freelancers, process secure payments via Razorpay, and improve the platform experience.</p>

        <h2 style="font-size:1.5rem; font-weight:800; margin-top:40px;">3. Data Security</h2>
        <p>We use enterprise-grade encryption to protect your personal and financial information. We never sell your data to third parties.</p>
    </div>
</div>

<?php include __DIR__ . '/../includes/layouts/footer.php'; ?>
