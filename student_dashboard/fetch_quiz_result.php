<?php
session_start();
require 'db_connection.php';

$student_id = $_SESSION['student_id'] ?? null;
$quiz_id    = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

if (!$student_id) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

if ($quiz_id === 0) {
    echo json_encode(["error" => "Missing quiz ID"]);
    exit;
}

// Fetch quiz questions and student's answers
$sql = "
    SELECT 
        qq.question_id,
        qq.question_text,
        qq.option_a,
        qq.option_b,
        qq.option_c,
        qq.option_d,
        qq.correct_option,
        sa.selected_option
    FROM quiz_questions qq
    LEFT JOIN student_answers sa 
        ON qq.question_id = sa.question_id 
        AND sa.student_id = ?
    WHERE qq.quiz_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $quiz_id);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}

header('Content-Type: application/json');
echo json_encode($questions);
