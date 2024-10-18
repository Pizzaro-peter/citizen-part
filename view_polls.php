<?php
session_start();
require 'config.php';

// Fetch all polls
$result = $conn->query("SELECT * FROM polls WHERE closing_date > NOW() OR created_by = ".$_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Polls</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <h1>Available Polls</h1>
    <ul>
        <?php while ($poll = $result->fetch_assoc()): ?>
            <li>
                <h2><?php echo htmlspecialchars($poll['title']); ?></h2>
                <p><?php echo htmlspecialchars($poll['description']); ?></p>
                <form method="POST" action="vote.php">
                    <input type="hidden" name="poll_id" value="<?php echo $poll['poll_id']; ?>">
                    <input type="radio" name="vote_option" value="Yes" required> Yes
                    <input type="radio" name="vote_option" value="No"> No
                    <input type="radio" name="vote_option" value="Abstain"> Abstain
                    <input type="submit" value="Vote">
                </form>
                <a href="poll_results.php?poll_id=<?php echo $poll['poll_id']; ?>">View Results</a>
                <a href="edit_poll.php?poll_id=<?php echo $poll['poll_id']; ?>">Edit Poll</a>
                <a href="delete_poll.php?poll_id=<?php echo $poll['poll_id']; ?>" onclick="return confirm('Are you sure you want to delete this poll?');">Delete Poll</a>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
