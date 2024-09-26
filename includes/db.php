<?php
// Database connection settings
$host = "localhost";
$username = "root";  // Default for XAMPP
$password = "";      // Default for XAMPP (empty password)
$database = "beauty_studio";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
