<?php

require __DIR__ . "/../vendor/autoload.php";

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . "./../");
$dotenv->load();

$host = $_ENV['HOST'];
$dbname = $_ENV['DBNAME'];
$username = $_ENV['USERNAME'];
$password = $_ENV['PASSWORD'];

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_errno) {
  die("Connection error: " . $mysqli->connect_errno);
}

return $mysqli;
?>
<a href="./../vendor/autoload.php"></a>