<?php
// Start the session to access session variables
session_start();

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Get the logged-in teacher's ID from the session
$teacher_id = $_SESSION['teacher_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged-In Teacher</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .teacher-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .teacher-container h1 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #333;
        }
        .teacher-container p {
            font-size: 18px;
            color: #555;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="teacher-container">
        <h1>Welcome, Teacher!</h1>
        <p>Your Teacher ID is: <strong><?php echo htmlspecialchars($teacher_id); ?></strong></p>
        <a href="teacher_dashboard.php" class="back-btn">Go to Dashboard</a>
    </div>
</body>
</html>
