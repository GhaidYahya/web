<?php
function db_connect() {
  $host = 'localhost'; 
  $dbname = 'c139_ghaid_yahya';
  $username = 'c139_ghaid_yahya';
  $password_db = 'web12345$';

  try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  } catch (PDOException $e) {
    echo 'Failed to connect to the database: ' . $e->getMessage();
    exit();
  }
}
?>
