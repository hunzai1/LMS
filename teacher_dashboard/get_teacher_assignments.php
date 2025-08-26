<?php
header('Content-Type: application/json');
require 'teachers_db_connection.php';

$teacher_id = isset($_GET['teacher_id']) ? intval($_GET['teacher_id']) : 0;
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$class_id = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;

if ($teacher_id === 0 || $course_id === 0 || $class_id === 0) {
    echo json_encode(["success" => false, "assignments" => [], "message" => "Missing parameters"]);
    exit;
}

$sql = "SELECT assignment_id, assignment_title, assignment_description, due_date, marks , file_path, assignment_file, created_at
        FROM assignments
        WHERE teacher_id = ? AND course_id = ? AND class_id = ?
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $teacher_id, $course_id, $class_id);
$stmt->execute();
$result = $stmt->get_result();

$assignments = [];
while ($row = $result->fetch_assoc()) {
    $assignments[] = $row;
}

echo json_encode(["success" => true, "assignments" => $assignments]);

$stmt->close();
$conn->close();
?>
