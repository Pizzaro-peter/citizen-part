<?php
$host = 'localhost'; 
$db_user = 'root'; 
$db_password = ''; 
$db_name = 'citizen_participation_db'; 

// Create connection
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


