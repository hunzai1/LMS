<?php
require 'teachers_db_connection.php';

$class_id = $_GET['class_id'];
$query = "
    SELECT s.students_id, s.students_name, 
           IF(sa.course_id IS NULL, 0, 1) AS is_assigned
    FROM students s
    LEFT JOIN student_assign sa 
    ON s.students_id = sa.student_id AND sa.course_id = :course_id
    WHERE s.class_id = :class_id
";
$stmt = $conn->prepare($query);
$stmt->bindParam(':class_id', $class_id);
$stmt->bindParam(':course_id', $_GET['course_id']);
$stmt->execute();
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
