<?php
require 'teachers_db_connection.php';
session_start();

if (!isset($_SESSION['teacher_id'])) {
    die("❌ Unauthorized");
}

$student_id = intval($_POST['student_id'] ?? 0);
$class_id   = intval($_POST['class_id'] ?? 0);
$course_id  = intval($_POST['course_id'] ?? 0);

if (!$student_id || !$class_id || !$course_id) {
    die("❌ Missing data");
}

$sql = "DELETE FROM student_course_assign WHERE students_id = ? AND class_id = ? AND course_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $student_id, $class_id, $course_id);

echo $stmt->execute() ? "✅ Student deassigned successfully." : "❌ Failed to deassign.";
