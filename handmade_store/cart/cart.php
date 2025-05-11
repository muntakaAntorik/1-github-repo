<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Add to cart logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $check = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
    $check->execute([$user_id, $product_id]);

    if ($check->rowCount() > 0) {
        $update = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
        $update->execute([$quantity, $user_id, $product_id]);
    } else {
        $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $insert->execute([$user_id, $product_id, $quantity]);
    }

    header("Location: cart.php");
    exit();
}

// Remove item from cart
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$remove_id, $user_id]);
    header("Location: cart.php");
    exit();
}

// Get all items in the cart
$stmt = $conn->prepare("
    SELECT cart.id as cart_id, products.name, products.price, products.image, cart.quantity
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Cart</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<div class="dashboard-container">
    <?php include '../includes/sidebar.php'; ?>
    
    <div class="main-content">
        <h2>Shopping Cart</h2>

        <?php if (count($items) > 0): ?>
            <table>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($items as $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                <tr>
                    <td><img src="../assets/images/<?= $item['image']; ?>" height="50"></td>
                    <td><?= $item['name']; ?></td>
                    <td>৳ <?= $item['price']; ?></td>
                    <td><?= $item['quantity']; ?></td>
                    <td>৳ <?= $subtotal; ?></td>
                    <td><a href="cart.php?remove=<?= $item['cart_id']; ?>">Remove</a></td>
                </tr>
                <?php endforeach; ?>
            </table>

            <h3>Total: ৳ <?= $total; ?></h3>

            <!-- ✅ Checkout Button -->
            <a href="checkout.php"><button>Proceed to Checkout</button></a>

        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
