<?php
/**
 * @var $csrfToken
 * @var $apiKeys
 */
require APP_ROOT . '/template/header.php';

use App\Core\Session;
$currentUser = Session::getCurrentUser();
$userEmail = $currentUser ? htmlspecialchars($currentUser['email']) : 'Developer';
?>

<style>
  .badge.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1) !important;
    color: #0f5132 !important; /* Darker green (contrast > 8:1) */
  }
  .badge.bg-primary-soft {
    background-color: rgba(79, 70, 229, 0.1) !important;
    color: #3730a3 !important; /* Darker indigo (contrast > 9:1) */
  }
  .font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
  }
</style>

<main role="main" class="container">
  <!-- Welcome Header -->
  <div class="row mb-5 align-items-center">
    <div class="col-12 col-md-8">
      <span class="badge bg-primary-soft mb-2 px-3 py-2 rounded-pill font-monospace" style="font-size: 0.8rem;">Developer Console</span>
      <h1 class="fw-bold tracking-tight mb-1">API Credentials</h1>
      <p class="text-muted mb-0">Manage your API tokens and register hosts to connect your external applications.</p>
    </div>
    <div class="col-12 col-md-4 text-md-end mt-3 mt-md-0">
      <div class="text-muted small">Logged in as: <strong class="text-dark"><?php echo $userEmail; ?></strong></div>
    </div>
  </div>

  <div class="row g-4">
    <!-- Left Column: Create API Key Form -->
    <div class="col-12 col-lg-4">
      <div class="card p-4 h-100">
        <h2 class="h5 fw-bold mb-3 d-flex align-items-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-plus-circle text-primary me-2" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
          </svg>
          Generate New Key
        </h2>
        <p class="text-muted small mb-4">API tokens allow your websites or applications to securely fetch content from this CMS.</p>
        
        <form action="/apikeys/create" method="post">
          <input type="hidden" name="__csrf_token" value="<?php echo $csrfToken; ?>">
          
          <div class="mb-4">
            <label for="host" class="form-label">Site Host</label>
            <input type="text" class="form-control" id="host" name="host" placeholder="e.g., www.example.com" required autocomplete="off">
            <div class="form-text">Specify the origin domain of the application accessing this API.</div>
          </div>
          
          <button type="submit" class="btn btn-primary w-100 py-2">Generate Token</button>
        </form>
      </div>
    </div>

    <!-- Right Column: API Keys Listing -->
    <div class="col-12 col-lg-8">
      <div class="card p-4 h-100">
        <h2 class="h5 fw-bold mb-3 d-flex align-items-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-key text-primary me-2" viewBox="0 0 16 16">
            <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
          </svg>
          Active Credentials
        </h2>
        
        <?php if (!empty($apiKeys['data'] ?? [])) : ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th scope="col" class="py-3 px-4" style="font-size: 0.85rem; font-weight: 600; color: #334155;">Site Host</th>
                  <th scope="col" class="py-3" style="font-size: 0.85rem; font-weight: 600; color: #334155;">API Token</th>
                  <th scope="col" class="py-3 text-end px-4" style="font-size: 0.85rem; font-weight: 600; color: #334155;">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($apiKeys['data'] as $key) : 
                  $host = htmlspecialchars_decode($key['site_host']);
                  $token = htmlspecialchars_decode($key['api_token']);
                ?>
                  <tr>
                    <td class="px-4 py-3">
                      <span class="fw-semibold text-dark"><?php echo htmlspecialchars($host); ?></span>
                    </td>
                    <td class="py-3">
                      <div class="input-group input-group-sm" style="max-width: 320px;">
                        <input type="text" class="form-control font-monospace text-muted bg-light border-0" value="<?php echo htmlspecialchars($token); ?>" readonly style="font-size: 0.8rem; padding-left: 0.75rem;">
                        <button class="btn btn-outline-secondary btn-copy" type="button" data-clipboard-text="<?php echo htmlspecialchars($token); ?>" title="Copy Key">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
                            <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
                            <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
                          </svg>
                        </button>
                      </div>
                    </td>
                    <td class="text-end px-4 py-3">
                      <span class="badge bg-success-soft rounded-pill px-3 py-2" style="font-size: 0.75rem;">Active</span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else : ?>
          <div class="text-center py-5 my-auto">
            <div class="mb-3" style="color: #94a3b8;">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-key" viewBox="0 0 16 16">
                <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
              </svg>
            </div>
            <h3 class="h6 fw-bold mb-1">No API keys generated</h3>
            <p class="text-muted small mb-0">Provide a host name in the left panel to register and generate a token.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const copyButtons = document.querySelectorAll('.btn-copy');
  copyButtons.forEach(button => {
    button.addEventListener('click', function() {
      const textToCopy = this.getAttribute('data-clipboard-text');
      navigator.clipboard.writeText(textToCopy).then(() => {
        // Change icon and add success styling temporarily
        const originalHTML = this.innerHTML;
        this.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-check2 text-white" viewBox="0 0 16 16">
            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
          </svg>
        `;
        this.classList.remove('btn-outline-secondary');
        this.classList.add('btn-success');
        
        setTimeout(() => {
          this.innerHTML = originalHTML;
          this.classList.remove('btn-success');
          this.classList.add('btn-outline-secondary');
        }, 1500);
      }).catch(err => {
        console.error('Failed to copy: ', err);
      });
    });
  });
});
</script>

<?php
require APP_ROOT . '/template/footer.php';