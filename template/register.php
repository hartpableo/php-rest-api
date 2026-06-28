<?php
/**
 * @var $csrfToken
 */
require APP_ROOT . '/template/header.php';
?>

<main role="main" class="container d-flex align-items-center justify-content-center" style="min-height: calc(105vh - 180px);">
  <div class="card p-4 p-md-5 shadow-sm w-100" style="max-width: 450px;">
    <div class="text-center mb-4">
      <div class="mb-2 d-inline-block p-3 rounded-circle bg-light">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-plus text-primary" viewBox="0 0 16 16">
          <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
          <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
        </svg>
      </div>
      <h2 class="fw-bold mb-1 mt-2">Create Account</h2>
      <p class="text-muted small">Register to start managing your API keys</p>
    </div>
    <form method="post">
      <input type="hidden" name="__csrf_token" value="<?php echo $csrfToken; ?>">
      
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="e.g., johndoe@domain.com" required autocomplete="email">
      </div>
      
      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required autocomplete="new-password">
      </div>
      
      <button type="submit" class="btn btn-primary w-100 mb-3 py-2">Sign Up</button>
      
      <div class="text-center mt-3">
        <span class="text-muted small">Already have an account? <a href="/login" class="text-decoration-none fw-semibold">Sign In</a></span>
      </div>
    </form>
  </div>
</main>

<?php
require APP_ROOT . '/template/footer.php';
