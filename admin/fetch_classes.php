<?php
// Database connection
include '../student_dashboard/db_connection.php';

$sql = "SELECT class_id, class_name FROM classes";
$result = $conn->query($sql);

$classes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($classes);

$conn->close();
?>
