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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    if (isset($_POST['course']) && !empty(trim($_POST['course']))) {
        $course_name = trim($_POST['course']);

        // Prepare SQL query to insert course into the database
        $query = "INSERT INTO courses (course_name) VALUES (?)";
        $stmt = $conn->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("s", $course_name);

            if ($stmt->execute()) {
                // Success message
                echo "<script>
                    alert('Course added successfully!');
                   window.history.back();
                   
                </script>";
            } else {
                // Error message
                echo "<script>
                    alert('Error adding course. Please try again.');
                    window.history.back(); // Go back to the previous page
                </script>";
            }

            $stmt->close();
        } else {
            echo "<script>
                alert('Database query preparation failed.');
                window.history.back(); // Go back to the previous page
            </script>";
        }
    } else {
        echo "<script>
            alert('Course name cannot be empty.');
            window.history.back(); // Go back to the previous page
        </script>";
    }
}

// Close database connection
$conn->close();
?>
