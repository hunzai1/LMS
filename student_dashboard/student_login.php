<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "fyp";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['students_email'];
    $password = $_POST['students_password'];

    // Fetch user details from the database
    $stmt = $conn->prepare("SELECT students_id, first_name, last_name, students_password FROM students WHERE students_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $row['students_password'])) {
            // Set session variables
            $_SESSION['students_id'] = $row['students_id'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            
            // Redirect to display_profile.php
            header("Location: student_dashboard.php");
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Invalid email or password.";
    }

    $stmt->close();
}

$conn->close();
?>
