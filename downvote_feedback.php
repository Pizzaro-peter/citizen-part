<?php
session_start();
require 'config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if (isset($_GET['id'])) {
    $feedback_id = $_GET['id'];
    
    // Update the upvote count in the database
    $sql = "UPDATE feedback SET upvotes = upvotes + 1 WHERE suggestion_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $feedback_id);

    if ($stmt->execute()) {
        header("Location: view_feedback.php");
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
