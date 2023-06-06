<?php 

$host = 'localhost';
$dbname = 'the_rooms_test';
$username = 'root';
$password = '89188d84D%&';

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_errno) {
  die("Connection error: " . $mysqli->connect_errno);
}

return $mysqli;
?>