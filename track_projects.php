<?php
session_start();
require 'config.php';

// Initialize variables
$error = "";
$projects = [];

// Fetch projects from the database
$projectsQuery = "SELECT * FROM projects"; // Adjust the query if you have a specific condition (e.g., active projects)
$projectsStmt = $conn->prepare($projectsQuery);
$projectsStmt->execute();
$result = $projectsStmt->get_result();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row; // Store projects in an array
    }
} else {
    $error = "No projects found.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Projects</title>
    <link rel="stylesheet" href="./style/style.css"> 
</head>
<body>
    <header>
        <h1>Track Community Projects</h1>
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

        <h2>Projects</h2>
        <?php if (!empty($projects)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $project): ?>
                        <tr>
                            <td><?= htmlspecialchars($project['title']); ?></td>
                            <td><?= htmlspecialchars($project['description']); ?></td>
                            <td><?= htmlspecialchars($project['status']); ?></td>
                            <td><?= htmlspecialchars($project['start_date']); ?></td>
                            <td><?= htmlspecialchars($project['end_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No projects available at this time.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Citizen Participation Software</p>
    </footer>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
