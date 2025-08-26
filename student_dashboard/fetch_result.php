<?php
session_start();
require 'db_connection.php';

header('Content-Type: application/json');

// ✅ Get student_id from session (or hardcode for test)
$student_id = $_SESSION['students_id'] ?? null;
$course_id  = $_GET['course_id'] ?? null;
$class_id   = $_GET['class_id'] ?? null;

// Hardcoded test values (remove later)


if (!$student_id || !$course_id || !$class_id) {
    echo json_encode(["error" => "Missing student, course, or class ID."]);
    exit;
}

try {
    $query = "
        SELECT result_id, teacher_id, course_id, class_id, student_id,
               quiz_obtained, quiz_total,
               assignment_obtained, assignment_total,
               exam_obtained, exam_total,
               percentage, created_at, updated_at
        FROM results
        WHERE student_id = ? 
          AND course_id  = ?
          AND class_id   = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $student_id, $course_id, $class_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    echo json_encode($result ?: []);
} catch (Exception $e) {
    echo json_encode(["error" => "DB Error: " . $e->getMessage()]);
}
