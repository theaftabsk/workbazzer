<?php
/**
 * WorkBazar — Terms of Service
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

$pageTitle = "Terms of Service — WorkBazar";
include __DIR__ . '/../includes/layouts/header.php';
include __DIR__ . '/../includes/layouts/navbar.php';
?>

<div class="wb-container" style="max-width:800px; margin:80px auto; padding:0 20px;">
    <h1 style="font-size:3rem; font-weight:800; margin-bottom:20px;">Terms of Service</h1>
    <p style="color:var(--muted); font-weight:600; margin-bottom:40px;">Effective Date: <?=date('F d, Y')?></p>

    <div style="line-height:1.8; color:#334155;">
        <h2 style="font-size:1.5rem; font-weight:800; margin-top:40px;">1. Acceptance of Terms</h2>
        <p>By using WorkBazar, you agree to comply with our platform rules and conduct policies. We reserve the right to suspend accounts that violate these terms.</p>

        <h2 style="font-size:1.5rem; font-weight:800; margin-top:40px;">2. User Conduct</h2>
        <p>Freelancers and clients must maintain professional communication. Direct payments outside the platform to avoid fees is strictly prohibited.</p>

        <h2 style="font-size:1.5rem; font-weight:800; margin-top:40px;">3. Payments & Coins</h2>
        <p>All coin purchases are final. Coins are used for bidding on projects and accessing premium platform features.</p>
    </div>
</div>

<?php include __DIR__ . '/../includes/layouts/footer.php'; ?>
