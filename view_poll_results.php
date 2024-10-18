<?php
session_start();
if ($_SESSION['role'] !== 'official' && $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
require 'config.php';

if (!isset($_GET['poll_id'])) {
    echo "No poll selected.";
    exit();
}

$poll_id = intval($_GET['poll_id']);

// Fetch poll details
$pollResult = $conn->prepare("SELECT * FROM polls WHERE poll_id = ?");
$pollResult->bind_param("i", $poll_id);
$pollResult->execute();
$poll = $pollResult->get_result()->fetch_assoc();

if (!$poll) {
    echo "Poll not found.";
    exit();
}

// Fetch voting results
$votingResults = $conn->query("SELECT vote_option, COUNT(*) as count FROM votes WHERE poll_id = $poll_id GROUP BY vote_option");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Poll Results</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <h1>Results for Poll: <?php echo htmlspecialchars($poll['title']); ?></h1>
    <table>
        <tr>
            <th>Vote Option</th>
            <th>Count</th>
        </tr>
        <?php while ($result = $votingResults->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($result['vote_option']); ?></td>
                <td><?php echo $result['count']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="manage_polls.php">Back to Manage Polls</a>
</body>
</html>
