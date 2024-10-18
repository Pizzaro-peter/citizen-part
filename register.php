<?php
// Display errors for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'config.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        // If email exists, show a friendly error message
        echo "This email is already registered. Please use a different email.";
    } else {
        // Proceed to insert into database
        $sql = "INSERT INTO users (first_name, last_name, email, password_hash, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $password_hash, $role);

        if ($stmt->execute()) {
            // On success, redirect to login page
            echo "Registration successful!";
            header("Location: login.html");
            exit();
        } else {
            // Log the error (optionally) and display a user-friendly message
            error_log("Database error: " . $stmt->error); // Logs the actual error for debugging
            echo "There was an error during registration. Please try again.";
        }

        $stmt->close();
    }

    $checkEmail->close();
    $conn->close();
}
?>
