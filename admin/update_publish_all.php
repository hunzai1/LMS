<?php
include '../student_dashboard/db_connection.php'; // DB connection

$data = json_decode(file_get_contents("php://input"), true);
$status = $data['status'] ?? 'off';

// Step 1: Insert missing class_ids from classes table into publish_result
$sql_insert = "
    INSERT INTO publish_result (class_id, status)
    SELECT c.class_id, ?
    FROM classes c
    LEFT JOIN publish_result p ON c.class_id = p.class_id
    WHERE p.class_id IS NULL
";
$stmt = $conn->prepare($sql_insert);
$stmt->bind_param("s", $status);
$stmt->execute();
$stmt->close();

// Step 2: Update status for ALL classes in publish_result
$sql_update = "UPDATE publish_result SET status = ?";
$stmt = $conn->prepare($sql_update);
$stmt->bind_param("s", $status);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "status" => $status]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
