<?php
require 'teachers_db_connection.php';
session_start();

if (!isset($_GET['id'])) {
    exit(json_encode(["error" => "Quiz ID is required."]));
}

$quiz_id = intval($_GET['id']);
$teacher_id = $_SESSION['teacher_id'];

// ✅ Fetch quiz data only if it belongs to this teacher
$sql = "SELECT * FROM quizzes WHERE quiz_id = ? AND teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $quiz_id, $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    exit(json_encode(["error" => "Quiz not found or you don't have permission."]));
}

$quiz = $result->fetch_assoc();

// ✅ Fetch quiz questions
$sqlQ = "SELECT * FROM quiz_questions WHERE quiz_id = ?";
$stmtQ = $conn->prepare($sqlQ);
$stmtQ->bind_param("i", $quiz_id);
$stmtQ->execute();
$questions = $stmtQ->get_result()->fetch_all(MYSQLI_ASSOC);

$quiz['questions'] = $questions;

echo json_encode($quiz);
