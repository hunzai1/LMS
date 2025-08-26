<?php
session_start();
require '../student_dashboard/db_connection.php';
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit;
}

$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

$newUsername = trim($data['newUsername'] ?? '');

if (!$newUsername) {
    echo json_encode(["success" => false, "error" => "Username cannot be empty"]);
    exit;
}

$admin_id = $_SESSION['admin_id'];

// ✅ Update username
$stmt = $conn->prepare("UPDATE admin SET username = ? WHERE admin_id = ?");
$stmt->bind_param("si", $newUsername, $admin_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$stmt->close();
$conn->close();
?>
