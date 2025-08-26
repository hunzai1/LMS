<?php
require 'teachers_db_connection.php';
header('Content-Type: application/json');

// ---- helper: merge POST/GET/JSON payloads
$raw = file_get_contents('php://input');
$json = json_decode($raw, true);
if (!is_array($json)) $json = [];

$payload = array_merge($_GET ?? [], $_POST ?? [], $json);

// ---- read + sanitize
$student_id     = isset($payload['student_id']) ? (int)$payload['student_id'] : null;
$course_id      = isset($payload['course_id'])  ? (int)$payload['course_id']  : null;
$class_id       = isset($payload['class_id'])   ? (int)$payload['class_id']   : null;
$exam_obtained  = isset($payload['exam_obtained']) ? (float)$payload['exam_obtained'] : 0.0;

if (!$student_id || !$course_id || !$class_id) {
    echo json_encode([
        "success" => false,
        "error"   => "Missing required parameters (student_id, course_id, class_id)"
    ]);
    exit;
}

// ---- check if result already exists
$sql_check = "SELECT result_id, quiz_obtained, quiz_total, assignment_obtained, assignment_total, exam_total 
              FROM results 
              WHERE student_id = ? AND course_id = ? AND class_id = ? LIMIT 1";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("iii", $student_id, $course_id, $class_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if ($row) {
    // ✅ Update existing record
    $quiz_total = (float)$row['quiz_total'];
    $quiz_obtained = (float)$row['quiz_obtained'];
    $assignment_total = (float)$row['assignment_total'];
    $assignment_obtained = (float)$row['assignment_obtained'];
    $exam_total = (float)$row['exam_total'];

    $total_max = $quiz_total + $assignment_total + $exam_total;
    $total_obt = $quiz_obtained + $assignment_obtained + $exam_obtained;
    $percentage = $total_max > 0 ? ($total_obt / $total_max) * 100 : 0.0;

    $sql_update = "UPDATE results 
                   SET exam_obtained = ?, percentage = ?, updated_at = CURRENT_TIMESTAMP 
                   WHERE result_id = ?";
    $stmt2 = $conn->prepare($sql_update);
    $stmt2->bind_param("ddi", $exam_obtained, $percentage, $row['result_id']);
    $ok = $stmt2->execute();
    $stmt2->close();

    if ($ok) {
        echo json_encode(["success" => true, "message" => "✅ Exam marks updated successfully"]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
} else {
    // ✅ Insert new record with decimal exam_obtained
    $sql_insert = "INSERT INTO results 
        (teacher_id, course_id, class_id, student_id,
         quiz_obtained, quiz_total,
         assignment_obtained, assignment_total,
         exam_obtained, exam_total, percentage)
        VALUES (0, ?, ?, ?, 0, 0, 0, 0, ?, 0, 0)";

    $stmt3 = $conn->prepare($sql_insert);
    $stmt3->bind_param("iiid", $course_id, $class_id, $student_id, $exam_obtained);
    $ok = $stmt3->execute();
    $stmt3->close();

    if ($ok) {
        echo json_encode(["success" => true, "message" => "✅ Exam marks inserted successfully"]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
}

$conn->close();
?>
