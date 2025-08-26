<?php
include '../student_dashboard/db_connection.php';

$sql = "SELECT COUNT(*) as total, SUM(status = 'on') as total_on FROM publish_result";
$result = $conn->query($sql);

$response = ["all_on" => false];

if ($result && $row = $result->fetch_assoc()) {
    $response["all_on"] = ($row['total'] > 0 && $row['total'] == $row['total_on']);
}

echo json_encode($response);
$conn->close();
?>
