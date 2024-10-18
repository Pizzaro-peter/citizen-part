<?php
session_start();
if ($_SESSION['role'] !== 'official' && $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
require 'config.php';

// Fetch all town hall meetings
$result = $conn->query("SELECT * FROM townhallmeetings");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Town Halls</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <h1>Manage Town Hall Meetings</h1>
    <h2>Create New Meeting</h2>
    <form method="POST" action="create_town_hall.php">
    <label for="title">Meeting Title:</label><br>
    <input type="text" id="title" name="title" required><br><br>
    
    <label for="description">Meeting Description:</label><br>
    <textarea id="description" name="description" required></textarea><br><br>
    
    <label for="meeting_date">Meeting Date:</label><br>
    <input type="datetime-local" id="meeting_date" name="meeting_date" required><br><br>
    
    <label for="location_url">Meeting Location URL (e.g., Google Meet link):</label><br>
    <input type="url" id="location_url" name="location_url" placeholder="https://meet.google.com/xyz" required><br><br>
    
    <input type="submit" value="Create Meeting">
</form>


    <h2>Existing Meetings</h2>
    <table>
        <tr>
            <th>Meeting ID</th>
            <th>Title</th>
            <th>Meeting Date</th>
            <th>Actions</th>
        </tr>
        <?php while ($meeting = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $meeting['meeting_id']; ?></td>
                <td><?php echo htmlspecialchars($meeting['title']); ?></td>
                <td><?php echo htmlspecialchars($meeting['meeting_date']); ?></td>
                <td>
                    <a href="delete_town_hall.php?meeting_id=<?php echo $meeting['meeting_id']; ?>" onclick="return confirm('Are you sure you want to delete this meeting?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
