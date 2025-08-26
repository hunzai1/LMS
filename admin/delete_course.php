<?php
// Include database connection file
require '../student_dashboard/db_connection.php'; // Adjust the path to your database connection file

// Set the response header
header('Content-Type: application/json');

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['course_name'])) {
    echo json_encode(['success' => false, 'message' => 'Course name is required.']);
    exit;
}

$courseName = $input['course_name'];

try {
    // Prepare SQL query to delete the course
    $query = "DELETE FROM courses WHERE course_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $courseName);

    // Execute the query
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => "Course '$courseName' deleted successfully."]);
        } else {
            echo json_encode(['success' => false, 'message' => "Course '$courseName' not found."]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete the course.']);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

// Close database connection
$conn->close();
?>
