<?php
// Include database connection
include '../student_dashboard/db_connection.php';

// Get the JSON input from the fetch request
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Check if required data is provided
if (isset($data['class_name'])) {
    $class_name = $data['class_name'];

    // Insert query
    $sql = "INSERT INTO classes (class_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $class_name);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Class added successfully."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to add class. Please try again later."
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Class name is required."
    ]);
}

$conn->close();
?>
