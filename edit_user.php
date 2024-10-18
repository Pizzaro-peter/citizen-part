<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
require 'db_connection.php';

if (!isset($_GET['user_id'])) {
    echo "No user selected.";
    exit();
}

$user_id = intval($_GET['user_id']);

// Fetch user details
$userResult = $conn->prepare("SELECT * FROM Users WHERE user_id = ?");
$userResult->bind_param("i", $user_id);
$userResult->execute();
$user = $userResult->get_result()->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];

    // Update user role in the database
    $stmt = $conn->prepare("UPDATE Users SET role = ? WHERE user_id = ?");
    $stmt->bind_param("si", $role, $user_id);
    $stmt->execute();

    echo "User role updated successfully!";
    header("Location: view_users.php"); // Redirect after update
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="./style/style.css"> 
</head>
<body>
    <h1>Edit User Role</h1>
    <form method="POST">
        <label for="role">User Role:</label><br>
        <select id="role" name="role" required>
            <option value="citizen" <?php if ($user['role'] === 'citizen') echo 'selected'; ?>>Citizen</option>
            <option value="official" <?php if ($user['role'] === 'official') echo 'selected'; ?>>Official</option>
            <option value="moderator" <?php if ($user['role'] === 'moderator') echo 'selected'; ?>>Moderator</option>
            <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
        </select><br>
        <input type="submit" value="Update User Role">
    </form>
    <a href="view_users.php">Cancel</a>
</body>
</html>
