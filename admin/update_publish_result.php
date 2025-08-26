<?php
include '../student_dashboard/db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['class_id']) || !isset($data['status'])) {
    echo json_encode(["success" => false, "error" => "Missing parameters"]);
    exit;
}

$class_id = intval($data['class_id']);
$status   = ($data['status'] === "on") ? "on" : "off";

// ✅ Insert or Update row
$sql = "INSERT INTO publish_result (class_id, status)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE status = VALUES(status)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["success" => false, "error" => $conn->error]);
    exit;
}

$stmt->bind_param("is", $class_id, $status);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "class_id" => $class_id,
        "status" => $status
    ]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
