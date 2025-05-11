<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    if ($rating < 1 || $rating > 5) {
        $_SESSION['error'] = "Invalid rating. Must be between 1 and 5.";
        header("Location: product_details.php?id=$product_id");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, review_text) VALUES (?, ?, ?, ?)");
    $stmt->execute([$product_id, $user_id, $rating, $review_text]);

    $_SESSION['success'] = "Review submitted successfully!";
    header("Location: product_details.php?id=$product_id");
    exit();
}
?>
