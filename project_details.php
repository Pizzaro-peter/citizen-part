<?php
session_start();
require 'config.php'; // Database connection

// Check if the user is logged in and is a citizen
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'citizen') {
    header("Location: login.html");
    exit();
}

// Fetch project details
if (isset($_GET['id'])) {
    $project_id = $_GET['id'];

    $sql = "SELECT * FROM Projects WHERE project_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $project = $result->fetch_assoc();
    } else {
        echo "Project not found.";
        exit();
    }
} else {
    header("Location: view_projects.php");
    exit();
}

// Insert feedback into the Feedback table
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedback = $_POST['feedback'];
    $user_id = $_SESSION['user_id'];

    if (!empty($feedback)) {
        $sql = "INSERT INTO Feedback (user_id, project_id, feedback_text, feedback_date) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $user_id, $project_id, $feedback);

        if ($stmt->execute()) {
            echo "Feedback submitted successfully!";
        } else {
            echo "Error submitting feedback: " . $conn->error;
        }
    } else {
        echo "Please provide feedback.";
    }
}

// Fetch feedback for the project
$sql = "SELECT * FROM Feedback WHERE project_id = ? ORDER BY feedback_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $project_id);
$stmt->execute();
$feedback_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <link rel="stylesheet" href="style/projects.css"> <!-- External CSS -->
</head>
<body>
    <header>
        <h1>Project Details</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2><?php echo htmlspecialchars($project['title']); ?></h2>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($project['description']); ?></p>
        <p><strong>Start Date:</strong> <?php echo $project['start_date']; ?></p>
        <p><strong>End Date:</strong> <?php echo $project['end_date']; ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($project['status']); ?></p>

        <h3>Submit Feedback</h3>
        <form action="project_details.php?id=<?php echo $project_id; ?>" method="POST">
            <textarea name="feedback" rows="4" placeholder="Share your thoughts on this project..."></textarea>
            <button type="submit">Submit Feedback</button>
        </form>

        <h3>Feedback</h3>
        <?php if ($feedback_result->num_rows > 0): ?>
            <ul>
                <?php while ($feedback = $feedback_result->fetch_assoc()): ?>
                    <li>
                        <p><strong><?php echo htmlspecialchars($feedback['feedback_text']); ?></strong></p>
                        <p>
                            <a href="vote.php?action=upvote&feedback_id=<?php echo $feedback['feedback_id']; ?>">👍 Upvote (<?php echo $feedback['upvotes']; ?>)</a>
                            <a href="vote.php?action=downvote&feedback_id=<?php echo $feedback['feedback_id']; ?>">👎 Downvote (<?php echo $feedback['downvotes']; ?>)</a>
                        </p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No feedback yet.</p>
        <?php endif; ?>
    </main>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
