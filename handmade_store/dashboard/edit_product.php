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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    $sql = "UPDATE products SET name = ?, price = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$name, $price, $id]);

    header("Location: products.php");
    exit();
} else {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>
    <h2>Edit Product</h2>
    <form method="POST">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?= $product['name'] ?>" required><br><br>

        <label>Price:</label><br>
        <input type="number" name="price" step="0.01" min="0" value="<?= $product['price'] ?>" required>


        <button type="submit">Update</button>
    </form>
</body>
</html>
