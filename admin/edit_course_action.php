<?php
// Start the session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Database connection
require '../student_dashboard/db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate inputs
    if (isset($_POST['course_id'], $_POST['course_name']) &&
        !empty(trim($_POST['course_id'])) &&
        !empty(trim($_POST['course_name']))) {

        $course_id = trim($_POST['course_id']);
        $course_name = trim($_POST['course_name']);

        // Prepare the SQL query to update course details
        $query = "UPDATE courses SET course_name = ? WHERE course_id = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("si", $course_name, $course_id);

            if ($stmt->execute()) {
                // Success message
                echo "<script>
                    alert('Course details updated successfully!');
                    window.history.back(); // Go back to the previous page
                </script>";
            } else {
                // Error message
                echo "<script>
                    alert('Error updating course. Please try again.');
                    window.history.back(); // Go back to the previous page
                </script>";
            }

            $stmt->close();
        } else {
            echo "<script>
                alert('Failed to prepare the database query.');
                window.history.back(); // Go back to the previous page
            </script>";
        }
    } else {
        echo "<script>
            alert('Course ID and Course Name cannot be empty.');
            window.history.back(); // Go back to the previous page
        </script>";
    }
}

// Close the database connection
$conn->close();
?>
