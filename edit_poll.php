<?php
session_start();
if ($_SESSION['role'] !== 'official') {
    header("Location: dashboard.php");
    exit();
}
require 'db_connection.php';

if (!isset($_GET['poll_id'])) {
    echo "No poll selected.";
    exit();
}

$poll_id = intval($_GET['poll_id']);

// Fetch poll details
$pollResult = $conn->prepare("SELECT * FROM Polls WHERE poll_id = ?");
$pollResult->bind_param("i", $poll_id);
$pollResult->execute();
$poll = $pollResult->get_result()->fetch_assoc();

if (!$poll) {
    echo "Poll not found.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $closing_date = $_POST['closing_date'];

    // Update poll in the database
    $stmt = $conn->prepare("UPDATE Polls SET title = ?, description = ?, closing_date = ? WHERE poll_id = ?");
    $stmt->bind_param("ssii", $title, $description, $closing_date, $poll_id);
    $stmt->execute();

    echo "Poll updated successfully!";
    header("Location: view_polls.php"); // Redirect after update
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Poll</title>
    <link rel="stylesheet" href="./style/style.css"> 
</head>
<body>
    <h1>Edit Poll</h1>
    <form method="POST">
        <label for="title">Poll Title:</label><br>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($poll['title']); ?>" required><br>
        
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($poll['description']); ?></textarea><br>
        
        <label for="closing_date">Closing Date:</label><br>
        <input type="datetime-local" id="closing_date" name="closing_date" value="<?php echo date('Y-m-d\TH:i', strtotime($poll['closing_date'])); ?>" required><br>
        
        <input type="submit" value="Update Poll">
    </form>
    <a href="view_polls.php">Cancel</a>
</body>
</html>
