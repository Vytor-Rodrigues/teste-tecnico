<?php
 if (isset($_SESSION['message'])): ?>

<div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?> alert-dismissible fade show mt-2" role="alert">
  <?= htmlspecialchars($_SESSION['message']) ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
<?php endif; ?>