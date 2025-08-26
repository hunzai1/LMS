<?php
// Database connection details
$servername = "localhost"; // Replace with your database server name
$username = "root";        // Replace with your database username
$password = "";            // Replace with your database password (default is empty for XAMPP)
$database = "FYP";      // Your database name

// Create a new connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Connection successful
// echo "Connected successfully"; // Uncomment for testing purposes
?>
