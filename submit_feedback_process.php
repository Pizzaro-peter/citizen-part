<?php
session_start();
require 'config.php'; // Database connection

// Check if the user is logged in and has the 'citizen' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'citizen') {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $user_id = $_SESSION['user_id'];

    // Validate input (you can add more validation as needed)
    if (!empty($title) && !empty($description)) {
        // Insert feedback into the database
        $sql = "INSERT INTO Feedback (user_id, title, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $user_id, $title, $description);

        if ($stmt->execute()) {
            echo "Feedback submitted successfully! <a href='dashboard.php'>Go back to Dashboard</a>";
        } else {
            echo "Error: " . $conn->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Please fill in all required fields.";
    }
}
?>
