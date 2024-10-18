<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'config.php'; // Include your database connection

function sendNotification($userId, $message) {
    global $conn;
    // Prepare the SQL statement to insert the notification
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, notification_message, timestamp) VALUES (?, ?, NOW())");
    
    if ($stmt === false) {
        die('MySQL prepare error: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("is", $userId, $message);
    
    if (!$stmt->execute()) {
        die('MySQL execute error: ' . htmlspecialchars($stmt->error));
    }

    $stmt->close(); // Close the statement after execution
}

function notifyAllUsers($message) {
    global $conn;
    // Get all active users to notify them
    $users = $conn->query("SELECT user_id FROM users WHERE active = 1");

    while ($user = $users->fetch_assoc()) {
        sendNotification($user['user_id'], $message);
    }
}

// Function to send a system notification for registration confirmation
function notifyRegistrationConfirmation($userId) {
    sendNotification($userId, "Thank you for registering! Your account has been created successfully.");
}

// Function to send notifications about new comments on discussions
function notifyNewComment($userId, $discussionTitle) {
    sendNotification($userId, "A new comment has been added to the discussion: " . htmlspecialchars($discussionTitle));
}

// Function to send reminders for upcoming town hall meetings or voting deadlines
function notifyUpcomingEvent($userId, $eventTitle, $eventDate) {
    sendNotification($userId, "Reminder: Upcoming event - " . htmlspecialchars($eventTitle) . " on " . htmlspecialchars($eventDate));
}

// Example usage of the notification functions

// 1. Sending a notification after user registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    // Registration logic goes here...
    
    // Assuming you have the user ID after registration
    $userId = 123; // Replace with actual user ID
    notifyRegistrationConfirmation($userId);
}

// 2. Sending notifications after creating a poll
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_poll'])) {
    // After poll creation logic goes here...
    
    if (isset($_POST['title'])) {
        notifyAllUsers("A new poll has been created: " . htmlspecialchars($_POST['title']));
    } else {
        echo "Poll title not set.";
    }
}

// 3. Sending a notification for a new comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_comment'])) {
    // Assuming you get the user ID from the session or another source
    $userId = $_SESSION['user_id']; // Replace with the actual user ID of the user who commented
    $discussionTitle = $_POST['discussion_title']; // Replace with the actual discussion title from the form
    notifyNewComment($userId, $discussionTitle);
}

// 4. Sending a reminder for an upcoming town hall meeting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upcoming_event'])) {
    // Assuming you have the user ID and event details from the form
    $userId = $_SESSION['user_id']; // Replace with actual user ID
    $eventTitle = $_POST['event_title']; // Replace with the actual event title from the form
    $eventDate = $_POST['event_date']; // Replace with the actual event date from the form
    notifyUpcomingEvent($userId, $eventTitle, $eventDate);
}

?>
