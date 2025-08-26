<?php
// Start the session for user authentication
session_start();

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Get the logged-in teacher's ID from the session
$teacher_id = $_SESSION['teacher_id'];

// Include database connection
require 'teachers_db_connection.php';

// Prepare the SQL query to fetch teacher details
$sql = "SELECT * FROM teachers WHERE teacher_id = ?";
$stmt = $conn->prepare($sql);

// Check if the query was prepared successfully
if ($stmt) {
    // Bind the parameter and execute the query
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->get_result();
    $teacher = $result->fetch_assoc();

    // Handle case where no teacher is found
    if (!$teacher) {
        die("No teacher found with the given ID.");
    }

    // Close the statement
    $stmt->close();
} else {
    die("Error preparing SQL statement: " . $conn->error);
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../teacher_dashboard/style.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        #main-content {
            padding: 20px;
        }
        .profile-picture img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <aside>
        <h1>LMS Dashboard</h1>
        
        <nav>
            <ul>
                <li><a href="#" class="menu-item" data-content="dashboard">Dashboard</a></li>
                <li><a href="#" class="menu-item" data-content="courses">Assigned Courses</a></li>
                <li><a href="#" class="menu-item" data-content="students">Student Management</a></li>
                <li><a href="#" class="menu-item" data-content="profile">Profile Management</a></li>
                <li><a href="#" class="menu-item" data-content="notice">Notice Board</a></li>
                
                <li><a href="#" class="menu-item" data-content="about">About Us</a></li>
                <li><a href="../teacher_dashboard/logout.php">Logout</a></li>
            </ul>
        </nav>
    </aside>

    <div id="content">
        <div class="header">
            <h2>Welcome to Your Dashboard</h2>
            <div class="profile-picture">
                <?php if (!empty($teacher['profile_picture'])): ?>
                    <img src="../uploads/profile_pictures/<?php echo htmlspecialchars($teacher['profile_picture']); ?>" 
                         alt="Profile Picture">
                <?php else: ?>
                    <p>No profile picture available.</p>
                <?php endif; ?>
            </div>
        </div>
        <div id="main-content">
            <h2>Hello, <?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?>!</h2>
            <p>Select an option from the menu to display content.</p>
        </div>
    </div>
 
  <script src="test_script.js"> </script>
 
</body>
</html>
