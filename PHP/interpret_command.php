<?php


$command = $_SESSION["command"];
$nearbyRooms = $_SESSION["nearbyRooms"];
unset($_SESSION["nearbyRooms"], $_SESSION["nearbyRooms"]);

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
