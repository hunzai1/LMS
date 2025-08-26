<?php
require 'teachers_db_connection.php';
header('Content-Type: application/json');

// ---- merge request payload
$raw = file_get_contents("php://input");
$json = json_decode($raw, true);
if (!is_array($json)) $json = [];
$payload = array_merge($_GET ?? [], $_POST ?? [], $json);

// ---- sanitize input
$course_id  = isset($payload['course_id'])  ? (int)$payload['course_id']  : 0;
$class_id   = isset($payload['class_id'])   ? (int)$payload['class_id']   : 0;
$quiz_total = isset($payload['quiz_total']) ? (int)$payload['quiz_total'] : 0;
$assignment_total = isset($payload['assignment_total']) ? (int)$payload['assignment_total'] : 0;
$exam_total = isset($payload['exam_total']) ? (int)$payload['exam_total'] : 0;

if (!$course_id || !$class_id) {
    echo json_encode(["success" => false, "error" => "Missing course_id or class_id"]);
    exit;
}

// ---- fetch all students of this course/class
$sql_students = "SELECT students_id FROM student_course_assign WHERE course_id = ? AND class_id = ?";
$stmt = $conn->prepare($sql_students);
$stmt->bind_param("ii", $course_id, $class_id);
$stmt->execute();
$res = $stmt->get_result();
$students = [];
while ($row = $res->fetch_assoc()) {
    $students[] = $row['students_id'];
}
$stmt->close();

if (empty($students)) {
    echo json_encode(["success" => false, "error" => "No students found for this class/course"]);
    exit;
}

// ---- update results for each student
foreach ($students as $sid) {
    // check existing result
    $check = $conn->prepare("SELECT result_id, quiz_obtained, assignment_obtained, exam_obtained 
                             FROM results WHERE student_id=? AND course_id=? AND class_id=? LIMIT 1");
    $check->bind_param("iii", $sid, $course_id, $class_id);
    $check->execute();
    $res = $check->get_result();
    $row = $res->fetch_assoc();
    $check->close();

    if ($row) {
        // ---- update existing result (keep obtained marks, update totals + percentage)
        $quiz_obt = (int)$row['quiz_obtained'];
        $assignment_obt = (int)$row['assignment_obtained'];
        $exam_obt = (int)$row['exam_obtained'];

        $total_max = $quiz_total + $assignment_total + $exam_total;
        $total_obt = $quiz_obt + $assignment_obt + $exam_obt;
        $percentage = $total_max > 0 ? ($total_obt / $total_max) * 100 : 0.0;

        $upd = $conn->prepare("UPDATE results 
                               SET quiz_total=?, assignment_total=?, exam_total=?, percentage=?, updated_at=NOW()
                               WHERE result_id=?");
        $upd->bind_param("iiidi", $quiz_total, $assignment_total, $exam_total, $percentage, $row['result_id']);
        $upd->execute();
        $upd->close();
    } else {
        // ---- insert new row with totals, obtained = 0
        $percentage = 0.0;
        $ins = $conn->prepare("INSERT INTO results 
            (teacher_id, course_id, class_id, student_id,
             quiz_obtained, quiz_total,
             assignment_obtained, assignment_total,
             exam_obtained, exam_total, percentage)
            VALUES (0, ?, ?, ?, 0, ?, 0, ?, 0, ?, ?)");
        $ins->bind_param("iiiiiid", $course_id, $class_id, $sid, $quiz_total, $assignment_total, $exam_total, $percentage);
        $ins->execute();
        $ins->close();
    }
}

echo json_encode(["success" => true, "message" => "✅ Totals applied successfully to all students"]);

$conn->close();
?>  