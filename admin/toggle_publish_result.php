<?php
require '../student_dashboard/db_connection.php';
header('Content-Type: application/json');

if (!isset($_POST['class_id'])) {
    echo json_encode(["success" => false, "message" => "Missing class_id"]);
    exit;
}

$class_id = intval($_POST['class_id']);

// Check if record exists
$check = $conn->prepare("SELECT * FROM publish_result WHERE class_id = ?");
$check->bind_param("i", $class_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $new_status = ($row['status'] === 'on') ? 'off' : 'on';

    $update = $conn->prepare("UPDATE publish_result SET status = ? WHERE class_id = ?");
    $update->bind_param("si", $new_status, $class_id);
    $update->execute();

    echo json_encode(["success" => true, "status" => $new_status]);
} else {
    // Insert new record with status "on"
    $status = "on";
    $insert = $conn->prepare("INSERT INTO publish_result (class_id, status) VALUES (?, ?)");
    $insert->bind_param("is", $class_id, $status);
    $insert->execute();

    echo json_encode(["success" => true, "status" => $status]);
}
?>
