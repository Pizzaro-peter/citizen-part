<?php
session_start();
require 'config.php'; // Database connection

// Check if the user is logged in and has the 'official' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'official') {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $created_by = $_SESSION['user_id'];

    // Validate input
    if (!empty($title) && !empty($description) && !empty($start_date) && !empty($end_date)) {
        // Insert project into the database
        $sql = "INSERT INTO Projects (title, description, start_date, end_date, created_by) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $title, $description, $start_date, $end_date, $created_by);

        if ($stmt->execute()) {
            echo "Project created successfully! <a href='dashboard.php'>Go back to Dashboard</a>";
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
