<?php
// Include database connection file
require '../student_dashboard/db_connection.php'; // Adjust the path to your database connection file

// Set the response header
header('Content-Type: application/json');

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['course_id'], $input['new_course_name'])) {
    echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
    exit;
}

$courseId = $input['course_id'];
$newCourseName = $input['new_course_name'];


try {
    // Prepare SQL query to update the course
    $query = "UPDATE courses SET course_name = ? WHERE course_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssi', $newCourseName,  $courseId);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Course updated successfully."]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update the course.']);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

// Close database connection
$conn->close();
?>
