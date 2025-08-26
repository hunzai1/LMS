<?php
// Include the database connection file
include '../student_dashboard/db_connection.php';

// Set the response header to return JSON
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data from the request body
    $input = json_decode(file_get_contents('php://input'), true);

    // Extract the teacher_id, class_id, and course_id
    $teacher_id = $input['teacher_id'] ?? null;
    $class_id = $input['class_id'] ?? null;
    $course_id = $input['course_id'] ?? null;

    // Validate that all required fields are provided
    if ($teacher_id && $class_id && $course_id) {

        // ✅ Check if ANY teacher is already assigned for this course + class
        $checkQuery = "
            SELECT * FROM teacher_assign 
            WHERE class_id = ? AND course_id = ?
        ";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param('ii', $class_id, $course_id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => '❌ A teacher is already assigned to this course and class.']);
            $checkStmt->close();
            $conn->close();
            exit;
        }
        $checkStmt->close();

        // ✅ If not assigned, insert into teacher_assign
        $stmt = $conn->prepare("
            INSERT INTO teacher_assign (teacher_id, class_id, course_id) 
            VALUES (?, ?, ?)
        ");

        if ($stmt) {
            $stmt->bind_param('iii', $teacher_id, $class_id, $course_id);

            if ($stmt->execute()) {
                // ✅ Also insert matching students into student_course_assign
                $insertStudentsQuery = "
                    INSERT INTO student_course_assign (students_id, course_id, class_id)
                    SELECT s.students_id, ?, ?
                    FROM students s
                    WHERE s.class_id = ?
                ";

                $studentStmt = $conn->prepare($insertStudentsQuery);

                if ($studentStmt) {
                    $studentStmt->bind_param('iii', $course_id, $class_id, $class_id);

                    if ($studentStmt->execute()) {
                        echo json_encode(['success' => true, 'message' => '✅ Teacher and students assigned successfully.']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Teacher assigned, but failed to assign students.']);
                    }

                    $studentStmt->close();
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to prepare student assignment query.']);
                }

            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to assign teacher.']);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare the SQL statement.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing required fields: teacher_id, class_id, or course_id.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

// Close the database connection
$conn->close();
?>
