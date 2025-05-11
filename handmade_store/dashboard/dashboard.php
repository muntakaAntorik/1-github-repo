<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Handmade Store</h2>
        <ul>
            <li><a href="products.php">Products</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="add_product.php">Add Product</a></li>
            <li><a href="../cart/cart.php">View Cart</a></li>
            <li><a href="../logout.php">Logout</a></li>
            
        </ul>
</div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>   Welcome to your Dashboard</h1>
        <div class="product-grid">
            <?php
            include '../includes/db.php';
            $stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
            while ($product = $stmt->fetch()) {
                echo "
                <div class='product-card'>
                    <img src='../assets/images/{$product['image']}' alt='{$product['name']}'>
                    <h3>{$product['name']}</h3>
                    <p>৳ {$product['price']}</p>
                    <form method='POST' action='../cart/cart.php'>
                        <input type='hidden' name='product_id' value='{$product['id']}'>
                        <input type='number' name='quantity' value='1' min='1' style='width: 50px;'>
                        <button type='submit' name='add_to_cart'>Add to Cart</button>
                    </form>
                </div>";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>
