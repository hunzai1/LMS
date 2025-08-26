<?php
require 'db_connection.php';
session_start();

$student_id = $_SESSION['students_id'];
$course_id = $_GET['course_id'];
$class_id = $_GET['class_id'];

// Fetch assignments with student submission status
$sql = "
    SELECT a.assignment_id, a.assignment_title, a.assignment_description, 
           a.due_date, a.marks, a.file_path,
           ad.assignment_uploads, ad.assignments_data_id
    FROM assignments a
    LEFT JOIN assignments_data ad 
        ON a.assignment_id = ad.assignment_id 
        AND ad.students_id = ?
    WHERE a.course_id = ? AND a.class_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $student_id, $course_id, $class_id);
$stmt->execute();
$result = $stmt->get_result();

$assignments = [];
while ($row = $result->fetch_assoc()) {
    $assignments[] = $row;
}

header("Content-Type: application/json");
echo json_encode($assignments);
