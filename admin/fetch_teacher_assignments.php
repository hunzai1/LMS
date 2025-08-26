<?php
include '../student_dashboard/db_connection.php';

// Query to join teacher_assign with teachers, courses, and classes
$query = "
    SELECT 
        ta.teacher_assign_id,
        CONCAT(t.first_name, ' ', t.last_name) AS teacher_name, 
        c.course_name, 
        cl.class_name
    FROM teacher_assign ta
    INNER JOIN teachers t ON ta.teacher_id = t.teacher_id
    INNER JOIN courses c ON ta.course_id = c.course_id
    INNER JOIN classes cl ON ta.class_id = cl.class_id
";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data); // Output data in JSON format
} else {
    echo json_encode([]); // Return an empty array if no records found
}

$conn->close();
?>
