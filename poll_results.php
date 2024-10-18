<?php
session_start();
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

// Fetch vote counts
$voteCounts = $conn->prepare("SELECT vote_option, COUNT(*) as count FROM votes WHERE poll_id = ? GROUP BY vote_option");
$voteCounts->bind_param("i", $poll_id);
$voteCounts->execute();
$votes = $voteCounts->get_result();

$voteResults = [];
while ($row = $votes->fetch_assoc()) {
    $voteResults[$row['vote_option']] = $row['count'];
}

// Default counts to 0 if not voted
$yesVotes = $voteResults['Yes'] ?? 0;
$noVotes = $voteResults['No'] ?? 0;
$abstainVotes = $voteResults['Abstain'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Poll Results: <?php echo htmlspecialchars($poll['title']); ?></title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <h1>Poll Results for "<?php echo htmlspecialchars($poll['title']); ?>"</h1>
    <p><?php echo htmlspecialchars($poll['description']); ?></p>

    <h2>Results:</h2>
    <ul>
        <li>Yes: <?php echo $yesVotes; ?></li>
        <li>No: <?php echo $noVotes; ?></li>
        <li>Abstain: <?php echo $abstainVotes; ?></li>
    </ul>

    <a href="view_polls.php">Back to Polls</a>
</body>
</html>
