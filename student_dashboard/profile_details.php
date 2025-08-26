<?php
session_start();
require 'db_connection.php';

$user_id = $_SESSION['students_id']; // Get logged-in user's ID

// JOIN students with classes to get class_name
$query = "
    SELECT 
        students.*, 
        classes.class_name 
    FROM 
        students 
    JOIN 
        classes 
    ON 
        students.class_id = classes.class_id 
    WHERE 
        students.students_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

if ($profile) {
    echo json_encode($profile);
} else {
    echo json_encode(["error" => "Profile not found"]);
}
?>
