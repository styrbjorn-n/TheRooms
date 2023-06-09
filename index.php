<?php

session_start(); // Start a session to store user data
$is_invalid = false; // Variable to track if the command is invalid
$is_complete = false; // Variable to track if the game is complete

function interpret_command($command, $nearbyRooms)
{
  switch ($command) {
    case str_contains($command, "go"): // If the command contains the word "go"
      foreach ($nearbyRooms as $room => $value) { // Loop through nearby rooms
        if ($value != null && str_contains($command, $room)) { // If the room is valid and mentioned in the command
          $_SESSION["next_room"] = $value; // Set the next room in the session
          return true; // Return true to indicate the command was interpreted successfully
          break; // Exit the loop
        }
      }
    default:
      return false; // Return false if the command is not recognized
  }
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
    $roomData = require __DIR__ . "./PHP/room.php"; // Include the room data
    ?>

     <?php if (isset($_POST["command"])) : ?> <!-- Check if a command has been submitted -->
      <?php if (interpret_command($_POST["command"], $roomData[1])) : ?> <!-- Interpret the command -->
        <?php

        $update_room_sql = sprintf("UPDATE users
        SET room = '%s'
        WHERE user_id = '%s'",$_SESSION["next_room"],$_SESSION["user_id"]); // Prepare an SQL query to update the user's room

        $mysqli->query($update_room_sql); // Execute the SQL query to update the room
        unset($_POST["command"]); // Clear the command after it has been executed

        ?>
      <?php else : ?>
        <?php $is_invalid = true; // Set the flag to indicate an invalid command ?>
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
      <p><?php echo ($roomData[0]); ?></p> <!-- Display the room description -->
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
