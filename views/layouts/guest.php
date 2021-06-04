<?php

/** @var \Kareem\Dominoes\View $this */

use Kareem\Dominoes\Application;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $this->title; ?></title>
  <link rel="stylesheet" href="./assets/css/guest.css">
  <!-- fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Zen+Dots&display=swap" rel="stylesheet">
</head>

<body>
  <div class="container">
    <nav class="navbar">
      <div class="logo">Dominoes</div>

      <div class="nav-menu">
        <ul>
          <li><a href="/">Home</a></li>
          <li><a href="/contact">Contact</a></li>
          <?php if (Application::isGuest()) : ?>
            <li><a href="/login">Login</a></li>
            <li><a href="/register">Register</a></li>
          <?php else : ?>
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/logout">Logout</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
    <main>
      <?php if (Application::$app->session->getFlash('success')) : ?>
        <div class="alert show alert-success">
          <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
          </svg>
          <span class="msg">
            <?php echo Application::$app->session->getFlash('success'); ?>
          </span>
          <svg id="close" class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </div>
      <?php endif; ?>
      {{content}}
    </main>
    <footer>
      <p class="copy-rights">&copy;2021. <span>Dominoes</span> PHP Framework</p>
      <p class="powered-by">Powered By: <span>Kareem Khalfalla</span></p>
    </footer>
  </div>
  <script src="./assets/js/guest.js"></script>
</body>

</html>
