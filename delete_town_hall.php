<?php
session_start();
if ($_SESSION['role'] !== 'official' && $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
require 'db_connection.php';

if (!isset($_GET['meeting_id'])) {
    echo "No meeting selected.";
    exit();
}

$meeting_id = intval($_GET['meeting_id']);

// Delete the town hall meeting
$stmt = $conn->prepare("DELETE FROM TownHallMeetings WHERE meeting_id = ?");
$stmt->bind_param("i", $meeting_id);
$stmt->execute();

echo "Town hall meeting deleted successfully!";
header("Location: manage_town_halls.php");
?>
