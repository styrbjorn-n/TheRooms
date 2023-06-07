<?php

session_start();

if (empty($_POST['name'])) {
  die('A name is required');
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  die('A valid Email is required');
}
$mysqli = require __DIR__ . "/database.php";
$apiKey = require __DIR__ . "/apiKeyGenerator.php";

$sql = "INSERT INTO users (name, email, apiKey)
        VALUES (?, ?, ?)";

$stmt = $mysqli->stmt_init();

if (!$stmt->prepare($sql)) {
  die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("sss", $_POST['name'], $_POST['email'], $apiKey);

try {
  if ($stmt->execute()) {
    $_SESSION['email'] = $_POST["email"];
    $_SESSION['apiKey'] = $apiKey;
    header("location: /PAGES/signupSuccess.php");
    exit();
  } else {
    throw new Exception($mysqli->error . " " . $mysqli->errno);
  }
} catch (Exception $e) {
  die($e->getMessage());
}
