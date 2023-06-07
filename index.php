<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>The Rooms</title>
</head>

<body>
  <h1>The Rooms</h1>

  <?php if (isset($_SESSION["user_id"])) : ?>
    <p>You are logged in.</p>
    <p><a href="./PHP/logout.php">Log out</a></p>
  <?php else : ?>
    <p><a href="./PAGES/login.php">Log in</a> or <a href="./PAGES/signup.html">Sign up</a></p>
  <?php endif; ?>
</body>

</html>