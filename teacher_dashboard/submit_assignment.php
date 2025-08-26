<?php
require 'teachers_db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id = $_POST['teacher_id'];
    $course_id = $_POST['course_id'];
    $class_id = $_POST['class_id'];
    $title = $_POST['assignment_title'];
    $description = $_POST['assignment_description'];
    $due_date = $_POST['due_date'];
    $Marks  = $_POST['marks'];

    // File upload handling
    $file_name = '';
    if (isset($_FILES['assignment_file']) && $_FILES['assignment_file']['error'] === 0) {
        $file_tmp = $_FILES['assignment_file']['tmp_name'];
        $file_name = basename($_FILES['assignment_file']['name']);
        $upload_dir = 'uploads/assignments/';
        move_uploaded_file($file_tmp, $upload_dir . $file_name);
    }

    $stmt = $conn->prepare("INSERT INTO assignments (teacher_id, course_id, class_id, assignment_title, assignment_description, due_date, marks , assignment_file) VALUES (?, ?, ?, ?, ?, ?, ? ,?)");
    $stmt->bind_param("iiisssss", $teacher_id, $course_id, $class_id, $title, $description, $due_date, $Marks , $file_name);

    if ($stmt->execute()) {
        echo "✅ Assignment submitted successfully.";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
