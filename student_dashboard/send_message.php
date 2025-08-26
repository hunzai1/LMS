<?php
require 'db_connection.php';
session_start();
header('Content-Type: application/json');

// ✅ Identify the logged-in sender
if (isset($_SESSION['teacher_id'])) {
    $senderType = 'teacher';
    $senderId   = (int) $_SESSION['teacher_id'];
} elseif (isset($_SESSION['student_id'])) {
    $senderType = 'student';
    $senderId   = (int) $_SESSION['student_id'];
} else {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

// ✅ Read input (supports x-www-form-urlencoded, multipart/form-data, OR raw JSON)
$raw = file_get_contents('php://input');
$payload = [];
if (!empty($raw)) {
    $json = json_decode($raw, true);
    if (is_array($json)) {
        $payload = $json;
    }
}

// Prefer $_POST, then fallback to JSON payload
$otherId = 0;
if (isset($_POST['other_id'])) {
    $otherId = (int) $_POST['other_id'];
} elseif (isset($_POST['receiver_id'])) {
    $otherId = (int) $_POST['receiver_id'];
} elseif (isset($payload['other_id'])) {
    $otherId = (int) $payload['other_id'];
} elseif (isset($payload['receiver_id'])) {
    $otherId = (int) $payload['receiver_id'];
}

$message = '';
if (isset($_POST['message'])) {
    $message = trim((string) $_POST['message']);
} elseif (isset($payload['message'])) {
    $message = trim((string) $payload['message']);
}

// ✅ Validate
if ($otherId <= 0 || $message === '') {
    echo json_encode(["success" => false, "message" => "Missing receiver or message"]);
    exit;
}

// ✅ Insert the message
$sql = "INSERT INTO messages (sender_type, sender_id, receiver_id, message, sent_at)
        VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("siis", $senderType, $senderId, $otherId, $message);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Message sent"]);
} else {
    echo json_encode(["success" => false, "message" => "Database error"]);
}

$stmt->close();
$conn->close();
