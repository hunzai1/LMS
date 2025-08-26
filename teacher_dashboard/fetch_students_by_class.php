<?php
include 'teachers_db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['class_id'])) {
        echo json_encode([]);
        exit;
    }

    $class_id = intval($_GET['class_id']);

    $query = "
        SELECT first_name, last_name, students_email, contact_num, gender, 
               country, provience, district
        FROM students 
        WHERE class_id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $class_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    echo json_encode($students);

    $stmt->close();
    $conn->close();
}
?>
