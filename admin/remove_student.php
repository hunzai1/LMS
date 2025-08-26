<?php
// remove_student.php
require '../student_dashboard/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['students_id'])) {
        $student_id = intval($_POST['students_id']);

        $stmt = $conn->prepare("DELETE FROM students WHERE students_id = ?");
        $stmt->bind_param("i", $student_id);

        if ($stmt->execute()) {
            echo "✅ Student removed successfully!";
        } else {
            echo "❌ Failed to remove student.";
        }

        $stmt->close();
    } else {
        echo "❌ No student selected.";
    }
} else {
    echo "❌ Invalid request method.";
}
