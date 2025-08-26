<?php
session_start();
include 'db_connection.php';

$course_id  = $_GET['course_id'] ?? null;
$class_id   = $_GET['class_id'] ?? null;
$student_id = $_SESSION['students_id'] ?? null;  // ✅ correct session key

header('Content-Type: application/json');

if (!$student_id) {
    echo json_encode(["error" => "Student not logged in."]);
    exit;
}

if (!$course_id || !$class_id) {
    echo json_encode(["error" => "Missing course_id or class_id."]);
    exit;
}

$query = "SELECT * FROM attendance 
          WHERE course_id = ? AND class_id = ? AND student_id = ? 
          ORDER BY date ASC";

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["error" => "SQL prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("iii", $course_id, $class_id, $student_id);
$stmt->execute();
$result = $stmt->get_result();

$attendance = [];
while ($row = $result->fetch_assoc()) {
    $attendance[] = $row;
}

echo json_encode($attendance);

$stmt->close();
$conn->close();
