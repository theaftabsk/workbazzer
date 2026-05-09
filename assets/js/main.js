/**
 * WorkBazar — Main JavaScript
 * Enterprise UI/UX Interactions
 */
document.addEventListener('DOMContentLoaded', function () {

  /* ── Navbar scroll shadow ── */
  const nav = document.getElementById('wbNav');
  if (nav) {
    window.addEventListener('scroll', () => {
      nav.classList.toggle('scrolled', window.scrollY > 20);
    });
  }

  /* ── Smooth scroll for anchor links ── */
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const target = document.querySelector(a.getAttribute('href'));
      if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
    });
  });

  /* ── Scroll reveal animation ── */
  const revealEls = document.querySelectorAll(
    '.wb-step, .wb-testimonial, .wb-role, .wb-aside-card, .wb-tag-pill, .wb-feature-row, .job-card, .pricing-card, .wb-fcard, .portfolio-item, .stat-card'
  );
  if ('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.style.opacity = '1';
          e.target.style.transform = 'translateY(0)';
          io.unobserve(e.target);
        }
      });
    }, { threshold: 0.12 });

    revealEls.forEach(el => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(20px)';
      el.style.transition = 'opacity .5s ease, transform .5s ease';
      io.observe(el);
    });
  }

  /* ── Marketplace Mobile Filter Toggle ── */
  const filterToggleBtn = document.getElementById('mobileFilterToggle');
  const sidebar = document.querySelector('.mp-sidebar');
  if (filterToggleBtn && sidebar) {
    filterToggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('open');
    });
  }

  /* ── Auto-dismiss Alerts (if any) ── */
  const alerts = document.querySelectorAll('.alert-auto-dismiss');
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.opacity = '0';
      setTimeout(() => alert.remove(), 300);
    }, 4000);
  });

});
