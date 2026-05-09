<?php
/**
 * WorkBazar — Enterprise Solutions
 */
require_once __DIR__ . '/../includes/app.php';
App::init();
$pageTitle = "Enterprise Solutions — WorkBazar";
require_once __DIR__ . '/../includes/layouts/header.php';
require_once __DIR__ . '/../includes/layouts/navbar.php';
?>

<link rel="stylesheet" href="<?php echo asset('assets/css/public/enterprise.css'); ?>">

<main>
  <!-- Hero Section -->
  <section class="enterprise-hero">
    <div class="enterprise-hero-content">
      <div class="enterprise-badge">WorkBazar Enterprise</div>
      <h1>Scale your workforce with enterprise-grade talent</h1>
      <p>Access the top 1% of vetted professionals, custom compliance workflows, and dedicated account management designed for large organizations.</p>
      <a href="mailto:enterprise@workbazar.com" class="btn-contact-sales">Contact Sales</a>
    </div>
  </section>

  <!-- Trusted By Section -->
  <section class="enterprise-logos">
    <p>Trusted by the world's most innovative teams</p>
    <div class="logo-grid">
      <i class="ri-amazon-fill"></i>
      <i class="ri-google-fill"></i>
      <i class="ri-microsoft-fill"></i>
      <i class="ri-netflix-fill"></i>
      <i class="ri-paypal-fill"></i>
    </div>
  </section>

  <!-- Features Section -->
  <section class="enterprise-features">
    
    <div class="feature-block">
      <div class="feature-content">
        <h2>Unmatched Compliance & Security</h2>
        <p>Enterprise-level security isn't an afterthought. We provide custom workflows that ensure every hire meets your organization's legal, tax, and compliance requirements globally.</p>
        <ul class="feature-list">
          <li><i class="ri-shield-check-fill"></i> Worker classification logic</li>
          <li><i class="ri-file-shield-2-fill"></i> Custom NDA & IP protection</li>
          <li><i class="ri-global-fill"></i> Global tax reporting & invoicing</li>
        </ul>
      </div>
      <div class="feature-image">
        <img src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?w=800&q=80" alt="Security & Compliance">
      </div>
    </div>

    <div class="feature-block">
      <div class="feature-content">
        <h2>Dedicated Account Management</h2>
        <p>Skip the search. Your dedicated talent success manager will hand-pick the perfect candidates for your projects within 24 hours, managing the entire lifecycle from onboarding to payment.</p>
        <ul class="feature-list">
          <li><i class="ri-user-star-fill"></i> VIP Talent Sourcing</li>
          <li><i class="ri-customer-service-2-fill"></i> 24/7 Priority Support</li>
          <li><i class="ri-group-fill"></i> Team Onboarding Assistance</li>
        </ul>
      </div>
      <div class="feature-image">
        <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=800&q=80" alt="Account Management">
      </div>
    </div>

    <div class="feature-block">
      <div class="feature-content">
        <h2>Advanced Reporting & Insights</h2>
        <p>Gain full visibility into your distributed workforce. Track spend, monitor project progress, and manage budgets across multiple teams and departments through a single dashboard.</p>
        <ul class="feature-list">
          <li><i class="ri-pie-chart-2-fill"></i> Real-time Spend Analytics</li>
          <li><i class="ri-organization-chart"></i> Multi-team budgeting</li>
          <li><i class="ri-file-chart-line"></i> Custom CSV/PDF Exports</li>
        </ul>
      </div>
      <div class="feature-image">
        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&q=80" alt="Reporting and Analytics">
      </div>
    </div>

  </section>

  <!-- CTA Section -->
  <section class="enterprise-cta">
    <h2>Ready to transform how your team works?</h2>
    <p>Join hundreds of enterprises that rely on WorkBazar to stay agile, innovate faster, and scale efficiently.</p>
    <a href="mailto:enterprise@workbazar.com" class="btn-cta-white">Get in touch with Sales</a>
  </section>
</main>

<?php require_once __DIR__ . '/../includes/layouts/footer.php'; ?>
