<?php
session_start();
require 'config.php';

// Check if the user is an admin (or has the role to create projects)
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin';

// Handle project creation form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && $is_admin) {
    $project_name = $_POST['project_name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];

    // Insert the new project into the database
    $sql = "INSERT INTO projects (project_name, description, start_date, end_date, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $project_name, $description, $start_date, $end_date, $status);

    if ($stmt->execute()) {
        $success = "Project created successfully!";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all projects from the database
$sql = "SELECT * FROM projects ORDER BY start_date DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
    <header>
        <h1>Projects</h1>
    </header>

    <nav class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="view_notifications.php">Notifications</a>
        <a href="track_projects.php">Track Projects</a>
        <a href="logout.php">Logout</a>
    </nav>

    <main>
        <?php if (isset($error)): ?>
            <div class="alert error"><?= $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert success"><?= $success; ?></div>
        <?php endif; ?>

        <!-- Show the project creation form only to admins -->
        <?php if ($is_admin): ?>
            <h2>Create a New Project</h2>
            <form method="POST">
                <label for="project_name">Project Name:</label>
                <input type="text" name="project_name" id="project_name" required>

                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>

                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" required>

                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" id="end_date" required>

                <label for="status">Status:</label>
                <select name="status" id="status">
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                </select>

                <input type="submit" value="Create Project">
            </form>
        <?php endif; ?>

        <h2>Ongoing and Completed Projects</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($project = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($project['project_name']); ?></td>
                            <td><?= htmlspecialchars($project['description']); ?></td>
                            <td><?= htmlspecialchars($project['start_date']); ?></td>
                            <td><?= htmlspecialchars($project['end_date']); ?></td>
                            <td><?= htmlspecialchars($project['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No projects found.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Citizen Participation Software</p>
    </footer>
</body>
</html>
