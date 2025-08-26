<?php
// Database connection
require '../student_dashboard/db_connection.php';

header('Content-Type: application/json');

$query = "SELECT assign_course_id FROM assign_courses";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
    echo json_encode($courses);
} else {
    echo json_encode([]);
}

$conn->close();
?>
