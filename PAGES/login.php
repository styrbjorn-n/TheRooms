<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $mysqli = require __DIR__ . "./../PHP/database.php";

  $sql = sprintf(
    "SELECT * FROM users
    WHERE email = '%s'",
    $mysqli->real_escape_string($_POST["email"])
  );

  $result = $mysqli->query($sql);

  $user = $result->fetch_assoc();

  if ($user) {
    if ($_POST["apikey"] == $user["apiKey"]) {
      session_start();
      $_SESSION["user_id"] = $user["id"];
      header("location: ./../index.php");
      exit();
    }
  }
  $is_invalid = true;
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
  <form method="post">
    <h2>Login</h2>
    <div>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="<?= $_POST["email"] ?? "" ?>">
    </div>
    <div>
      <label for="apikey">Api-Key</label>
      <input type="text" id="apikey" name="apikey">
    </div>
    <?php if ($is_invalid) : ?>
      <p>Invalid login details</p>
    <?php endif; ?>
    <button>Login</button>
  </form>
</body>

</html>