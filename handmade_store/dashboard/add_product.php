<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $user_id = $_SESSION['user_id'];

    // Image Upload
    $image = $_FILES['image']['name'];
    $target = "../assets/images/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO products (user_id, name, description, price, image) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $name, $description, $price, $image])) {
        echo "Product added!";
        header("Refresh:1; url=dashboard.php");
    } else {
        echo "Error adding product!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<div class="dashboard-container">
    <?php include '../includes/sidebar.php'; ?>
    
    <div class="main-content">
        <h2>Add New Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Product Name:</label>
            <input type="text" name="name" required>

            <label>Description:</label>
            <textarea name="description" required></textarea>

            <label>Price:</label>
            <input type="number" name="price" step="0.01" required>

            <label>Image:</label>
            <input type="file" name="image" required>

            <button type="submit">Add Product</button>
        </form>
    </div>
</div>

</body>
</html>
