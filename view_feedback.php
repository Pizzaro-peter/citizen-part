<?php
session_start();
require 'config.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Fetch feedback submissions from the database
$sql = "SELECT f.suggestion_id, f.title, f.description, f.submission_date, f.upvotes, f.downvotes, u.first_name, u.last_name 
        FROM feedback f 
        JOIN users u ON f.user_id = u.user_id 
        WHERE f.status = 'open'
        ORDER BY f.submission_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link rel="stylesheet" href="./style/style.css"> 
</head>
<body>
    <header>
        <h1>Feedback and Suggestions</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Citizen Feedback</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Submitted By</th>
                    <th>Upvotes</th>
                    <th>Downvotes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']) . " " . htmlspecialchars($row['last_name']); ?></td>
                            <td><?php echo $row['upvotes']; ?></td>
                            <td><?php echo $row['downvotes']; ?></td>
                            <td>
                                <a href="upvote_feedback.php?id=<?php echo $row['suggestion_id']; ?>">Upvote</a> | 
                                <a href="downvote_feedback.php?id=<?php echo $row['suggestion_id']; ?>">Downvote</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No feedback submitted yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>

<?php
$conn->close();
?>
