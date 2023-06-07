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
  <h1>Signup was successfull</h1>
  <p>You have now signed up using <?php echo ($_SESSION['email']); ?></p>
  <p>This is your API key: <?php echo ($_SESSION['apiKey']); ?><br>
    treat it as a password and save it in a password manager.</p>
  <p>You can now login <a href="./login.php">here.</a></p>
</body>

</html>
<?php
unset($_SESSION['email']);
unset($_SESSION['apiKey']);
session_destroy();
?>