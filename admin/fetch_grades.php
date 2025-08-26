<?php
require '../student_dashboard/db_connection.php';

$sql = "SELECT * FROM grades ORDER BY min_percentage DESC";
$result = $conn->query($sql);

$grades = [];
while ($row = $result->fetch_assoc()) {
    $grades[] = $row;
}

header("Content-Type: application/json");
echo json_encode($grades);
