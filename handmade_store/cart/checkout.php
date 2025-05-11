<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pay_now'])) {
    $payment_method = $_POST['payment_method'];

    $stmt = $conn->prepare("
        SELECT cart.product_id, cart.quantity, products.price
        FROM cart
        JOIN products ON cart.product_id = products.id
        WHERE cart.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll();

    if (count($items) == 0) {
        echo "Cart is empty.";
        exit();
    }

    // Calculate total
    $total = 0;
    foreach ($items as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Insert order
    $order_stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method) VALUES (?, ?, ?)");
    $order_stmt->execute([$user_id, $total, $payment_method]);
    
    $order_id = $conn->lastInsertId();

    // Insert order items
    foreach ($items as $item) {
        $oi_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $oi_stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
    }

    // Clear cart
    $clear = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $clear->execute([$user_id]);

    // Show confirmation
    echo "<div class='main-content'>";
    echo "<h2>✅ Payment Successful via <strong>$payment_method</strong></h2>";
    echo "<p>Your order ID is <strong>#{$order_id}</strong></p>";
    echo "<p>Total Paid: <strong>৳{$total}</strong></p>";
    echo "<a href='../dashboard/dashboard.php'><button>Back to Dashboard</button></a>";
    echo "</div>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<div class="dashboard-container">
    <?php include '../includes/sidebar.php'; ?>
    <div class="main-content">
        <h2>Select Payment Method</h2>
        <form method="POST">
            <label>
                <input type="radio" name="payment_method" value="Cash on Delivery" required>
                Cash on Delivery
            </label><br><br>
            <label>
                <input type="radio" name="payment_method" value="Bkash" required>
                Bkash
            </label><br><br>
            <label>
                <input type="radio" name="payment_method" value="Nagad" required>
                Nagad
            </label><br><br>
            <button type="submit" name="pay_now">Pay Now</button>
        </form>
    </div>
</div>
</body>
</html>
