<?php


$command = $_SESSION["command"];
$nearbyRooms = $_SESSION["nearbyRooms"];
$itemConditions = $_SESSION["itemConditions"];

unset($_SESSION["nearbyRooms"], $_SESSION["nearbyRooms"], $_SESSION["itemConditions"]);

switch ($command) {
  case str_contains($command, "go"): // If the command contains the word "go"
    foreach ($nearbyRooms as $room => $value) { // Loop through nearby rooms
      if ($value != null && str_contains($command, $room)) { // If the room is valid and mentioned in the command
        $_SESSION["next_room"] = $value; // Set the next room in the session
        return true; // Return true to indicate the command was interpreted successfully
        break; // Exit the loop
      }
    }
  case str_contains($command, "pick up"):
    $interactableItems = array("key", "thing");
    foreach ($interactableItems as $item) {
      if (str_contains($command, $item) || str_contains($itemConditions[1], $item)) {

        $mysqli = require __DIR__ . "./database.php";

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
    return true;
    break;
  default:
    return false; // Return false if the command is not recognized
}
