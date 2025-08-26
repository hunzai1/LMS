<?php
session_start();
require 'teachers_db_connection.php'; // Ensure this file correctly connects to your database

header('Content-Type: application/json');

// Ensure the user is logged in
if (!isset($_SESSION['teacher_id'])) {
    echo json_encode(['error' => 'User is not logged in.']);
    exit;
}

// Get the teacher ID from the session
$teacher_id = intval($_SESSION['teacher_id']);

// Check database connection
if (!$conn) {
    echo json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit;
}

// Fetch assigned classes and courses
$sql = "
    SELECT 
        cl.class_id,
        cl.class_name,
        c.course_id,
        c.course_name
    FROM teacher_assign ta
    JOIN courses c ON ta.course_id = c.course_id
    JOIN classes cl ON ta.class_id = cl.class_id
    WHERE ta.teacher_id = ?
";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['error' => 'Failed to prepare SQL statement: ' . $conn->error]);
    exit;
}

// Bind and execute the query
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch results
$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = [
        'class_id' => $row['class_id'],
        'class_name' => $row['class_name'],
        'course_id' => $row['course_id'],
        'course_name' => $row['course_name']
    ];
}

// Return classes and courses as JSON
echo json_encode(['success' => true, 'courses' => $courses]);

// Close connections
$stmt->close();
$conn->close();
?>
