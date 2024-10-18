<?php
session_start();
if ($_SESSION['role'] !== 'official' && $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
require 'config.php';
require 'send_notification.php'; // Include the notification sending functions

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $closingDate = $_POST['closing_date'];

    // Insert new poll into the database
    $stmt = $conn->prepare("INSERT INTO polls (title, description, creation_date, closing_date, created_by) VALUES (?, ?, NOW(), ?, ?)");
    
    // Bind parameters: title (string), description (string), closing_date (datetime), created_by (int)
    $stmt->bind_param("sssi", $title, $description, $closingDate, $_SESSION['user_id']);

    // Execute and check for errors
    if ($stmt->execute()) {
        // Notify users about the new poll
        $users = $conn->query("SELECT user_id FROM users");
        while ($user = $users->fetch_assoc()) {
            sendNotification($user['user_id'], "A new poll has been created: " . $title);
        }

        echo "Poll created successfully!";
        header("Location: manage_polls.php");
        exit(); // Ensure no further processing occurs after the redirect
    } else {
        echo "Error creating poll: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
    $conn->close(); // Close the database connection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Poll</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <form method="POST">
        <label for="title">Poll Title:</label><br>
        <input type="text" id="title" name="title" required><br>
        
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required></textarea><br>
        
        <label for="closing_date">Closing Date:</label><br>
        <input type="datetime-local" id="closing_date" name="closing_date" required><br>
        
        <input type="submit" value="Create Poll">
    </form>
</body>
</html>
