<?php
header('Content-Type: application/json'); // ✅ Ensure JSON response

include '../student_dashboard/db_connection.php';

// Query to fetch all teacher details
$sql = "SELECT 
            teacher_id, first_name, last_name, teachers_email, 
            contact_number, gender, country, province, district, 
            subject_expertise, qualification, profile_picture 
        FROM teachers";

if ($result = $conn->query($sql)) {
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data, JSON_PRETTY_PRINT); // ✅ Prettified JSON
    $result->free();
} else {
    // ✅ Error response if query fails
    echo json_encode(["error" => "Query failed: " . $conn->error]);
}

$conn->close();
?>
