<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Get the user's role
$role = $_SESSION['role'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./style/style.css"> 
</head>
<body>
    <header>
        <h1>Welcome to the Citizen Participation Platform</h1>
        <nav>
            <ul>
                <!-- Common links for all users -->
                <li><a href="profile.php">Profile</a></li>
                <li><a href="view_notifications.php">View Notifications</a></li>
                <li><a href="logout.php">Logout</a></li>
                
                <?php if ($role == 'citizen') : ?>
                    <!-- Citizen-specific links -->
                    <li><a href="submit_feedback.php">Submit Feedback</a></li>
                    <li><a href="./view_polls.php">Vote on Polls</a></li>
                    <li><a href="track_projects.php">Track Projects</a></li>
                    <li><a href="report_issue.php">Report an Issue</a></li>
                <?php elseif ($role == 'official') : ?>
                    <!-- Official-specific links -->
                    <li><a href="manage_projects.php">Manage Projects</a></li>
                    <li><a href="create_poll.php">Create Polls</a></li>
                    <li><a href="view_feedback.php">View Feedback</a></li>
                    <li><a href="create_town_hall.php">Host Town Hall</a></li>
                    <li><a href="create_project.php">Create a Project</a></li>
                    <li><a href="./manage_polls.php">Manage poll results</a></li>
                <?php elseif ($role == 'moderator') : ?>
                    <!-- Moderator-specific links -->
                    <li><a href="view_feedback.php">Monitor Feedback</a></li>
                    <li><a href="resolve_issues.php">Resolve Reported Issues</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Dashboard</h2>
        <?php if ($role == 'citizen') : ?>
            <p>As a citizen, you can submit feedback, vote on polls, track public projects, and report issues.</p>
        <?php elseif ($role == 'official') : ?>
            <p>As a government official, you can manage projects, create polls, host town halls, and review citizen feedback.</p>
        <?php elseif ($role == 'moderator') : ?>
            <p>As a moderator, you oversee feedback submissions, discussions, and resolve reported issues.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Citizen Participation Platform</p>
    </footer>
</body>
</html>
