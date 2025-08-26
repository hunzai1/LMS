<?php
session_start();
require 'teachers_db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['teacher_id'])) {
    echo json_encode(['exists' => false]);
    exit;
}

$teacher_id = $_SESSION['teacher_id'];
$course_id = $_GET['course_id'] ?? null;
$class_id = $_GET['class_id'] ?? null;

$query = "SELECT course_outline FROM teacher_assign 
          WHERE teacher_id = ? AND course_id = ? AND class_id = ? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $teacher_id, $course_id, $class_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (!empty($row['course_outline'])) {
        echo json_encode(['exists' => true, 'path' => $row['course_outline']]);
    } else {
        echo json_encode(['exists' => false]);
    }
} else {
    echo json_encode(['exists' => false]);
}
