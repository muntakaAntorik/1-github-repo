<?php
session_start();
require_once '../includes/db.php';

// Protect the dashboard (only logged-in users)
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Fetch products from the database
$stmt = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - Handmade Store</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include '../includes/sidebar.php'; ?>

    <div class="main-content">
        <h1>All Products</h1>
        <div class="product-grid">
            <?php
            // Loop through your products
            foreach ($products as $product) {
                echo '<div class="product-card">';
                echo '<img src="../assets/images/' . $product['image'] . '" alt="' . $product['name'] . '">';
                echo '<h3>' . $product['name'] . '</h3>';
                echo "৳" . $product['price'] ;
                echo '<a href="product_details.php?id=' . $product['id'] . '" class="btn-view">View</a>';

                
                if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                    echo '<a href="edit_product.php?id=' . $product['id'] . '" class="btn-edit">Edit</a>';
                    echo '<a href="delete_product.php?id=' . $product['id'] . '" class="btn-delete" onclick="return confirm(\'Are you sure?\')">Delete</a>';
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>


</body>
</html>

