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

// Delete the user
$stmt = $conn->prepare("DELETE FROM Users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

echo "User deleted successfully!";
header("Location: view_users.php");
?>
