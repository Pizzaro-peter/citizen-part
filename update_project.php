<?php
session_start();
require 'config.php'; // Database connection

// Check if the user is logged in and has the 'official' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'official') {
    header("Location: login.html");
    exit();
}

// Get project details
if (isset($_GET['id'])) {
    $project_id = $_GET['id'];
    
    $sql = "SELECT * FROM Projects WHERE project_id = ? AND created_by = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $project_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $project = $result->fetch_assoc();
    } else {
        echo "Project not found or you do not have permission to edit this project.";
        exit();
    }
} else {
    header("Location: manage_projects.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    
    // Update project status
    $sql = "UPDATE Projects SET status = ? WHERE project_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $project_id);

    if ($stmt->execute()) {
        echo "Project status updated successfully! <a href='manage_projects.php'>Go back to Manage Projects</a>";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Project Status</title>
    <link rel="stylesheet" href="./style/style.css"> 
</head>
<body>
    <header>
        <h1>Update Project Status</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Project: <?php echo htmlspecialchars($project['title']); ?></h2>
        <form action="update_project.php?id=<?php echo $project_id; ?>" method="POST">
            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="in progress" <?php if ($project['status'] == 'in progress') echo 'selected'; ?>>In Progress</option>
                <option value="completed" <?php if ($project['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                <option value="delayed" <?php if ($project['status'] == 'delayed') echo 'selected'; ?>>Delayed</option>
            </select>

            <button type="submit">Update Status</button>
        </form>
    </main>
</body>
</html>
