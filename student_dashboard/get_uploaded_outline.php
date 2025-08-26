<?php
session_start();
require 'db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['students_id'])) {
    echo json_encode(['exists' => false]);
    exit;
}

$course_id = $_GET['course_id'] ?? null;
$class_id = $_GET['class_id'] ?? null;

if (!$course_id || !$class_id) {
    echo json_encode(['exists' => false]);
    exit;
}

// Query course_outline file name from database
$query = "SELECT course_outline FROM teacher_assign 
          WHERE course_id = ? AND class_id = ? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $course_id, $class_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (!empty($row['course_outline'])) {
        $fileName = basename($row['course_outline']);
        $baseUrl = "http://localhost:8012/MY-FYP/teacher_dashboard/uploads/course_outlines/";
        $fileUrl = $baseUrl . $fileName;

        echo json_encode([
            'exists' => true,
            'file_url' => $fileUrl,
            'file_name' => $fileName
        ]);
    } else {
        echo json_encode(['exists' => false]);
    }
} else {
    echo json_encode(['exists' => false]);
}
