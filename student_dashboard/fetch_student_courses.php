<?php
session_start();
include 'db_connection.php';

// Check if student is logged in
if (!isset($_SESSION['students_id'])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

$students_id = $_SESSION['students_id'];

$query = "
    SELECT 
        sc.course_id,
        sc.class_id,
        c.course_name,
        cl.class_name
    FROM student_course_assign sc
    JOIN courses c ON sc.course_id = c.course_id
    JOIN classes cl ON sc.class_id = cl.class_id
    WHERE sc.students_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $students_id);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];

while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

echo json_encode($courses);

$stmt->close();
$conn->close();
?>
