<?php
require 'teachers_db_connection.php';
header('Content-Type: application/json');

if (!isset($_GET['course_id'], $_GET['class_id'])) {
    echo json_encode(["success" => false, "error" => "Missing parameters"]);
    exit;
}

$course_id = intval($_GET['course_id']);
$class_id = intval($_GET['class_id']);

// ✅ Update results based on normalized values
$sql = "
    UPDATE results r
    JOIN student_assignment_summary sas 
      ON r.student_id = sas.student_id
     AND r.course_id = sas.course_id
     AND r.class_id = sas.class_id
    SET 
      r.quiz_obtained = ROUND(
          (CASE 
             WHEN sas.total_marks > 0 
             THEN (sas.quiz_obtained / sas.total_marks) * r.quiz_total -1
             ELSE 0
           END), 2
      ),
      r.assignment_obtained = ROUND(
          (CASE 
             WHEN sas.total_marks > 0 
             THEN (sas.total_obtained_marks / sas.total_marks) * r.assignment_total
             ELSE 0
           END), 2
      ),
      r.updated_at = CURRENT_TIMESTAMP
    WHERE r.course_id = ? AND r.class_id = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["success" => false, "error" => $conn->error]);
    exit;
}

$stmt->bind_param("ii", $course_id, $class_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "✅ Results updated successfully"]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
