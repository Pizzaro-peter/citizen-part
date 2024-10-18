<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'];
$notifications = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY sent_date DESC");
$notifications->bind_param("i", $user_id);
$notifications->execute();
$result = $notifications->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Notifications</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <h1>Your Notifications</h1>
    <ul>
        <?php while ($notification = $result->fetch_assoc()): ?>
            <li>
                <p><?php echo htmlspecialchars($notification['notification_message']); ?></p>
                <small><?php echo date('Y-m-d H:i:s', strtotime($notification['sent_date'])); ?></small>
            </li>
        <?php endwhile; ?>
    </ul>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
