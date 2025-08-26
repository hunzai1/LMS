<?php
// Include database connection
include 'teachers_db_connection.php';

// Set response content type to JSON
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Corrected SQL Query
$query = "
    SELECT 
        s.first_name,
        c.course_name,
        cl.class_name
    FROM 
        students_assign sa
    JOIN 
        students s ON sa.student_id = s.students_id
    JOIN 
        courses c ON sa.course_id = c.course_id
    JOIN 
        classes cl ON sa.class_id = cl.class_id
";

$result = $conn->query($query);

// Handle query errors
if (!$result) {
    echo json_encode(['error' => 'Database query failed: ' . $conn->error]);
    exit;
}

// Fetch data and format as JSON
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'first_name' => $row['first_name'],
        'course_name' => $row['course_name'],
        'class_name' => $row['class_name'],
    ];
}

// Return data as JSON response
echo json_encode(['data' => $data]);

// Close database connection
$conn->close();
?>
