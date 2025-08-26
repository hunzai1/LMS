<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['students_id'])) {
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$students_id = $_SESSION['students_id'];
$sql = "SELECT s.*, c.class_name 
        FROM students s 
        LEFT JOIN classes c ON s.class_id = c.class_id
        WHERE s.students_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $students_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

echo json_encode($student);
?>
