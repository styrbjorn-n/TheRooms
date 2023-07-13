<?php

session_start(); // Start a session to store user data
$is_invalid = false; // Variable to track if the command is invalid
$is_complete = false; // Variable to track if the game is complete

function interpret_command($command, $nearbyRooms, $itemConditions)
{
  $_SESSION["command"] = $command;
  $_SESSION["nearbyRooms"] = $nearbyRooms;
  $_SESSION["itemConditions"] = $itemConditions;

  $response = require __DIR__ . "./PHP/interpret_command.php";
  return $response;
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

  <?php if (isset($_SESSION["user_id"])) : ?> <!-- Check if the user is logged in -->

    <?php
    $mysqli = require __DIR__ . "./PHP/database.php"; // Include the database connection
    try { // this entire try catch block might be useless as i might have caused the error of some users having no current room set when doing manual changes in the database
      $roomData = require __DIR__ . "./PHP/room.php"; // Include the room data
      if (empty($roomData)) {
        throw new Exception("There was an error retriving the player loaction.");
      }
    } catch (Exception $e) {
      echo ($e->getMessage());
      echo ("<br> We are sorry for the incovinience you have been reset to the start of the game.");
      echo ("<br> Please re-load the page");
      exit();
    }
    // var_dump($roomData[2][0]);
    ?>

    <?php if (isset($_POST["command"])) : ?> <!-- Check if a command has been submitted -->
      <?php if (interpret_command($_POST["command"], $roomData[1], $roomData[2])) : ?> <!-- Interpret the command -->
        <?php
        unset($_POST["command"]); // Clear the command after it has been executed
        ?>
      <?php else : ?>
        <?php $is_invalid = true; // Set the flag to indicate an invalid command 
        ?>
      <?php endif; ?>
    <?php endif; ?>

    <?php
    $user_sql = sprintf(
      "SELECT * FROM users
          WHERE user_id = '%s'",
      $mysqli->real_escape_string($_SESSION["user_id"])
    ); // Prepare an SQL query to fetch user data

    $user_result = $mysqli->query($user_sql); // Execute the SQL query

    $user = $user_result->fetch_assoc(); // Fetch the user data
    $roomData = require __DIR__ . "./PHP/room.php"; // Include the room data
    ?>

    <h2>Welcome back <?php echo ($user["name"]); ?>.</h2> <!-- Display the user's name -->
    <h3><?php echo $user["room"] ?></h3> <!-- Display the user's current room -->

    <?php if (!$is_complete) : ?> <!-- If the game is not complete -->
      <?php if ($roomData[2][1] !== ""  && $user[$roomData[2][1]] !== "0") : ?>
        <p>You have picked up all items in this room.</p>
      <?php elseif (isset($_SESSION["containerOpen"]) && $_SESSION["containerOpen"] == true) : ?>
        <p>The container opens,<br>you see a <?php echo ($roomData[3]); ?></p>
      <?php elseif (isset($_SESSION["doorOpen"]) && $_SESSION["doorOpen"] == true) : ?>
        <p>The door opens</p>
      <?php elseif (isset($user[$user["room"] . "_door_opend"]) && $user[$user["room"] . "_door_opend"] == 1) : ?>
        <p>the door stays open</p>
      <?php else : ?>
        <p><?php echo ($roomData[0]); ?></p> <!-- Display the room description -->
      <?php endif; ?>
      <form action="" method="post"> <!-- Create a form to submit commands -->
        <?php if ($is_invalid) : ?>
          <p>Invalid command</p> <!-- Display an error message for invalid commands -->
        <?php endif; ?>
        <div>
          <label for="command">What do you do?</label> <!-- Label for the command input field -->
          <input type="text" name="command" id="command"> <!-- Command input field -->
        </div>
        <button>Submit Command</button>
      </form>
    <?php endif; ?>

    <p><a href="./PHP/logout.php">Log out</a></p> <!-- Logout link -->
  <?php else : ?>
    <p><a href="./PAGES/login.php">Log in</a> or <a href="./PAGES/signup.html">Sign up</a></p> <!-- Login and signup links -->
  <?php endif; ?>
</body>

</html>