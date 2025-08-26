<?php
session_start();
require 'teachers_db_connection.php'; // Adjust path

header('Content-Type: application/json');

// Must be logged in
if (!isset($_SESSION['teacher_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in.']);
    exit;
}

$teacher_id = $_SESSION['teacher_id'];
$course_id = $_POST['course_id'] ?? null;
$class_id = $_POST['class_id'] ?? null;

if (!isset($_FILES['course_outline']) || $_FILES['course_outline']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error.']);
    exit;
}

$fileTmpPath = $_FILES['course_outline']['tmp_name'];
$fileName = $_FILES['course_outline']['name'];
$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

// Only allow PDF/DOCX
$allowedExtensions = ['pdf', 'docx'];
if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
    echo json_encode(['success' => false, 'message' => 'Only PDF and DOCX allowed.']);
    exit;
}

$uploadDir = 'uploads/course_outlines/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$newFileName = uniqid() . '.' . $fileExtension;
$destPath = $uploadDir . $newFileName;

if (move_uploaded_file($fileTmpPath, $destPath)) {
    $query = "UPDATE teacher_assign SET course_outline = ? 
              WHERE teacher_id = ? AND course_id = ? AND class_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("siii", $destPath, $teacher_id, $course_id, $class_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Course outline uploaded successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
}
