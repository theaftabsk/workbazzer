<?php
/**
 * WorkBazar Freelancer Card Component
 * Usage: include with $freelancer array set
 */
?>
<div class="wb-fcard" onclick="window.location='freelancer-profile.php?id=<?= $freelancer['id'] ?? 1 ?>'">
  <div class="wb-fcard-top">
    <div class="wb-fcard-avatar">
      <?php if (!empty($freelancer['avatar'])): ?>
        <img src="uploads/avatars/<?= htmlspecialchars($freelancer['avatar']) ?>" alt="">
      <?php else: ?>
        <span><?= strtoupper(substr($freelancer['fullname'] ?? 'U', 0, 1)) ?></span>
      <?php endif; ?>
      <?php if (!empty($freelancer['available'])): ?>
        <div class="wb-fcard-dot"></div>
      <?php endif; ?>
    </div>
    <div class="wb-fcard-meta">
      <h3 class="wb-fcard-name">
        <?= htmlspecialchars($freelancer['fullname'] ?? 'Expert Freelancer') ?>
        <?php if (!empty($freelancer['verified'])): ?>
          <span class="wb-badge-verified" title="Verified"><i class="ri-shield-check-fill"></i></span>
        <?php endif; ?>
      </h3>
      <p class="wb-fcard-title"><?= htmlspecialchars($freelancer['title'] ?? 'Freelance Expert') ?></p>
      <div class="wb-fcard-location">
        <i class="ri-map-pin-line"></i>
        <?= htmlspecialchars($freelancer['country'] ?? 'Global') ?>
        <?php if (!empty($freelancer['response_time'])): ?>
          · <i class="ri-time-line"></i> Responds in <?= $freelancer['response_time'] ?>
        <?php endif; ?>
      </div>
    </div>
    <div class="wb-fcard-rate">
      $<?= $freelancer['hourly_rate'] ?? '40' ?><span>/hr</span>
    </div>
  </div>

  <p class="wb-fcard-bio"><?= htmlspecialchars(substr($freelancer['bio'] ?? 'Expert freelancer with proven track record.', 0, 120)) ?>…</p>

  <div class="wb-fcard-skills">
    <?php
    $skills = is_array($freelancer['skills'] ?? null) ? $freelancer['skills'] : explode(',', $freelancer['skills'] ?? 'PHP,Laravel');
    foreach (array_slice($skills, 0, 5) as $skill):
    ?>
      <span class="wb-skill-chip"><?= htmlspecialchars(trim($skill)) ?></span>
    <?php endforeach; ?>
    <?php if (count($skills) > 5): ?>
      <span class="wb-skill-chip wb-skill-more">+<?= count($skills) - 5 ?></span>
    <?php endif; ?>
  </div>

  <div class="wb-fcard-footer">
    <div class="wb-fcard-stats">
      <span><i class="ri-star-fill"></i> <?= number_format($freelancer['rating'] ?? 4.9, 1) ?></span>
      <span><i class="ri-message-3-line"></i> <?= $freelancer['reviews'] ?? 0 ?> reviews</span>
      <span><i class="ri-checkbox-circle-line"></i> <?= $freelancer['success_rate'] ?? 98 ?>% success</span>
    </div>
    <div class="wb-fcard-actions">
      <a href="freelancer-profile.php?id=<?= $freelancer['id'] ?? 1 ?>" class="wb-btn-view" onclick="event.stopPropagation()">View Profile</a>
      <a href="register.php" class="wb-btn-hire" onclick="event.stopPropagation()">Hire Now</a>
    </div>
  </div>
</div>
