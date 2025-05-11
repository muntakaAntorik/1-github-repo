<?php
session_start();
include('../includes/db.php');

if ($_SESSION['user_role'] !== 'admin') {
    echo "Access denied!";
    exit();
}


if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

header("Location: products.php");
exit();
