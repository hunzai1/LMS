<?php
session_start();
require 'db_connection.php';

$student_id    = $_SESSION['students_id'] ?? null;
$assignment_id = $_POST['assignment_id'] ?? null;
$course_id     = $_POST['course_id'] ?? null;
$class_id      = $_POST['class_id'] ?? null;

if (!$student_id || !$assignment_id || !$course_id || !$class_id) {
    die("❌ Missing student, assignment, course, or class ID.");
}

if (!isset($_FILES['assignment_file']) || $_FILES['assignment_file']['error'] !== UPLOAD_ERR_OK) {
    die("❌ File upload error.");
}

$fileTmp  = $_FILES['assignment_file']['tmp_name'];
$fileName = basename($_FILES['assignment_file']['name']);
$uploadDir = "uploads/assignments/";
$uploadPath = $uploadDir . time() . "_" . $fileName;

// Ensure upload directory exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Move file
if (!move_uploaded_file($fileTmp, $uploadPath)) {
    die("❌ Failed to move uploaded file.");
}

// ✅ Check if this student already submitted for this assignment
$checkSql = "SELECT assignments_data_id FROM assignments_data WHERE students_id = ? AND assignment_id = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("ii", $student_id, $assignment_id);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    // 🔄 Update previous submission
    $updateSql = "UPDATE assignments_data 
                  SET assignment_uploads = ?, created_at = NOW() 
                  WHERE students_id = ? AND assignment_id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sii", $uploadPath, $student_id, $assignment_id);

    if ($updateStmt->execute()) {
        echo "✅ Assignment updated successfully!";
    } else {
        echo "❌ Failed to update assignment. " . $updateStmt->error;
    }
    $updateStmt->close();
} else {
    // 🆕 Insert new submission
    $insertSql = "INSERT INTO assignments_data (students_id, assignment_id, assignment_uploads, created_at, course_id, class_id) 
                  VALUES (?, ?, ?, NOW(), ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("iissi", $student_id, $assignment_id, $uploadPath, $course_id, $class_id);

    if ($insertStmt->execute()) {
        echo "✅ Assignment submitted successfully!";
    } else {
        echo "❌ Failed to submit assignment. " . $insertStmt->error;
    }
    $insertStmt->close();
}

$checkStmt->close();
$conn->close();
?>
