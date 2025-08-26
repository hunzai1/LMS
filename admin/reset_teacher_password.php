<?php
include '../student_dashboard/db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['teacher_id'])) {
    echo json_encode(["success" => false, "message" => "Teacher ID missing"]);
    exit;
}

$teacher_id = intval($data['teacher_id']);
$default_password = password_hash("teacher123", PASSWORD_BCRYPT);

// Update password
$sql = "UPDATE teachers SET teachers_password = ? WHERE teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $default_password, $teacher_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Password reset successful"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to reset password"]);
}
?>
