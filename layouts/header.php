<?php
define('PATH', strstr($_SERVER['REQUEST_URI'], 'admin') ? '../' : './');
require(PATH . 'bootstrap/app.php');

// Check if the the request comes from the administration pages and if a user is logged in
// if not logged in, redirect to the login page
if (strstr($_SERVER['REQUEST_URI'], 'admin') && !isset($_SESSION['id'])) {
    $uri = PATH . 'loggain.php';
    header("Location: $uri");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IK Svalan</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</head>

<body class="min-vh-100">
  <nav class="navbar navbar-dark navbar-expand-lg navbar-white bg-primary mb-3">
    <a class="navbar-brand text-white" href="<?php echo PATH ?>">IK Svalan</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="text-white nav-link" href="<?php echo PATH ?>">Startsida</a>
        </li>
        <li class="nav-item">
          <a class="text-white nav-link" href="<?php echo PATH ?>fotboll.php">Fotboll</a>
        </li>
        <li class="nav-item">
          <a class="text-white nav-link" href="<?php echo PATH ?>skidor.php">Skidor</a>
        </li>
        <li class="nav-item">
          <a class="text-white nav-link" href="<?php echo PATH ?>gymnastik.php">Gymnastik</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <?php
        // Check if a user is logged in
        if (isset($_SESSION['id'])){
            $user = App\User::find($_SESSION['id']);
        ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo "$user->firstName $user->lastName"; ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="<?php echo PATH ?>admin">Administration</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?php echo PATH ?>loggaut.php">Logga ut</a>
          </div>
        </li>
        <?php
        } else {
        // If no user is logged in show login link
        ?>
        <a class="navbar-text nav-link text-white" href="<?php echo PATH ?>loggain.php">
          Logga in
        </a>
        <?php } ?>
    </div>
    </div>
  </nav>