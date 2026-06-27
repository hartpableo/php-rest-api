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
      Username
      <input type="text" name="username" placeholder="e.g., johndoe123">
    </label>
    <label>
      Password
      <input type="password" name="password">
    </label>
    <button type="submit">Sign up</button>
  </form>
</main>

<?php
require APP_ROOT . '/template/footer.php';
