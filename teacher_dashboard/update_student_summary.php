<?php
require 'teachers_db_connection.php';
header('Content-Type: application/json');

if (!isset($_GET['course_id'], $_GET['class_id'])) {
    echo json_encode(["success" => false, "error" => "Missing required parameters"]);
    exit;
}

$course_id = intval($_GET['course_id']);
$class_id = intval($_GET['class_id']);

// ✅ Insert new students or update if exists
$sql = "
    INSERT INTO student_assignment_summary 
        (student_id, course_id, class_id, teacher_id, total_obtained_marks, total_marks, quiz_total, quiz_obtained)
    SELECT 
        sca.students_id AS student_id,
        sca.course_id,
        sca.class_id,
        (
            SELECT a.teacher_id 
            FROM assignments a 
            WHERE a.course_id = sca.course_id AND a.class_id = sca.class_id 
            LIMIT 1
        ) AS teacher_id,

        -- 📝 Total obtained assignment marks
        COALESCE((
            SELECT SUM(ad.marks) 
            FROM assignments_data ad
            WHERE ad.students_id = sca.students_id
              AND ad.course_id = sca.course_id
              AND ad.class_id = sca.class_id
        ), 0) AS total_obtained_marks,

        -- 📝 Total assignment marks
        COALESCE((
            SELECT SUM(a2.marks) 
            FROM assignments a2
            WHERE a2.course_id = sca.course_id
              AND a2.class_id = sca.class_id
        ), 0) AS total_marks,

        -- 📝 Total quiz marks
        COALESCE((
            SELECT SUM(q.total_marks)
            FROM quizzes q
            WHERE q.course_id = sca.course_id
              AND q.class_id = sca.class_id
        ), 0) AS quiz_total,

        -- 📝 Quiz marks obtained by student
        COALESCE((
            SELECT SUM(qa.score)
            FROM quiz_attempts qa
            INNER JOIN quizzes qz ON qa.quiz_id = qz.quiz_id
            WHERE qa.student_id = sca.students_id
              AND qz.course_id = sca.course_id
              AND qz.class_id = sca.class_id
        ), 0) AS quiz_obtained

    FROM student_course_assign sca
    WHERE sca.course_id = ? AND sca.class_id = ?
    GROUP BY sca.students_id, sca.course_id, sca.class_id
    ON DUPLICATE KEY UPDATE
        total_obtained_marks = VALUES(total_obtained_marks),
        total_marks = VALUES(total_marks),
        quiz_total = VALUES(quiz_total),
        quiz_obtained = VALUES(quiz_obtained),
        teacher_id = VALUES(teacher_id),
        updated_at = CURRENT_TIMESTAMP
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["success" => false, "error" => $conn->error]);
    exit;
}

$stmt->bind_param("ii", $course_id, $class_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "✅ Student summary (assignments + quizzes) updated successfully"]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
