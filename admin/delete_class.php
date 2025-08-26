<?php
// Include database connection
include '../student_dashboard/db_connection.php';

// Get the JSON input from the fetch request
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (isset($data['class_id'])) {
    $class_id = $data['class_id'];

    // Delete query
    $sql = "DELETE FROM classes WHERE class_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $class_id);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Class deleted successfully."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to delete class. Please try again later."
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Class ID is required."
    ]);
}

$conn->close();
?>
