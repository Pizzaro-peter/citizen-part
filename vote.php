<?php
session_start();
require 'config.php';

// Initialize variables
$result = null;
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['poll_id']) && isset($_POST['vote_option'])) {
        $poll_id = $_POST['poll_id'];
        $user_id = $_SESSION['user_id'];
        $vote_option = $_POST['vote_option'];

        // Check if the user has already voted
        $checkVote = $conn->prepare("SELECT * FROM votes WHERE poll_id = ? AND user_id = ?");
        $checkVote->bind_param("ii", $poll_id, $user_id);
        $checkVote->execute();
        $checkVoteResult = $checkVote->get_result();

        if ($checkVoteResult->num_rows > 0) {
            $error = "You have already voted in this poll.";
        } else {
            // Insert vote into the database
            $stmt = $conn->prepare("INSERT INTO votes (poll_id, user_id, vote_option) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $poll_id, $user_id, $vote_option);
            if ($stmt->execute()) {
                $success = "Your vote has been recorded!";
            } else {
                $error = "There was an error recording your vote.";
            }
        }
    } else {
        $error = "Please select a poll and vote option.";
    }
}

// Fetch active polls to display
$pollsQuery = "SELECT * FROM polls WHERE status = 'active'"; // Fetch only active polls
$pollsStmt = $conn->prepare($pollsQuery);
$pollsStmt->execute();
$result = $pollsStmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <header>
        <h1>Cast Your Vote</h1>
    </header>

    <nav class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="view_notifications.php">Notifications</a>
        <a href="logout.php">Logout</a>
    </nav>

    <main>
        <?php if (!empty($error)): ?>
            <div class="alert error"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert success"><?= htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <h2>Available Polls</h2>
        <?php if ($result && $result->num_rows > 0): ?>
            <form method="POST">
                <?php while ($poll = $result->fetch_assoc()): ?>
                    <div class="poll">
                        <h3><?= htmlspecialchars($poll['question']); ?></h3>
                        <?php
                        // Fetch options for each poll (from poll_options table)
                        $optionsQuery = "SELECT * FROM polls WHERE poll_id = ?";
                        $optionsStmt = $conn->prepare($optionsQuery);
                        $optionsStmt->bind_param("i", $poll['id']);
                        $optionsStmt->execute();
                        $optionsResult = $optionsStmt->get_result();
                        ?>

                        <?php while ($option = $optionsResult->fetch_assoc()): ?>
                            <label>
                                <input type="radio" name="vote_option" value="<?= htmlspecialchars($option['option_text']); ?>" required>
                                <?= htmlspecialchars($option['option_text']); ?>
                            </label><br>
                        <?php endwhile; ?>

                        <input type="hidden" name="poll_id" value="<?= $poll['id']; ?>">
                        <input type="submit" value="Vote">
                    </div>
                <?php endwhile; ?>
            </form>
        <?php else: ?>
            <p>No active polls available at this time.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Citizen Participation Software</p>
    </footer>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
