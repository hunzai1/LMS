<?php
require 'teachers_db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("❌ Invalid request method.");
}

$quiz_id = intval($_POST['quiz_id']);
$teacher_id = $_SESSION['teacher_id'];

// ✅ Update quiz info
$sql = "UPDATE quizzes 
        SET title = ?, time_limit = ?, start_time = ?, end_time = ?, total_marks = ? 
        WHERE quiz_id = ? AND teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sissiii", 
    $_POST['title'], 
    $_POST['time_limit'], 
    $_POST['start_time'], 
    $_POST['end_time'], 
    $_POST['total_marks'], 
    $quiz_id, 
    $teacher_id
);
$stmt->execute();

// ✅ Update questions
$qNum = 1;
while (isset($_POST["question_$qNum"])) {
    $sqlQ = "UPDATE quiz_questions 
             SET question_text = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_option = ?
             WHERE quiz_id = ? AND question_id = ?";
    $stmtQ = $conn->prepare($sqlQ);
    $stmtQ->bind_param(
        "ssssssii",
        $_POST["question_$qNum"],
        $_POST["option_a_$qNum"],
        $_POST["option_b_$qNum"],
        $_POST["option_c_$qNum"],
        $_POST["option_d_$qNum"],
        $_POST["correct_option_$qNum"],
        $quiz_id,
        $_POST["question_id_$qNum"]
    );
    $stmtQ->execute();
    $qNum++;
}

echo "✅ Quiz updated successfully!";
