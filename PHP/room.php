<?php

$mysqli = require __DIR__ . "./database.php";

$sql = sprintf(
  "SELECT user_id, name, users.room, northen_room, southern_room, western_room, eastern_room, message, interactable_item, pickeble_item, required_item
  FROM users 
  INNER JOIN rooms
  ON users.room = rooms.room
  INNER JOIN nearby_rooms
  ON users.room = currnet_room
  WHERE user_id = '%s'",
  $mysqli->real_escape_string($_SESSION["user_id"])
);
$result = $mysqli->query($sql);
$roomData = $result->fetch_assoc();

if (!isset($roomData["room"])) { // this entire if else block might be useless as i might have caused the error of some users having no current room set when doing manual changes in the database
  $update_room_sql = sprintf("UPDATE users
        SET room = 'A'
        WHERE user_id = '%s'", $_SESSION["user_id"]);
  $mysqli->query($update_room_sql);
  return array();
} else {
  return array(
    $roomData["message"],
    array(
      "north" => $roomData["northen_room"],
      "south" => $roomData["southern_room"],
      "west" => $roomData["western_room"],
      "east" => $roomData["eastern_room"]
    ),
    array(
      $roomData["interactable_item"],
      $roomData["pickeble_item"],
      $roomData["required_item"]
    )
  );
}
