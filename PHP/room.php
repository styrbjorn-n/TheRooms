<?php

$mysqli = require __DIR__ . "./database.php";

$sql = sprintf(
  "SELECT user_id, name, users.room, northen_room, southern_room, western_room, eastern_room, message
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

return array(
  $roomData["message"],
  array(
    "north" => $roomData["northen_room"],
    "south" =>$roomData["southern_room"],
    "west" => $roomData["western_room"],
    "east" => $roomData["eastern_room"]
  )
);
