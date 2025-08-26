<?php
require 'teachers_db_connection.php';
header('Content-Type: application/json');

$course_id = intval($_GET['course_id'] ?? 0);
$class_id = intval($_GET['class_id'] ?? 0);

if (!$course_id || !$class_id) {
    echo json_encode(["assignment_id" => null, "error" => "Missing parameters"]);
    exit;
}

$sql = "SELECT assignment_id 
        FROM assignments 
        WHERE course_id = ? AND class_id = ? 
        ORDER BY created_at DESC 
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $course_id, $class_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(["assignment_id" => $row['assignment_id'] ?? null]);
?>
