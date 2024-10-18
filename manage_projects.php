<?php
session_start();
require 'config.php'; // Database connection

// Check if the user is logged in and has the 'official' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'official') {
    header("Location: login.html");
    exit();
}

// Fetch projects created by the logged-in official
$official_id = $_SESSION['user_id'];
$sql = "SELECT * FROM projects WHERE created_by = ? ORDER BY created_by DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $official_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects</title>
    <link rel="stylesheet" href="./style/style.css"> 
</head>
<body>
    <header>
        <h1>Manage Your Projects</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Projects Created by You</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo $row['start_date']; ?></td>
                            <td><?php echo $row['end_date']; ?></td>
                            <td><?php echo ucfirst($row['status']); ?></td>
                            <td>
                                <a href="update_project.php?id=<?php echo $row['project_id']; ?>">Update</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No projects found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
