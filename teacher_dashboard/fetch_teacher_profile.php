<?php
session_start();
require 'teachers_db_connection.php'; // Update this to the correct path for your database connection file

header('Content-Type: application/json');

// Ensure the user is logged in
if (!isset($_SESSION['teacher_id'])) {
    echo json_encode(['error' => 'User is not logged in.']);
    exit;
}

// Get the user ID from the session
$teacher_id = intval($_SESSION['teacher_id']);

// Check database connection
if (!$conn) {
    echo json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit;
}

// Prepare the SQL query to fetch the teacher's profile details
$sql = "SELECT 
            first_name, 
            last_name, 
            teachers_email, 
            contact_number, 
            gender, 
            country, 
            province, 
            district, 
            subject_expertise, 
            qualification, 
            profile_picture 
        FROM teachers 
        WHERE teacher_id = ?";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['error' => 'Failed to prepare the SQL statement: ' . $conn->error]);
    exit;
}

// Bind the user ID and execute the query
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if any data is retrieved
if ($result->num_rows > 0) {
    $profile = $result->fetch_assoc();
    echo json_encode($profile); // Send the profile data as JSON
} else {
    echo json_encode(['error' => 'No profile found for the specified teacher ID.']);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
