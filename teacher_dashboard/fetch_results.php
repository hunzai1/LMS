<?php
header("Content-Type: application/json");
require_once "teachers_db_connection.php";

// Debug mode (remove or comment in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$class_id  = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

if ($class_id === 0 || $course_id === 0) {
    echo json_encode(["error" => "Missing class_id or course_id"]);
    exit;
}

// ✅ Fetch all students from student_course_assign, join with results (if exists)
$sql = "
    SELECT 
        sca.student_course_assign_id,
        sca.students_id AS student_id,
        sca.class_id,
        sca.course_id,
        s.first_name,
        s.last_name,
        r.result_id,
        r.teacher_id,
        r.quiz_obtained,
        r.quiz_total,
        r.assignment_obtained,
        r.assignment_total,
        r.exam_obtained,
        r.exam_total,
        r.percentage
    FROM student_course_assign sca
    INNER JOIN students s 
        ON sca.students_id = s.students_id
    LEFT JOIN results r 
        ON r.student_id = sca.students_id 
       AND r.class_id = sca.class_id 
       AND r.course_id = sca.course_id
    WHERE sca.class_id = ? AND sca.course_id = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "SQL prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("ii", $class_id, $course_id);

if (!$stmt->execute()) {
    echo json_encode(["error" => "SQL execute failed: " . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();
