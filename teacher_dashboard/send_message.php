<?php
require 'teachers_db_connection.php';
session_start();
header('Content-Type: application/json');

// Read POST
$teacher_id  = isset($_POST['teacher_id']) ? (int)$_POST['teacher_id'] : 0;
$student_id  = isset($_POST['student_id']) ? (int)$_POST['student_id'] : 0;
$course_id   = isset($_POST['course_id'])  ? (int)$_POST['course_id']  : 0;
$class_id    = isset($_POST['class_id'])   ? (int)$_POST['class_id']   : 0;
$sender_type = isset($_POST['sender_type']) ? trim($_POST['sender_type']) : '';
$message     = isset($_POST['message']) ? trim($_POST['message']) : '';

// 🔁 Fallbacks from session when appropriate
if ($sender_type === 'teacher' && $teacher_id === 0 && isset($_SESSION['teacher_id'])) {
    $teacher_id = (int)$_SESSION['teacher_id'];
}
if ($sender_type === 'student' && $student_id === 0) {
    if (isset($_SESSION['student_id'])) {
        $student_id = (int)$_SESSION['student_id'];
    } elseif (isset($_SESSION['students_id'])) { // in case your session key is plural
        $student_id = (int)$_SESSION['students_id'];
    }
}

// Validate
$errors = [];
if ($teacher_id <= 0)  $errors[] = 'teacher_id';
if ($student_id <= 0)  $errors[] = 'student_id';
if ($course_id  <= 0)  $errors[] = 'course_id';
if ($class_id   <= 0)  $errors[] = 'class_id';
if ($sender_type !== 'teacher' && $sender_type !== 'student') $errors[] = 'sender_type';
if ($message === '')   $errors[] = 'message';

if (!empty($errors)) {
    echo json_encode([
        "success" => false,
        "message" => "❌ Missing required fields: " . implode(', ', $errors)
    ]);
    exit;
}

// Optional: ensure the student actually belongs to this class/course
// $chk = $conn->prepare("SELECT 1 FROM student_course_assign WHERE students_id=? AND class_id=? AND course_id=? LIMIT 1");
// $chk->bind_param("iii", $student_id, $class_id, $course_id);
// $chk->execute();
// if ($chk->get_result()->num_rows === 0) {
//     echo json_encode(["success"=>false,"message"=>"❌ Student is not assigned to this class/course."]);
//     exit;
// }

// Insert
$sql = "INSERT INTO messages (teacher_id, student_id, course_id, class_id, sender_type, message, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiiss", $teacher_id, $student_id, $course_id, $class_id, $sender_type, $message);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message_id" => $stmt->insert_id]);
} else {
    echo json_encode(["success" => false, "message" => "Database error while sending message."]);
}
