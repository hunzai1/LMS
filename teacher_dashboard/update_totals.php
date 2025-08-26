<?php
header("Content-Type: application/json");
require_once "teachers_db_connection.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data["class_id"]) || !isset($data["course_id"])) {
    echo json_encode(["success" => false, "error" => "Invalid input"]);
    exit;
}

$class_id = intval($data["class_id"]);
$course_id = intval($data["course_id"]);
$quiz_total = intval($data["quiz_total"] ?? 0);
$assignment_total = intval($data["assignment_total"] ?? 0);
$exam_total = intval($data["exam_total"] ?? 0);

// ✅ Update all results of this class + course
$sql = "UPDATE results 
        SET quiz_total = ?, assignment_total = ?, exam_total = ?, updated_at = NOW()
        WHERE class_id = ? AND course_id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "error" => "SQL prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("iiiii", $quiz_total, $assignment_total, $exam_total, $class_id, $course_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Totals updated successfully"]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
