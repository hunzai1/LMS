<?php
require 'teachers_db_connection.php';
header('Content-Type: application/json');

// ✅ Validate parameters
if (!isset($_GET['course_id'], $_GET['class_id'])) {
    echo json_encode(["success" => false, "error" => "Missing required parameters"]);
    exit;
}

$course_id = intval($_GET['course_id']);
$class_id = intval($_GET['class_id']);

// ✅ Query summary table + student names (including quiz columns)
$sql = "
    SELECT 
        sas.summary_id,
        sas.student_id,
        sas.course_id,
        sas.class_id,
        sas.teacher_id,
        sas.total_obtained_marks,
        sas.total_marks,
        sas.quiz_obtained,
        sas.quiz_total,
        s.first_name,
        s.last_name
    FROM student_assignment_summary sas
    INNER JOIN students s 
        ON sas.student_id = s.students_id
    WHERE sas.course_id = ? AND sas.class_id = ?
    ORDER BY s.first_name ASC, s.last_name ASC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Database prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("ii", $course_id, $class_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode(["success" => true, "students" => $data]);
?>
