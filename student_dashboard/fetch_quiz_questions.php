<?php
require 'db_connection.php';
session_start();

$quiz_id = intval($_GET['quiz_id']);

// ✅ Get quiz questions
$sql = "SELECT * FROM quiz_questions WHERE quiz_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode($questions);
