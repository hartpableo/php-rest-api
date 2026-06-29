<?php
/**
 * @var $status
 * @var $message
 */
require APP_ROOT . '/template/header.php';
?>

<main class="container">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow-lg border-0 rounded-4 overflow-hidden mt-5">
        <div class="card-body p-5 text-center">
          <div class="mb-4 text-danger d-flex justify-content-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                 class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
              <path
                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
            </svg>
          </div>

          <h1 class="display-5 fw-bold text-dark mb-3"><?= htmlspecialchars($status) ?></h1>
          <h3 class="h4 text-muted mb-4"><?= htmlspecialchars($message) ?></h3>

          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger text-start rounded-3 mb-4">
              <ul class="mb-0 list-unstyled">
                <?php foreach ($errors as $errorField => $errMsgs): ?>
                  <?php foreach ((array)$errMsgs as $errMsg): ?>
                    <li><strong><?= htmlspecialchars($errorField) ?>:</strong> <?= htmlspecialchars($errMsg) ?></li>
                  <?php endforeach; ?>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <div class="d-grid gap-2">
            <a href="<?php echo $_SERVER['HTTP_REFERER'] ?? '/'; ?>" class="btn btn-primary btn-lg rounded-3">Back</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require APP_ROOT . '/template/footer.php'; ?>
