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
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-shield-lock text-primary" viewBox="0 0 16 16">
          <path d="M5.338 1.59a61 61 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.6.6 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7.2 7.2 0 0 1-1.038.618c-.282.13-.52.2-.667.241a2 2 0 0 1-.326.046 2 2 0 0 1-.326-.046c-.147-.041-.385-.11-.667-.24a7.2 7.2 0 0 1-1.038-.619 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
          <path d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415V11a.5.5 0 0 1-1 0V7.915A1.5 1.5 0 1 1 9.5 6.5"/>
        </svg>
      </div>
      <h2 class="fw-bold mb-1 mt-2">Welcome Back</h2>
      <p class="text-muted small">Sign in to your REST API developer dashboard</p>
    </div>
    <form method="post">
      <input type="hidden" name="__csrf_token" value="<?php echo $csrfToken; ?>">
      
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="e.g., johndoe@domain.com" required autocomplete="email">
      </div>
      
      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
      </div>
      
      <button type="submit" class="btn btn-primary w-100 mb-3 py-2">Sign In</button>
      
      <div class="text-center mt-3">
        <span class="text-muted small">Don't have an account? <a href="/register" class="text-decoration-none fw-semibold">Register</a></span>
      </div>
    </form>
  </div>
</main>

<?php
require APP_ROOT . '/template/footer.php';
