<?php
$servername = "localhost";
$username = "root"; // Your XAMPP database username
$password = ""; // Your XAMPP database password (empty)
$dbname = "clients"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>