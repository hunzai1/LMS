<?php
include 'teachers_db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ensure course_name is provided in the request
    if (!isset($_GET['course_name'])) {
        echo json_encode([]);
        exit;
    }

    $course_name = $_GET['course_name'];

    $query = "SELECT class_id FROM courses WHERE course_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $course_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(['class_id' => $row['class_id']]);
    } else {
        echo json_encode([]);
    }

    $stmt->close();
    $conn->close();
}
?>
