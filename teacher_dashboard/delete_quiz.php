<?php
require 'teachers_db_connection.php';
session_start();

if (!isset($_GET['id'])) {
    exit("❌ Error: Quiz ID is missing.");
}

$quiz_id = intval($_GET['id']);
$teacher_id = $_SESSION['teacher_id'];

// ✅ Only allow deletion if quiz belongs to logged-in teacher
$sql = "DELETE FROM quizzes WHERE quiz_id = ? AND teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $quiz_id, $teacher_id);

if ($stmt->execute()) {
    echo "✅ Quiz deleted successfully.";
} else {
    echo "❌ Error deleting quiz: " . $stmt->error;
}
