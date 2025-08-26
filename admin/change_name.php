<?php
require '../student_dashboard/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);

    if (empty($first_name) || empty($last_name)) {
        echo "❌ Both first and last name are required.";
        exit;
    }

    $sql = "UPDATE students SET first_name = ?, last_name = ? WHERE students_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $first_name, $last_name, $student_id);

    if ($stmt->execute()) {
        echo "✅ Student name updated successfully.";
    } else {
        echo "❌ Error updating name.";
    }

    $stmt->close();
    $conn->close();
}
