<?php
// assign_course_teacher.php

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "fyp";

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch assign_course_id where (course_id with class_id exist)
    $assignCoursesQuery = "
        SELECT ac.assign_course_id, c.course_name, cl.class_name
        FROM assign_courses ac
        JOIN courses c ON ac.course_id = c.course_id
        JOIN classes cl ON ac.class_id = cl.class_id
    ";

    $assignCoursesResult = $conn->query($assignCoursesQuery);

    $assignCourses = [];
    if ($assignCoursesResult->num_rows > 0) {
        while ($row = $assignCoursesResult->fetch_assoc()) {
            $assignCourses[] = $row;
        }
    }

    // Fetch teacher_id and names
    $teachersQuery = "SELECT teacher_id, CONCAT(first_name, ' ', last_name) AS teacher_name FROM teachers";
    $teachersResult = $conn->query($teachersQuery);

    $teachers = [];
    if ($teachersResult->num_rows > 0) {
        while ($row = $teachersResult->fetch_assoc()) {
            $teachers[] = $row;
        }
    }

    // Send JSON response
    echo json_encode([
        "assignCourses" => $assignCourses,
        "teachers" => $teachers,
    ]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assign a course with class to a teacher
    $assignCourseId = $_POST['assign_course_id'];
    $teacherId = $_POST['teacher_id'];

    // Update the assign_courses table
    $updateQuery = "UPDATE assign_courses SET teacher_id = ? WHERE assign_course_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ii", $teacherId, $assignCourseId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Teacher assigned successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to assign teacher."]);
    }

    $stmt->close();
}

$conn->close();
?>
