<?php
session_start();
require 'citizen_participation_db';

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM notifications WHERE user_id = $user_id ORDER BY timestamp DESC");
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
    <table>
        <tr>
            <th>Message</th>
            <th>Time</th>
        </tr>
        <?php while ($notification = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($notification['message']); ?></td>
                <td><?php echo htmlspecialchars($notification['timestamp']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
