<?php
if (empty($_POST['name'])) {
  die('A name is required');
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  die('A valid Email is required');
}
$mysqli = require __DIR__ . "/database.php";

$sql = "INSERT INTO users (name, email)
        VALUES (?, ?)";

$stmt = $mysqli->stmt_init();

if (!$stmt->prepare($sql)) {
  die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("ss", $_POST['name'], $_POST['email']);

$stmt->execute();

echo ("Signup successful");
