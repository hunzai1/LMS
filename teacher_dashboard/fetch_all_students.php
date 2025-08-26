<?php
include 'teachers_db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "
        SELECT students_id , first_name, last_name, students_email, contact_num, gender, 
               country, provience, district
        FROM students WHERE class_id= ?";
        
    $result = $conn->query($query);

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    echo json_encode($students);
    $conn->close();
}
?>
