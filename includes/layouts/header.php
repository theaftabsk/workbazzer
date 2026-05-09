<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageTitle ?? 'WorkBazar — Hire Top Freelance Talent'; ?></title>
  <meta name="description" content="<?php echo $pageDesc ?? 'WorkBazar is an AI-powered enterprise freelance marketplace. Hire verified experts or find work across 500+ skill categories.'; ?>">
  
  <!-- Premium Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
  
  <!-- Core Styles -->
  <link rel="stylesheet" href="<?php echo asset('assets/css/common/global.css'); ?>">
  <link rel="stylesheet" href="<?php echo asset('assets/css/common/navbar.css'); ?>">
  <link rel="stylesheet" href="<?php echo asset('assets/css/common/mobile/navbar-responsive.css'); ?>">
  <link rel="stylesheet" href="<?php echo asset('assets/css/common/footer.css'); ?>">
  
  <!-- Page Specific Styles -->
  <?php 
    $uri = $_SERVER['REQUEST_URI'];
    if (strpos($uri, 'dashboard') !== false) {
        echo '<link rel="stylesheet" href="' . asset('assets/css/dashboard/dashboard.css') . '">';
    } elseif (strpos($uri, 'job-details') !== false) {
        echo '<link rel="stylesheet" href="' . asset('assets/css/public/job-details.css') . '">';
    } elseif (strpos($uri, 'profile.php') !== false) {
        echo '<link rel="stylesheet" href="' . asset('assets/css/public/profile.css') . '">';
    } elseif (strpos($uri, 'pricing') !== false) {
        echo '<link rel="stylesheet" href="' . asset('assets/css/public/pricing.css') . '">';
    } elseif (strpos($uri, 'privacy') !== false || strpos($uri, 'terms') !== false || strpos($uri, 'why-workbazar') !== false) {
        echo '<link rel="stylesheet" href="' . asset('assets/css/public/info.css') . '">';
    } elseif (strpos($uri, 'marketplace') !== false || strpos($uri, 'find-work') !== false) {
        echo '<link rel="stylesheet" href="' . asset('assets/css/public/marketplace.css') . '">';
    } elseif (strpos($uri, 'auth') !== false || strpos($uri, 'login') !== false || strpos($uri, 'register') !== false) {
        echo '<link rel="stylesheet" href="' . asset('assets/css/auth/auth.css') . '">';
    } else {
        // Default to landing CSS for home.php or root
        echo '<link rel="stylesheet" href="' . asset('assets/css/public/landing.css') . '">';
        echo '<link rel="stylesheet" href="' . asset('assets/css/public/landing-responsive.css') . '">';
    }
  ?>

  <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🚀</text></svg>">
</head>
<body>
