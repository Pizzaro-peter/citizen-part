function sendNotification($user_id, $message) {
    global $conn;

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message, timestamp) VALUES (?, ?, NOW())");

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die('MySQL prepare error: ' . htmlspecialchars($conn->error));
    }

    // Bind parameters
    $stmt->bind_param("is", $user_id, $message);

    // Execute the statement
    if (!$stmt->execute()) {
        die('MySQL execute error: ' . htmlspecialchars($stmt->error));
    }

    // Close the statement
    $stmt->close();
}
