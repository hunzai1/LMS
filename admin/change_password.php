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

$currentPassword = $data['currentPassword'] ?? '';
$newPassword = $data['newPassword'] ?? '';

if (!$currentPassword || !$newPassword) {
    echo json_encode(["success" => false, "error" => "Missing required fields"]);
    exit;
}

$admin_id = $_SESSION['admin_id'];

// ✅ Fetch current password hash
$sql = "SELECT password FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(["success" => false, "error" => "Admin not found"]);
    exit;
}

if (!password_verify($currentPassword, $row['password'])) {
    echo json_encode(["success" => false, "error" => "Current password is incorrect"]);
    exit;
}

// ✅ Update with new hash
$newHash = password_hash($newPassword, PASSWORD_BCRYPT);
$upd = $conn->prepare("UPDATE admin SET password = ? WHERE admin_id = ?");
$upd->bind_param("si", $newHash, $admin_id);

if ($upd->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$upd->close();
$conn->close();
?>
