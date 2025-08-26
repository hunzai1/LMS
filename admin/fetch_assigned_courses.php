<?php
include '../student_dashboard/db_connection.php';

// Query to fetch assigned courses along with class names and course names
$query = "
    SELECT 
        classes.class_name, 
        courses.course_name 
    FROM 
        assign_courses 
    INNER JOIN 
        classes ON assign_courses.class_id = classes.class_id 
    INNER JOIN 
        courses ON assign_courses.course_id = courses.course_id
";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; // Add each row to the data array
    }
    echo json_encode($data); // Output the data in JSON format
} else {
    echo json_encode([]); // Return an empty array if no records found
}

$conn->close();
?>
