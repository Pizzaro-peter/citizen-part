<?php
session_start();
if ($_SESSION['role'] !== 'official' && $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
require 'config.php';

// Fetch all polls
$result = $conn->query("SELECT * FROM polls");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Polls</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <h1>Manage Polls</h1>
    <h2>Create New Poll</h2>
    <form method="POST" action="create_poll.php">
        <label for="title">Poll Title:</label><br>
        <input type="text" id="title" name="title" required><br>
        <label for="description">Poll Description:</label><br>
        <textarea id="description" name="description" required></textarea><br>
        <input type="submit" value="Create Poll">
    </form>

    <h2>Existing Polls</h2>
    <table>
        <tr>
            <th>Poll ID</th>
            <th>Title</th>
            <th>Actions</th>
        </tr>
        <?php while ($poll = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $poll['poll_id']; ?></td>
                <td><?php echo htmlspecialchars($poll['title']); ?></td>
                <td>
                    <a href="view_poll_results.php?poll_id=<?php echo $poll['poll_id']; ?>">View Results</a>
                    <a href="delete_poll.php?poll_id=<?php echo $poll['poll_id']; ?>" onclick="return confirm('Are you sure you want to delete this poll?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
