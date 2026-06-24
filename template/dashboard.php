<?php
/**
 * @var $csrfToken
 */
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Authentication | PHP Headless Multi-Tenant CMS REST API</title>
  <style>
    *, *::before, *::after {
      box-sizing: border-box;
    }

    body {
      max-width: 1920px;
      margin: 0 auto;
    }

    main {
      display: grid;
      grid-template-columns: minmax(0, 1fr);
    }

    @media screen and (min-width: 768px) {
      main {
        grid-template-columns: minmax(0, 40%) minmax(0, 1fr);
      }
    }
  </style>
</head>
<body>
<header role="banner">
  <h1>PHP Headless Multi-Tenant CMS REST API</h1>
</header>
<main role="main">
  <div class="col form">
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
      <button type="submit">Authenticate</button>
    </form>
  </div>
  <div class="col result">
    N/A
  </div>
</main>
<footer role="contentinfo">
  Developed by <a href="https://hartpableo.com" target="_blank">Hart</a>
</footer>
</body>
</html>