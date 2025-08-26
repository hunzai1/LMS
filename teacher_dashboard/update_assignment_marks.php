<?php
require 'teachers_db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $assignmentDataId = $_POST['assignments_data_id'] ?? null;
    $marks = $_POST['marks'] ?? null;

    if (!$assignmentDataId || $marks === null) {
        echo json_encode(['status' => 'error', 'message' => 'Missing assignment data ID or marks.']);
        exit;
    }

    // Validate that marks is a number
    if (!is_numeric($marks)) {
        echo json_encode(['status' => 'error', 'message' => 'Marks must be a number.']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE assignments_data SET marks = ? WHERE assignments_data_id = ?");
    $stmt->bind_param("di", $marks, $assignmentDataId); // d = double (for decimal marks), i = int

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Marks updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
