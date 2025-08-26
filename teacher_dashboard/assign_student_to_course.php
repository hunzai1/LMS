<?php
require 'teachers_db_connection.php';

header('Content-Type: application/json');

// Read raw JSON POST body
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (
    !isset($data['student_id']) ||
    !isset($data['course_id']) ||
    !isset($data['class_id']) ||
    !isset($data['action'])
) {
    echo json_encode(['error' => 'Missing required fields.']);
    exit;
}

$studentId = intval($data['student_id']);
$courseId  = intval($data['course_id']);
$classId   = intval($data['class_id']);
$action    = $data['action'];

try {
    if ($action === 'assign') {
        // Check if already assigned
        $checkStmt = $conn->prepare("SELECT * FROM student_course_assign WHERE students_id = ? AND course_id = ? AND class_id = ?");
        $checkStmt->bind_param("iii", $studentId, $courseId, $classId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Already assigned.']);
        } else {
            // Insert assignment
            $insertStmt = $conn->prepare("INSERT INTO student_course_assign (students_id, course_id, class_id) VALUES (?, ?, ?)");
            $insertStmt->bind_param("iii", $studentId, $courseId, $classId);
            $insertStmt->execute();

            echo json_encode(['success' => true, 'message' => 'Student assigned successfully.']);
        }

    } elseif ($action === 'deassign') {
        // Delete the assignment
        $deleteStmt = $conn->prepare("DELETE FROM student_course_assign WHERE students_id = ? AND course_id = ? AND class_id = ?");
        $deleteStmt->bind_param("iii", $studentId, $courseId, $classId);
        $deleteStmt->execute();

        echo json_encode(['success' => true, 'message' => 'Student deassigned successfully.']);
    } else {
        echo json_encode(['error' => 'Invalid action specified.']);
    }

} catch (Exception $e) {
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
