<?php
session_start();

// Set session variable for demonstration (should come from login in a real project)
$_SESSION['user_id'] = 1;

// Include database connection
require 'db_connection.php';

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Prepare the SQL query
$sql = "SELECT * FROM students WHERE students_id = ?";
$stmt = $conn->prepare($sql);

// Bind the parameter and execute the query
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Fetch the result
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Close statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Picture</title>
</head>
<body>
<div class="profile-picture">
    <?php if (!empty($student['profile_picture'])): ?>
        <img src="../uploads/profile_pictures/<?php echo htmlspecialchars($student['profile_picture']); ?>" 
             alt="Profile Picture" 
             style="width:150px; height:150px; border-radius:50%;">
    <?php else: ?>
        <p>No profile picture available.</p>
    <?php endif; ?>
</div>
</body>
</html>
