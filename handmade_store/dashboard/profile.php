<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Update profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");
        $update->execute([$name, $password, $user_id]);
    } else {
        $update = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
        $update->execute([$name, $user_id]);
    }
    echo "Profile updated!";
    header("Refresh:1"); // Refresh page after 1 second
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<div class="dashboard-container">
    <?php include '../includes/sidebar.php'; ?>
    
    <div class="main-content">
        <h2>My Profile</h2>
        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" value="<?= $user['name']; ?>" required>

            <label>Email:</label>
            <input type="email" value="<?= $user['email']; ?>" disabled>

            <label>New Password (optional):</label>
            <input type="password" name="password" placeholder="Enter new password">

            <button type="submit">Update Profile</button>
        </form>
    </div>
</div>

</body>
</html>
