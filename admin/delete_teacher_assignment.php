<?php
include '../student_dashboard/db_connection.php';

// Get the input data
$data = json_decode(file_get_contents('php://input'), true);

// Debugging: Log the input data
error_log("Raw input data: " . file_get_contents('php://input'));
error_log("Decoded data: " . print_r($data, true));

if (isset($data['teacher_assign_id']) && is_numeric($data['teacher_assign_id'])) {
    $teacherAssignId = $data['teacher_assign_id'];

    // Prepare the delete query
    $query = "DELETE FROM teacher_assign WHERE teacher_assign_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $teacherAssignId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            error_log("Execution Error: " . $stmt->error);
            echo json_encode(['success' => false, 'error' => 'Failed to execute query.']);
        }

        $stmt->close();
    } else {
        error_log("Prepare Error: " . $conn->error);
        echo json_encode(['success' => false, 'error' => 'Failed to prepare query.']);
    }
} else {
    error_log("Invalid input data: " . print_r($data, true));
    echo json_encode(['success' => false, 'error' => 'Invalid input data']);
}

$conn->close();
?>
