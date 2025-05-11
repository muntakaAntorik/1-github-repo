<?php
session_start();
include('../includes/db.php');

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

// Fetch reviews
$reviewStmt = $conn->prepare("
    SELECT r.*, u.name 
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    WHERE r.product_id = ?
    ORDER BY r.created_at DESC
");
$reviewStmt->execute([$product_id]);
$reviews = $reviewStmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $product['name']; ?> - Details</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include('../includes/sidebar.php'); ?>

        <div class="main-content">
            <h2><?= $product['name']; ?></h2>
            <img src="../assets/images/<?= $product['image']; ?>" alt="<?= $product['name']; ?>" width="250">
            <p><strong>Price:</strong> ৳<?= $product['price']; ?></p>
            <p><?= $product['description']; ?></p>

            <!-- Review Form -->
            <h3>Leave a Review</h3>
            <form method="POST" action="submit_review.php">
                <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                <label>Rating (1-5):</label>
                <input type="number" name="rating" min="1" max="5" required><br><br>
                <label>Review:</label><br>
                <textarea name="review_text" rows="4" cols="50" required></textarea><br><br>
                <button type="submit">Submit Review</button>
            </form>

            <!-- Reviews List -->
            <h3>Reviews</h3>
            <?php if (count($reviews) > 0): ?>
                <?php foreach ($reviews as $r): ?>
                    <div class="review-box">
                        <strong><?= htmlspecialchars($r['name']); ?></strong>
                        <div>Rating: <?= $r['rating']; ?>/5</div>
                        <p><?= nl2br(htmlspecialchars($r['review_text'])); ?></p>
                        <hr>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No reviews yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
