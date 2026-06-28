<?php
/**
 * @var $csrfToken
 * @var $apiKeys
 */
require APP_ROOT . '/template/header.php';
?>

<main role="main" class="container">
  <div class="row">
    <div class="col-21 col-md-6 form">
      <form action="/api/create" method="post">
        <input type="hidden" name="__csrf_token" value="<?php echo $csrfToken; ?>">
        <label>
          Site Host
          <input type="text" name="host" placeholder="e.g., www.example.com">
        </label>
        <button type="submit">Create API Key</button>
      </form>
    </div>
    <div class="col-21 col-md-6 result">
      <?php if (!empty($apiKeys)) : ?>
        <?php foreach ($apiKeys as $key) : ?>
          <div>
            <p><?php echo htmlspecialchars_decode($key['site_host']); ?></p>
            <p><?php echo htmlspecialchars_decode($key['key']); ?></p>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <p><strong>API KEYS HERE...</strong></p>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php
require APP_ROOT . '/template/footer.php';