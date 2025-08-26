<?php
require 'teachers_db_connection.php';
session_start();

if (!isset($_SESSION['teacher_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$class_id  = intval($_GET['class_id']);
$course_id = intval($_GET['course_id']);

$sql = "SELECT s.students_id, s.first_name, s.last_name, s.students_email 
        FROM students s
        INNER JOIN student_course_assign sca ON sca.students_id = s.students_id
        WHERE sca.class_id = ? AND sca.course_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $class_id, $course_id);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

echo json_encode(["success" => true, "students" => $students]);
