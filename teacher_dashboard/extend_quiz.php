<?php
require 'teachers_db_connection.php';
session_start();

if (!isset($_GET['id']) || !isset($_GET['end_time'])) {
    exit("❌ Error: Missing parameters.");
}

$quiz_id = intval($_GET['id']);
$new_end_time = $_GET['end_time'];
$teacher_id = $_SESSION['teacher_id'];

// ✅ Update only if quiz belongs to this teacher
$sql = "UPDATE quizzes SET end_time = ? WHERE quiz_id = ? AND teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $new_end_time, $quiz_id, $teacher_id);

if ($stmt->execute()) {
    echo "✅ Quiz end date updated successfully.";
} else {
    echo "❌ Error updating date: " . $stmt->error;
}
