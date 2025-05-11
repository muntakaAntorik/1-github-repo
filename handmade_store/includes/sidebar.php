<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <h2>Handmade Store</h2>
    <ul>
        <li><a href="../dashboard/dashboard.php" class="<?= $current == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
        <li><a href="../dashboard/profile.php" class="<?= $current == 'profile.php' ? 'active' : '' ?>">Profile</a></li>
        <li><a href="../dashboard/add_product.php" class="<?= $current == 'add_product.php' ? 'active' : '' ?>">Add Product</a></li>
        <li><a href="../cart/cart.php" class="<?= $current == 'cart.php' ? 'active' : '' ?>">Cart</a></li>
        <li><a href="../dashboard/products.php">Products</a></li>


        <li><a href="../logout.php">Logout</a></li>
    </ul>
</div>
