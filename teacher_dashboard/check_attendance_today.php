<?php
require 'teachers_db_connection.php';
session_start();

if (!isset($_SESSION['teacher_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$class_id  = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$date      = date("Y-m-d");

if ($class_id === 0 || $course_id === 0) {
    echo json_encode(["success" => false, "message" => "Missing IDs"]);
    exit;
}

$sql = "SELECT COUNT(*) as count 
        FROM attendance 
        WHERE class_id = ? AND course_id = ? AND date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $class_id, $course_id, $date);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

echo json_encode([
    "success" => true,
    "alreadyTaken" => $res['count'] > 0
]);
