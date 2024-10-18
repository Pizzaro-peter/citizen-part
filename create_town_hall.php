<?php
session_start();
if ($_SESSION['role'] !== 'official' && $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $meeting_date = $_POST['meeting_date'];
    $location_url = $_POST['location_url'];

    // Insert new town hall meeting into the database
    $stmt = $conn->prepare("INSERT INTO townhallmeetings (title, description, meeting_date, location_url, created_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $title, $description, $meeting_date, $location_url, $_SESSION['user_id']);
    $stmt->execute();

    // Notify users about the new town hall meeting
    $users = $conn->query("SELECT user_id FROM users");
    while ($user = $users->fetch_assoc()) {
        sendNotification($user['user_id'], "A new town hall meeting has been scheduled: " . $title);
    }

    echo "Town hall meeting created successfully!";
    header("Location: manage_town_halls.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Town Hall Meeting</title>
    <link rel="stylesheet" href="./style/style.css"> <!-- Assuming you have a CSS file -->
</head>
<body>
    <header>
        <h1>Create Town Hall Meeting</h1>
    </header>

    <nav class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="view_notifications.php">Notifications</a>
        <a href="logout.php">Logout</a>
    </nav>

    <main>
        <!-- Form for creating a new town hall meeting -->
        <form method="POST" action="townhall.php">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" required><br><br>

            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" required></textarea><br><br>

            <label for="meeting_date">Meeting Date and Time:</label><br>
            <input type="datetime-local" id="meeting_date" name="meeting_date" required><br><br>

            <label for="location_url">Location/URL:</label><br>
            <input type="text" id="location_url" name="location_url" placeholder="Physical Address or Meeting URL" required><br><br>
    <input type="url" id="location_url" name="location_url" placeholder="https://meet.google.com/xyz" required><br><br>

            <input type="submit" value="Create Town Hall Meeting">
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Citizen Participation Software</p>
    </footer>
</body>
</html>