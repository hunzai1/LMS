<?php
require 'teachers_db_connection.php';



header('Content-Type: application/json');

if (!isset($_GET['class_id']) || !isset($_GET['course_id'])) {
    echo json_encode(['error' => 'Missing class_id or course_id']);
    exit;
}

$classId = intval($_GET['class_id']);
$courseId = intval($_GET['course_id']);

try {
    // Fetch students from the given class
    $stmt = $conn->prepare("SELECT students_id, first_name, last_name FROM students WHERE class_id = ?");
    $stmt->bind_param("i", $classId);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];

    while ($row = $result->fetch_assoc()) {
        $studentId = $row['students_id'];

        // Check if the student is assigned to the selected course and class
        $check = $conn->prepare("SELECT 1 FROM student_course_assign WHERE students_id = ? AND course_id = ? AND class_id = ?");
        $check->bind_param("iii", $studentId, $courseId, $classId);
        $check->execute();
        $isAssigned = $check->get_result()->num_rows > 0;

        $students[] = [
            'student_id' => $studentId,
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'is_assigned' => $isAssigned
        ];
    }

    echo json_encode(['students' => $students]);

} catch (Exception $e) {
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
