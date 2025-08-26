<?php
require 'teachers_db_connection.php';

$course_id = intval($_GET['course_id']);
$class_id = intval($_GET['class_id']);

$sql = "SELECT quiz_id, title, total_marks, start_time, end_time 
        FROM quizzes 
        WHERE course_id = ? AND class_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $course_id, $class_id);
$stmt->execute();
$result = $stmt->get_result();

$quizzes = [];
while ($row = $result->fetch_assoc()) {
    $quizzes[] = $row;
}

header('Content-Type: application/json');
echo json_encode($quizzes);
