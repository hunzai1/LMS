<?php
session_start();
require 'db_connection.php';

$student_id = $_SESSION['students_id'] ?? null;

if (!$student_id) {
    echo json_encode(["error" => "Student not logged in"]);
    exit;
}

// ✅ Fetch student info (including ID and class)
$sqlStudent = "SELECT s.students_id, s.first_name, s.last_name, c.class_id, c.class_name 
               FROM students s
               JOIN classes c ON s.class_id = c.class_id
               WHERE s.students_id = ?";
$stmt = $conn->prepare($sqlStudent);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$resultStudent = $stmt->get_result();
$studentInfo = $resultStudent->fetch_assoc();
$stmt->close();

if (!$studentInfo) {
    echo json_encode(["error" => "Student not found"]);
    exit;
}

$class_id = $studentInfo['class_id'];

// ✅ Check publish_result table
$sqlCheck = "SELECT status FROM publish_result WHERE class_id = ?";
$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param("i", $class_id);
$stmt->execute();
$resultCheck = $stmt->get_result();
$publishInfo = $resultCheck->fetch_assoc();
$stmt->close();

if (!$publishInfo || $publishInfo['status'] !== 'on') {
    echo json_encode([
        "student" => $studentInfo,
        "results" => [],
        "error" => "Results not published yet for this class"
    ]);
    exit;
}

// ✅ Fetch all results of this student
$sqlResults = "SELECT r.*, cr.course_name 
               FROM results r
               JOIN courses cr ON r.course_id = cr.course_id
               WHERE r.student_id = ?";
$stmt = $conn->prepare($sqlResults);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$results = [];
while ($row = $result->fetch_assoc()) {
    $results[] = $row;
}

$stmt->close();
$conn->close();

// ✅ Return student info and results in JSON
echo json_encode([
    "student" => $studentInfo,
    "results" => $results
]);
?>
