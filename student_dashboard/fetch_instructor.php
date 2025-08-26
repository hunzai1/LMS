<?php
// fetch_instructor.php
include 'db_connection.php';

$course_id = $_GET['course_id'] ?? null;
$class_id = $_GET['class_id'] ?? null;

if ($course_id && $class_id) {
    $query = "
        SELECT t.*
        FROM teacher_assign ta
        JOIN teachers t ON ta.teacher_id = t.teacher_id
        WHERE ta.course_id = ? AND ta.class_id = ?
        LIMIT 1
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $course_id, $class_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($instructor = $result->fetch_assoc()) {
        echo json_encode($instructor);
    } else {
        echo json_encode(['error' => 'Instructor not found for this course and class.']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Missing course_id or class_id.']);
}

$conn->close();
?>
