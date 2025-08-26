<?php
require 'teachers_db_connection.php';
header('Content-Type: application/json');

// ---- helper: merge POST/GET/JSON payloads
$raw = file_get_contents('php://input');
$json = json_decode($raw, true);
if (!is_array($json)) $json = [];

$payload = array_merge($_GET ?? [], $_POST ?? [], $json);

// ---- read + sanitize
$result_id     = isset($payload['result_id']) ? (int)$payload['result_id'] : null;
$exam_obtained = isset($payload['exam_obtained']) ? (float)$payload['exam_obtained'] : null;

if (!$result_id || $exam_obtained === null) {
    echo json_encode([
        "success" => false,
        "error"   => "Missing required parameters (result_id, exam_obtained)"
    ]);
    exit;
}

// ---- fetch current values (to preserve old quiz & assignment)
$sql = "SELECT quiz_obtained, quiz_total, assignment_obtained, assignment_total, exam_total, course_id, class_id 
        FROM results WHERE result_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Prepare failed: " . $conn->error]);
    exit;
}
$stmt->bind_param("i", $result_id);
$stmt->execute();
$res = $stmt->get_result();
if (!$row = $res->fetch_assoc()) {
    echo json_encode(["success" => false, "error" => "Result record not found"]);
    exit;
}
$stmt->close();

// ---- calculate new percentage
$quiz_obtained      = (float)$row['quiz_obtained'];
$quiz_total         = (float)$row['quiz_total'];
$assignment_obt     = (float)$row['assignment_obtained'];
$assignment_total   = (float)$row['assignment_total'];
$exam_total         = (float)$row['exam_total'];
$course_id          = (int)$row['course_id'];
$class_id           = (int)$row['class_id'];

$total_max = $quiz_total + $assignment_total + $exam_total;
$total_obt = $quiz_obtained + $assignment_obt + $exam_obtained;
$percentage = $total_max > 0 ? ($total_obt / $total_max) * 100 : 0.0;

// ---- update exam_obtained & percentage
$sql_upd = "UPDATE results 
            SET exam_obtained = ?, percentage = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE result_id = ?";
$stmt2 = $conn->prepare($sql_upd);
if (!$stmt2) {
    echo json_encode(["success" => false, "error" => "Prepare failed: " . $conn->error]);
    exit;
}
$stmt2->bind_param("ddi", $exam_obtained, $percentage, $result_id);

if ($stmt2->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "✅ Exam marks updated successfully",
        "result_id" => $result_id,
        "exam_obtained" => $exam_obtained,
        "percentage" => round($percentage, 2),
        "course_id" => $course_id,
        "class_id" => $class_id
    ]);
} else {
    echo json_encode(["success" => false, "error" => $stmt2->error]);
}

$stmt2->close();
$conn->close();
?>
