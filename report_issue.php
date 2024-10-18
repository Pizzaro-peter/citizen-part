<?php
session_start();
require 'config.php';

// Initialize variables
$error = "";
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $issue_description = $_POST['issue_description'];

    // Validate input
    if (empty($issue_description)) {
        $error = "Please provide a description of the issue.";
    } else {
        // Insert the issue into the database
        $stmt = $conn->prepare("INSERT INTO publicissues (user_id, issue_description, status) VALUES (?, ?, 'pending')");
        $stmt->bind_param("is", $user_id, $issue_description);

        if ($stmt->execute()) {
            $success = "Your issue has been reported successfully!";
        } else {
            $error = "There was an error reporting your issue. Please try again.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Issue</title>
    <link rel="stylesheet" href="./style/style.css"> 
</head>
<body>
    <header>
        <h1>Report an Issue</h1>
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

        <form method="POST">
            <label for="issue_description">Issue Description:</label>
            <textarea id="issue_description" name="issue_description" rows="4" required></textarea>
            <input type="submit" value="Report Issue">
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Citizen Participation Software</p>
    </footer>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
