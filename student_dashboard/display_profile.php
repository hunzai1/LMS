<?php
session_start(); // Start session if you use session for login

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "fyp";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the student is logged in
if (isset($_SESSION['students_id'])) {
    $students_id = $_SESSION['students_id'];
} else {
    die("You must be logged in to view this page.");
}

// Fetch student details
$query = "SELECT * FROM students WHERE students_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $students_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    die("Student not found.");
}

// Close statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="style2.css"> <!-- Your CSS file -->
    <style>
        .profile-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .profile-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .profile-info p {
            font-size: 16px;
            margin: 5px 0;
            color: #555;
        }
        .profile-picture img {
            display: block;
            margin: 0 auto;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-picture img.default {
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h1>Student Profile</h1>
        <div class="profile-picture">
            <?php 
            $profilePicturePath = "../uploads/profile_pictures/" . htmlspecialchars($student['profile_picture']);
            $defaultPicturePath = "default_picture.jpg";

            // Resolve the absolute path to avoid file existence issues
            $absolutePath = $_SERVER['DOCUMENT_ROOT'] . "/uploads/profile_pictures/" . htmlspecialchars($student['profile_picture']);

            if (!empty($student['profile_picture']) && file_exists($absolutePath)): ?>
                <img src="<?php echo $profilePicturePath; ?>" alt="Profile Picture">
            <?php else: ?>
                <img src="<?php echo $defaultPicturePath; ?>" alt="Default Profile Picture" class="default">
            <?php endif; ?>
        </div>

        <div class="profile-info">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($student['first_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($student['last_name']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($student['gender']); ?></p>
        </div>
    </div>
</body>
</html>
