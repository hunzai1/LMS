<?php
require 'db_connection.php';
session_start();
header('Content-Type: application/json');

// ✅ Ensure student is logged in
if (!isset($_SESSION['student_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$student_id = (int) $_SESSION['student_id'];
$teacher_id = isset($_GET['teacher_id']) ? (int) $_GET['teacher_id'] : 0;

if ($teacher_id <= 0) {
    echo json_encode(["success" => false, "message" => "Missing teacher ID"]);
    exit;
}

// ✅ Fetch messages between student & teacher
$sql = "SELECT message_id, teacher_id, student_id, sender_type, message, created_at
        FROM messages
        WHERE student_id = ? AND teacher_id = ?
        ORDER BY created_at ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode([
    "success" => true,
    "messages" => $messages
]);

$stmt->close();
$conn->close();
