<?php
/**
 * @var $csrfToken
 */
require APP_ROOT . '/template/header.php';
?>

<main role="main" class="container">
  <form method="post">
    <input type="hidden" name="__csrf_token" value="<?php echo $csrfToken; ?>">
    <label>
      Email Address
      <input type="email" name="email" placeholder="e.g., johndoe@domain.com">
    </label>
    <label>
      Password
      <input type="password" name="password">
    </label>
    <button type="submit">Login</button>
  </form>
</main>

<?php
require APP_ROOT . '/template/footer.php';
