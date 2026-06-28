<?php

global $request;

use App\Core\Session;

$menu = [
  [
    'label' => 'Login',
    'path' => '/login',
    'auth' => FALSE
  ],
  [
    'label' => 'Register',
    'path' => '/register',
    'auth' => FALSE
  ],
  [
    'label' => 'Dashboard',
    'path' => '/dashboard',
    'auth' => TRUE
  ],
  [
    'label' => 'Logout',
    'path' => '/logout',
    'auth' => TRUE,
  ]
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PHP Headless Multi-Tenant CMS REST API</title>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  
  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"
          defer></script>

  <style>
    body {
      font-family: 'Outfit', sans-serif;
      background: #f8fafc;
      color: #0f172a; /* Darker Slate 900 for body text to ensure contrast */
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    
    header {
      flex-shrink: 0;
    }

    main {
      flex: 1 0 auto;
      padding: 3rem 0;
    }

    .navbar {
      background: #0f172a !important; /* Dark slate */
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      border-bottom: 1px solid #1e293b;
      padding-top: 0.8rem;
      padding-bottom: 0.8rem;
    }

    .navbar-brand {
      font-weight: 700;
      letter-spacing: -0.5px;
      font-size: 1.25rem;
      color: #ffffff; /* Solid high-contrast white */
    }

    .nav-link {
      font-weight: 500;
      color: #cbd5e1 !important; /* Increased contrast Slate 300 (passes AA) */
      transition: all 0.2s ease-in-out;
      padding: 0.5rem 1rem !important;
      border-radius: 6px;
    }

    .nav-link:hover {
      color: #ffffff !important;
      background: rgba(255, 255, 255, 0.08);
    }

    .nav-link.active {
      color: #ffffff !important;
      background: rgba(99, 102, 241, 0.25);
    }

    .card {
      border: 1px solid #cbd5e1;
      border-radius: 12px;
      background: #ffffff;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-primary {
      background-color: #4f46e5; /* Solid Indigo 600 */
      border: 1px solid #4f46e5;
      font-weight: 600;
      padding: 0.6rem 1.5rem;
      border-radius: 8px;
      transition: all 0.2s ease;
    }

    .btn-primary:hover, .btn-primary:focus {
      background-color: #3730a3; /* Solid Indigo 800 */
      border-color: #3730a3;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
    }

    .form-label {
      font-weight: 600;
      color: #1e293b; /* Darker slate for form labels (passes AAA) */
      font-size: 0.9rem;
      margin-bottom: 0.4rem;
    }

    .form-control {
      border-radius: 8px;
      padding: 0.6rem 1rem;
      border: 1px solid #94a3b8; /* Darker border for input elements */
      font-size: 0.95rem;
      color: #0f172a;
      transition: all 0.2s ease;
    }

    .form-control:focus {
      border-color: #4f46e5;
      box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }
    
    .form-text {
      color: #475569 !important; /* Higher contrast form helper text (passes AA) */
    }

    .text-muted {
      color: #475569 !important; /* Overriding bootstrap default muted gray to meet WCAG AA contrast of 4.5+:1 */
    }

    .text-gradient {
      color: #4f46e5; /* Solid Indigo 600 for clean high-contrast accent links */
    }
    .text-gradient:hover {
      color: #3730a3; /* Solid Indigo 800 */
      text-decoration: underline !important;
    }
  </style>
</head>
<body>
<header role="banner">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="/dashboard">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cpu me-2" viewBox="0 0 16 16" style="color: #cbd5e1;">
          <path d="M5 0a.5.5 0 0 1 .5.5V2h1V.5a.5.5 0 0 1 1 0V2h1V.5a.5.5 0 0 1 1 0V2h1V.5a.5.5 0 0 1 1 0V2A2.5 2.5 0 0 1 14 4.5h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14v1h1.5a.5.5 0 0 1 0 1H14A2.5 2.5 0 0 1 11.5 14v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14h-1v1.5a.5.5 0 0 1-1 0V14h-1V12.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2v-1H.5a.5.5 0 0 1 0-1H2A2.5 2.5 0 0 1 4.5 2V.5A.5.5 0 0 1 5 0m-.5 3A1.5 1.5 0 0 0 3 4.5v7A1.5 1.5 0 0 0 4.5 13h7a1.5 1.5 0 0 0 1.5-1.5v-7A1.5 1.5 0 0 0 11.5 3zM5 5h6v6H5z"/>
        </svg>
        <span>CMS REST API</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <?php foreach ($menu as $item) :
            $isAuth = isset($item['auth']) && $item['auth'] === TRUE;
            $isLoggedIn = Session::isUserLoggedIn();

            if (($isAuth && !$isLoggedIn) || (!$isAuth && $isLoggedIn)) {
              continue;
            }

            $isActive = $item['path'] === $request->uri;
            ?>
            <li class="nav-item">
              <a
                class="nav-link <?php echo $isActive ? 'active' : ''; ?>"
                <?php echo $isActive ? 'aria-current="page"' : ''; ?>
                href="<?php echo $item['path']; ?>"
              ><?php echo $item['label']; ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </nav>
</header>