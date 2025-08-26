<?php
require 'db_connection.php'; // adjust to your path

header('Content-Type: application/json');

if (!isset($_GET['student_id'], $_GET['course_id'], $_GET['class_id'])) {
    echo json_encode(["error" => "Missing required parameters"]);
    exit;
}

$student_id = intval($_GET['student_id']);
$course_id  = intval($_GET['course_id']);
$class_id   = intval($_GET['class_id']);

$query = "
    SELECT c.course_name, r.quiz_obtained, r.quiz_total, 
           r.assignment_obtained, r.assignment_total,
           r.exam_obtained, r.exam_total, r.percentage
    FROM student_course_assign sca
    INNER JOIN courses c ON sca.course_id = c.course_id
    LEFT JOIN results r 
        ON r.student_id = sca.students_id 
       AND r.course_id = sca.course_id 
       AND r.class_id = sca.class_id
    WHERE sca.students_id = :student_id
      AND sca.course_id  = :course_id
      AND sca.class_id   = :class_id
";

$stmt = $conn->prepare($query);
$stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
$stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
