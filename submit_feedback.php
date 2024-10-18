<?php
session_start();

// Check if the user is logged in and has the 'citizen' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'citizen') {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
    <link rel="stylesheet" href="./style/style.css"> 
</head>
<body>
    <header>
        <h1>Submit Feedback or Suggestion</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Submit Feedback</h2>
        <form action="submit_feedback_process.php" method="POST">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="5" required></textarea>

            <button type="submit">Submit Feedback</button>
        </form>
    </main>
</body>
</html>
