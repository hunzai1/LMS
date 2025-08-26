<?php
session_start();
require 'teachers_db_connection.php';
header('Content-Type: application/json');

// ---- merge request payload
$raw = file_get_contents("php://input");
$json = json_decode($raw, true);
if (!is_array($json)) $json = [];
$payload = array_merge($_GET ?? [], $_POST ?? [], $json);

// ---- sanitize
$class_id  = isset($payload['class_id'])  ? (int)$payload['class_id']  : 0;
$course_id = isset($payload['course_id']) ? (int)$payload['course_id'] : 0;
$teacher_id = isset($_SESSION['teacher_id']) ? (int)$_SESSION['teacher_id'] : 0;

if (!$class_id || !$course_id || !$teacher_id) {
    echo json_encode(["success" => false, "error" => "Missing class_id, course_id or teacher session"]);
    exit;
}

// ---- fetch all students from student_course_assign
$sql_students = "SELECT students_id FROM student_course_assign WHERE class_id=? AND course_id=?";
$stmt = $conn->prepare($sql_students);
if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Prepare failed: ".$conn->error]);
    exit;
}
$stmt->bind_param("ii", $class_id, $course_id);
$stmt->execute();
$res = $stmt->get_result();

$added = 0;
while ($row = $res->fetch_assoc()) {
    $student_id = (int)$row['students_id'];

    // check if already exists in results
    $check = $conn->prepare("SELECT result_id FROM results WHERE student_id=? AND class_id=? AND course_id=? LIMIT 1");
    $check->bind_param("iii", $student_id, $class_id, $course_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        // insert new result row
        $ins = $conn->prepare("
            INSERT INTO results 
            (teacher_id, course_id, class_id, student_id,
             quiz_obtained, quiz_total,
             assignment_obtained, assignment_total,
             exam_obtained, exam_total, percentage, created_at, updated_at)
            VALUES (?, ?, ?, ?, 0, 0, 0, 0, 0, 0, 0.0, NOW(), NOW())
        ");
        if (!$ins) {
            echo json_encode(["success" => false, "error" => "Insert prepare failed: ".$conn->error]);
            exit;
        }
        $ins->bind_param("iiii", $teacher_id, $course_id, $class_id, $student_id);
        if ($ins->execute()) {
            $added++;
        }
        $ins->close();
    }

    $check->close();
}

$stmt->close();
$conn->close();

echo json_encode(["success" => true, "message" => "✅ $added students added to results"]);
?>
