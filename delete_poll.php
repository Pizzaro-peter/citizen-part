<?php
session_start();
if ($_SESSION['role'] !== 'official' && $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
require 'config.php';

if (!isset($_GET['poll_id'])) {
    echo "No poll selected.";
    exit();
}

$poll_id = intval($_GET['poll_id']);

// Delete the poll
$stmt = $conn->prepare("DELETE FROM polls WHERE poll_id = ?");
$stmt->bind_param("i", $poll_id);
$stmt->execute();

echo "Poll deleted successfully!";
header("Location: manage_polls.php");
?>
