<?php

global $request;

use App\Core\Session;

$menu = [
  [
    'label' => 'Login',
    'path' => '/login',
  ],
  [
    'label' => 'Register',
    'path' => '/register',
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
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PHP Headless Multi-Tenant CMS REST API</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous" defer></script>
</head>
<body>
<header role="banner">
  <nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">REST API</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <?php foreach ($menu as $item) :
            if (
              ($item['auth'] ?? FALSE) === TRUE
              && !Session::isUserLoggedIn()
            ) {
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