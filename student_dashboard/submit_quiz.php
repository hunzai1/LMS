<?php
session_start();
require 'db_connection.php';

header('Content-Type: application/json');

// ✅ Get student_id from session (consistent naming)
$student_id = $_SESSION['students_id'] ?? null;
if (!$student_id) {
    echo json_encode(["success" => false, "message" => "Unauthorized. Please log in."]);
    exit;
}

$student_id = intval($student_id);
$quiz_id = isset($_POST['quiz_id']) ? intval($_POST['quiz_id']) : 0;

if ($quiz_id === 0) {
    echo json_encode(["success" => false, "message" => "Missing quiz ID."]);
    exit;
}

// ✅ Check if already attempted
$sqlCheck = "SELECT 1 FROM quiz_attempts WHERE quiz_id = ? AND student_id = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("ii", $quiz_id, $student_id);
$stmtCheck->execute();
if ($stmtCheck->get_result()->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "You have already attempted this quiz."]);
    exit;
}

// ✅ Fetch quiz questions
$sqlQ = "SELECT question_id, correct_option FROM quiz_questions WHERE quiz_id = ?";
$stmtQ = $conn->prepare($sqlQ);
$stmtQ->bind_param("i", $quiz_id);
$stmtQ->execute();
$resultQ = $stmtQ->get_result();

$total_questions = $resultQ->num_rows;
if ($total_questions === 0) {
    echo json_encode(["success" => false, "message" => "No questions found for this quiz."]);
    exit;
}

$score = 0;
$answers = [];

// ✅ Check answers & store in array
while ($row = $resultQ->fetch_assoc()) {
    $qId = $row['question_id'];
    $selected_option = isset($_POST["answer_$qId"]) ? $_POST["answer_$qId"] : null;

    // Save for insertion into student_answers table
    if ($selected_option) {
        $answers[] = [
            "question_id" => $qId,
            "selected_option" => $selected_option
        ];
    }

    if ($selected_option === $row['correct_option']) {
        $score++;
    }
}

// ✅ Get marks per question
$sqlMarks = "SELECT total_marks FROM quizzes WHERE quiz_id = ?";
$stmtMarks = $conn->prepare($sqlMarks);
$stmtMarks->bind_param("i", $quiz_id);
$stmtMarks->execute();
$marksRow = $stmtMarks->get_result()->fetch_assoc();
$total_marks = $marksRow['total_marks'];

$marks_per_question = $total_marks / $total_questions;
$final_score = $score * $marks_per_question;

// ✅ Save attempt in quiz_attempts
$sqlInsert = "INSERT INTO quiz_attempts (student_id, quiz_id, score, attempted_at) VALUES (?, ?, ?, NOW())";
$stmtInsert = $conn->prepare($sqlInsert);
$stmtInsert->bind_param("iid", $student_id, $quiz_id, $final_score);

if (!$stmtInsert->execute()) {
    echo json_encode(["success" => false, "message" => "Failed to save quiz attempt."]);
    exit;
}

// ✅ Save each answer in student_answers
$sqlAns = "INSERT INTO student_answers (student_id, quiz_id, question_id, selected_option) VALUES (?, ?, ?, ?)";
$stmtAns = $conn->prepare($sqlAns);

foreach ($answers as $ans) {
    $stmtAns->bind_param("iiis", $student_id, $quiz_id, $ans['question_id'], $ans['selected_option']);
    $stmtAns->execute();
}

echo json_encode([
    "success" => true,
    "score" => $final_score,
    "total" => $total_marks
]);
