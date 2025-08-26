<?php
require 'teachers_db_connection.php';
session_start();
header('Content-Type: application/json');

$teacher_id = isset($_GET['teacher_id']) ? intval($_GET['teacher_id']) : 0;
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;
$course_id  = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$class_id   = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;

if ($teacher_id === 0 || $student_id === 0 || $course_id === 0 || $class_id === 0) {
    echo json_encode(["success" => false, "message" => "❌ Missing required parameters."]);
    
    exit;
}

$sql = "SELECT message_id, teacher_id, student_id, course_id, class_id, sender_type, message, created_at
        FROM messages
        WHERE teacher_id = ? AND student_id = ? AND course_id = ? AND class_id = ?
        ORDER BY created_at ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $teacher_id, $student_id, $course_id, $class_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode(["success" => true, "messages" => $messages]);
?>
