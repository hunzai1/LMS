<?php
header("Content-Type: application/json");
require_once "teachers_db_connection.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data["result_id"])) {
    echo json_encode(["success" => false, "error" => "Invalid input"]);
    exit;
}

$result_id = intval($data["result_id"]);
$quiz_obtained = intval($data["quiz_obtained"] ?? 0);
$quiz_total = intval($data["quiz_total"] ?? 0);
$assignment_obtained = intval($data["assignment_obtained"] ?? 0);
$assignment_total = intval($data["assignment_total"] ?? 0);
$exam_obtained = intval($data["exam_obtained"] ?? 0);
$exam_total = intval($data["exam_total"] ?? 0);

$sql = "UPDATE results 
        SET quiz_obtained=?, quiz_total=?, assignment_obtained=?, assignment_total=?, exam_obtained=?, exam_total=?, updated_at=NOW()
        WHERE result_id=?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiiiii", $quiz_obtained, $quiz_total, $assignment_obtained, $assignment_total, $exam_obtained, $exam_total, $result_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
