<?php
session_start();
require 'config.php';

// Ensure the user is logged in and is a moderator
if ($_SESSION['role'] !== 'moderator' && $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Fetch unresolved issues
$issuesQuery = "SELECT * FROM publicissues WHERE status = 'under investigation'";
$issuesResult = $conn->query($issuesQuery);

// Handle issue resolution
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $issue_id = $_POST['issue_id'];
    $resolution_comment = $_POST['resolution_comment'];

    // Update the issue status to 'resolved'
    $resolveQuery = $conn->prepare("UPDATE publicissues SET status = 'resolved', resolution_comment = ?, resolved_by = ?, resolved_at = NOW() WHERE user_id = ?");
    $resolveQuery->bind_param("sii", $resolution_comment, $_SESSION['user_id'], $issue_id);
    $resolveQuery->execute();

    // Redirect back to the same page to prevent form resubmission
    header("Location: resolve_issues.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resolve Issues</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <header>
        <h1>Unresolved Issues</h1>
    </header>

    <nav class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>

    <main>
        <?php if ($issuesResult->num_rows > 0): ?>
            <h2>Click an issue to resolve it:</h2>
            <?php while ($issue = $issuesResult->fetch_assoc()): ?>
                <div class="issue">
                    <h3><?= htmlspecialchars($issue['issue_title']); ?></h3>
                    <p><?= htmlspecialchars($issue['issue_description']); ?></p>
                    <p><strong>Reported By:</strong> User ID <?= $issue['user_id']; ?></p>
                    <p><strong>Reported At:</strong> <?= $issue['report_date']; ?></p>

                    <!-- Form to resolve the issue -->
                    <form method="POST" action="resolve_issues.php">
                        <label for="resolution_comment">Resolution Comment (optional):</label><br>
                        <textarea id="resolution_comment" name="resolution_comment" rows="4"></textarea><br>
                        <input type="hidden" name="user_id" value="<?= $issue['issue_id']; ?>">
                        <input type="submit" value="Resolve Issue">
                    </form>
                </div>
                <hr>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No unresolved issues at the moment.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Citizen Participation Software</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
