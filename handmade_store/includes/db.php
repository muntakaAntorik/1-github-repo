<?php
$host = 'localhost';
$db   = 'handmade_store';  // your database name
$user = 'root';            // default XAMPP user
$pass = '';                // default XAMPP password (empty)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $conn = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
?>
