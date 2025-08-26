<?php

include 'teachers_db_connection.php';

// Query to fetch all teacher details
$query = "SELECT teacher_id, first_name, last_name, teachers_email, contact_number, gender, country, province, district, subject_expertise, qualification, profile_picture FROM teachers";
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
