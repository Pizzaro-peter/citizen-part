<?php
session_start();
include('config.php');

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Fetch upcoming town hall meetings from the database
$stmt = $conn->prepare("SELECT * FROM town_halls WHERE date >= NOW() ORDER BY date ASC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Town Halls</title>
    <link rel="stylesheet" href="css/style.css">
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
        <h2>Upcoming Town Halls</h2>
        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($town_hall = $result->fetch_assoc()): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($town_hall['title']); ?></h3>
                        <p>Date: <?php echo htmlspecialchars($town_hall['date']); ?></p>
                        <p>Location: <?php echo htmlspecialchars($town_hall['location']); ?></p>
                        <p>Description: <?php echo htmlspecialchars($town_hall['description']); ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No upcoming town halls at this time.</p>
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
