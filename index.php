<?php

session_start();
$is_invalid = false;
$is_complete = false;

function isDoorOpen($interactebleObject, $command)
{
  foreach ($interactebleObject as $key => $value) {
    if (str_contains($command, $key)) {
      return (true);
      exit;
    }
  }
  return (false);
}
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

    <?php
    $mysqli = require __DIR__ . "./PHP/database.php";

    $sql = sprintf(
      "SELECT * FROM users
      WHERE id = '%s'",
      $mysqli->real_escape_string($_SESSION["user_id"])
    );

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();
    $roomData = require __DIR__ . "./PHP/room.php"

    ?>

    <h2>Welcome back <?php echo ($user["name"]); ?>.</h2>

    <?php if (isset($_POST["command"])) : ?>
      <?php if (isDoorOpen($roomData[1], $_POST["command"])) : ?>
        <p>Room done</p>
        <?php $is_complete = true ?>
      <?php else : ?>
        <?php $is_invalid = true; ?>
      <?php endif; ?>
    <?php endif; ?>

    <?php if( ! $is_complete): ?>
    <?php foreach ($roomData[0] as $message) : ?>
      <p><?php echo ($message); ?></p>
    <?php endforeach; ?>

    <form action="" method="post">
      <?php if ($is_invalid) : ?>
        <p>Invalid command</p>
      <?php endif; ?>
      <div>
        <label for="command">What do you do?</label>
        <input type="text" name="command" id="command">
      </div>
      <button>Submit Command</button>
    </form>
    <?php endif; ?>

    <p><a href="./PHP/logout.php">Log out</a></p>
  <?php else : ?>
    <p><a href="./PAGES/login.php">Log in</a> or <a href="./PAGES/signup.html">Sign up</a></p>
  <?php endif; ?>
</body>

</html>