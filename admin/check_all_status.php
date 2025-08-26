<?php
include '../student_dashboard/db_connection.php';

// Count total rows
$totalQuery = $conn->query("SELECT COUNT(*) as total FROM publish_result");
$totalRows = $totalQuery->fetch_assoc()['total'];

// Count how many are ON
$onQuery = $conn->query("SELECT COUNT(*) as on_count FROM publish_result WHERE status = 'on'");
$onCount = $onQuery->fetch_assoc()['on_count'];

if ($totalRows > 0 && $onCount == $totalRows) {
    echo json_encode(["all_on" => true]); // all ON
} else {
    echo json_encode(["all_on" => false]); // at least one OFF
}

$conn->close();
?>
