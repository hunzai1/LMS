<?php
// Include database connection
include '../student_dashboard/db_connection.php';

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if teacher_id is provided
    if (isset($_POST['teacher_id'])) {
        $teacher_id = intval($_POST['teacher_id']); // Convert to integer for safety

        // Prepare the SQL query
        $sql = "DELETE FROM teachers WHERE teacher_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $teacher_id);

            // Execute the query and respond
            if ($stmt->execute()) {
                echo json_encode([
                    "success" => true,
                    "message" => "Teacher deleted successfully."
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to delete teacher. Please try again later."
                ]);
            }
            $stmt->close();
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Failed to prepare the query."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Teacher ID is required."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}

$conn->close();
?>
