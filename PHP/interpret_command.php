<?php


$command = $_SESSION["command"];
$nearbyRooms = $_SESSION["nearbyRooms"];
$itemConditions = $_SESSION["itemConditions"];

unset($_SESSION["nearbyRooms"], $_SESSION["nearbyRooms"], $_SESSION["itemConditions"]);
$mysqli = require __DIR__ . "./database.php";

switch ($command) {
  case str_contains($command, "go"): // If the command contains the word "go"
    foreach ($nearbyRooms as $room => $value) { // Loop through nearby rooms
      if ($value != null && str_contains($command, $room)) { // If the room is valid and mentioned in the command
        if (str_contains($itemConditions[0], $value . "door") && isset($_SESSION["doorOpen"]) && $_SESSION["doorOpen"] == true) {
          $update_room_sql = sprintf("UPDATE users
          SET room = '%s'
          WHERE user_id = '%s'", $value, $_SESSION["user_id"]); // Prepare an SQL query to update the user's room

          $mysqli->query($update_room_sql); // Execute the SQL query to update the room

          unset($_SESSION["doorOpen"]);
          unset($_SESSION["containerOpen"]);
          return true; // Return true to indicate the command was interpreted successfully
          break; // Exit the loop
        } elseif (str_contains($itemConditions[0], $value . "door") && !isset($_SESSION["doorOpen"])) {
          return false;
          break;
        } else {
          $update_room_sql = sprintf("UPDATE users
          SET room = '%s'
          WHERE user_id = '%s'", $value, $_SESSION["user_id"]); // Prepare an SQL query to update the user's room

          $mysqli->query($update_room_sql); // Execute the SQL query to update the room
          unset($_SESSION["doorOpen"]);
          unset($_SESSION["containerOpen"]);
          return true; // Return true to indicate the command was interpreted successfully
          break; // Exit the loop
        }
      }
    }
  case str_contains($command, "pick up"):
    $interactableItems = array("key", "thing", "item");
    foreach ($interactableItems as $item) {
      if (str_contains($command, $item) && str_contains($itemConditions[1], $item)) {
        if ($itemConditions[0] != "" && isset($_SESSION["containerOpen"]) && $_SESSION["containerOpen"] == true) {
          $sql = sprintf(
            "UPDATE users
          SET `%s` = 1
          WHERE user_id = %s",
            $itemConditions[1],
            $_SESSION["user_id"]
          );

          $mysqli->query($sql);

          unset($_SESSION["containerOpen"]);
          return true;
          break;
        } elseif ($itemConditions[0] != "" && !isset($_SESSION["containerOpen"])) {
          return false;
          break;
        } else {
          $sql = sprintf(
            "UPDATE users
          SET `%s` = 1
          WHERE user_id = %s",
            $itemConditions[1],
            $_SESSION["user_id"]
          );

          $mysqli->query($sql);

          return true;
          break;
        }
      }
    }
  case str_contains($command, "open"):
    if (str_contains($command, "container")) {

      $sql = sprintf(
        "SELECT D_key, E_key
      FROM users
      WHERE user_id = 16"
      );

      $result = $mysqli->query($sql);
      $userItems = $result->fetch_assoc();

      foreach ($userItems as $item => $value) {
        if ($itemConditions[2] == $item) {

          $_SESSION["containerOpen"] = true;
          return true;
          break;
        }
      }
    } elseif (str_contains($command, "door")) {
      $sql = sprintf(
        "SELECT D_key, E_key
      FROM users
      WHERE user_id = 16"
      );

      $result = $mysqli->query($sql);
      $userItems = $result->fetch_assoc();

      foreach ($userItems as $item => $value) {
        if ($itemConditions[2] == $item) {

          $_SESSION["doorOpen"] = true;
          return true;
          break;
        }
      }
    }
  default:
    return false; // Return false if the command is not recognized
}
