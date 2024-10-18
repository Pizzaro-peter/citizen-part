<?php
session_start();
require 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$sql = "SELECT first_name, last_name, email, role FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update user profile
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    
    // Prepare and execute update statement
    $update_sql = "UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id);
    
    if ($update_stmt->execute()) {
        echo "<div class='alert success'>Profile updated successfully!</div>";
        // Refresh the user data
        $user['first_name'] = $first_name;
        $user['last_name'] = $last_name;
        $user['email'] = $email;
    } else {
        echo "<div class='alert error'>Error updating profile: " . $update_stmt->error . "</div>";
    }
    
    $update_stmt->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="./style/style.css"> 
</head>
<body>
    <header>
        <h1>User Profile</h1>
    </header>

    <nav class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="view_notifications.php">Notifications</a>
        <a href="logout.php">Logout</a>
    </nav>

    <main>
        <h2>Profile Information</h2>
        <form method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']); ?>" required>
            
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']); ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
            
            <input type="submit" value="Update Profile">
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Citizen Participation Software</p>
    </footer>
</body>
</html>
