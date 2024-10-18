<?php
session_start();
include('config.php');

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Fetch active polls from the database
$stmt = $conn->prepare("SELECT * FROM polls WHERE status = 'active' ORDER BY created_by DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Polls</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="dashboard.php" class="logo">Citizen Participation</a>
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="polls.php">Polls</a></li>
                <li><a href="town_halls.php">Town Halls</a></li>
                <li><a href="notifications.php">Notifications</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>Active Polls</h2>
        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($poll = $result->fetch_assoc()): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($poll['question']); ?></h3>
                        <p>Options:</p>
                        <ul>
                            <?php 
                            // Assuming options are stored in a separate table related to polls
                            $poll_id = $poll['id'];
                            $options_stmt = $conn->prepare("SELECT * FROM polls WHERE poll_id = ?");
                            $options_stmt->bind_param("i", $poll_id);
                            $options_stmt->execute();
                            $options_result = $options_stmt->get_result();
                            
                            while ($option = $options_result->fetch_assoc()): ?>
                                <li><?php echo htmlspecialchars($option['option_text']); ?></li>
                            <?php endwhile; ?>
                        </ul>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No active polls at this time.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 Citizen Participation Platform</p>
    </footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
