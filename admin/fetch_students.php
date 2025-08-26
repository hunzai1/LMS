<?php
include '../student_dashboard/db_connection.php';

// ✅ Fetch first_name and last_name only
$query = "SELECT students_id, first_name, last_name FROM students";
$result = $conn->query($query);

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = [
        'students_id' => $row['students_id'],
        'full_name'   => $row['first_name'] . ' ' . $row['last_name']
    ];
}

echo json_encode($students);
?>
