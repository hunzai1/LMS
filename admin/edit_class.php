<?php
// Include database connection
include '../student_dashboard/db_connection.php';

// Get the JSON input from the fetch request
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Check if required data is provided
if (isset($data['class_id']) && isset($data['new_class_name'])) {
    $class_id = $data['class_id'];
    $new_class_name = $data['new_class_name'];

    // Update query (no class_description needed)
    $sql = "UPDATE classes SET class_name = ? WHERE class_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_class_name, $class_id);

    if ($stmt->execute()) {
        // Respond with success message
        echo json_encode([
            "success" => true,
            "message" => "Class updated successfully."
        ]);
    } else {
        // Respond with error message
        echo json_encode([
            "success" => false,
            "message" => "Failed to update class. Please try again later."
        ]);
    }

    $stmt->close();
} else {
    // Respond with an error if required data is missing
    echo json_encode([
        "success" => false,
        "message" => "Invalid input. Class ID and new class name are required."
    ]);
}

$conn->close();
?>
