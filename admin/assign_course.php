<?php
// Include the database connection file
require '../student_dashboard/db_connection.php';

// Set the response header to return JSON
header('Content-Type: application/json');

try {
    // Get the raw POST data
    $postData = file_get_contents('php://input');
    $data = json_decode($postData, true);

    // Validate the required fields
    if (!isset($data['class_id']) || !isset($data['course_id']) || !isset($data['teacher_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required fields: class_id, course_id, or teacher_id'
        ]);
        exit;
    }

    // Extract the values
    $classId = $conn->real_escape_string($data['class_id']);
    $courseId = $conn->real_escape_string($data['course_id']);
    $teacherId = $conn->real_escape_string($data['teacher_id']);

    // Check if the course is already assigned to the class with the teacher
    $checkQuery = "SELECT * FROM teacher_assign WHERE class_id = '$classId' AND course_id = '$courseId' AND teacher_id = '$teacherId'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'This course is already assigned to the selected class and teacher.'
        ]);
        exit;
    }

    // Insert the assignment into the database
    $insertQuery = "INSERT INTO teacher_assign (class_id, course_id, teacher_id) VALUES ('$classId', '$courseId', '$teacherId')";
    if ($conn->query($insertQuery)) {
        echo json_encode([
            'success' => true,
            'message' => 'Course successfully assigned to the class and teacher.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to assign the course. Please try again later.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
}

// Close the database connection
$conn->close();
?>
