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
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh; /* Full screen height */
            overflow: hidden; /* Prevent body scroll */
        }

        /* Sidebar */
        aside {
            width: 250px;
            background: #333;
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            padding: 20px;
        }

        aside h1 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        aside nav ul {
            list-style: none;
            padding: 0;
        }

        aside nav ul li {
            margin: 15px 0;
        }

        aside nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        aside nav ul li a:hover {
            text-decoration: underline;
        }

        /* Content area */
        #content {
            margin-left: 250px; /* Push aside */
            display: flex;
            flex-direction: column;
            height: 100vh;
            width: calc(100% - 250px);
        }

        /* Header */
        .header {
            flex: 0 0 auto;
            padding: 15px;
            margin : 20px;
            background: #372e2e;
            border-bottom: 1px solid #ccc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
            border : 2px solid black;
               border-radius: 30px;
        
            
        }

        .profile-picture img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #ccc;
        }

        /* Main content */
        #main-content {
            flex: 1 1 auto;
            padding: 50px;
            overflow-y: auto;
            background: #f9f9f9;
        }

        /* Existing styles (students grid, etc.) */
        .students-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .student-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .student-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 14px rgba(0,0,0,0.15);
        }

        .student-photo {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .student-info {
            padding: 15px;
            font-family: Arial, sans-serif;
        }

        .student-info h3 {
            margin: 0 0 10px;
            color: #333;
        }

        .student-info p {
            margin: 4px 0;
            font-size: 14px;
            color: #555;
        }

        /* Teacher profile settings */
        .settings-container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            max-width: 700px;
            margin: auto;
            font-family: Arial, sans-serif;
        }

        .setting-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            font-size: 16px;
        }

        .setting-row:last-child {
            border-bottom: none;
        }

        .field-value {
            color: #333;
            font-weight: 500;
        }

        .profile-preview {
            width: 100px;
            height: 100px;
            margin-top: 5px;
            border: 2px solid #ddd;
            object-fit: cover;
            border-radius: 10px;
        }

        .edit-btn, .save-btn, .cancel-btn {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }

        .edit-btn {
            background: #007BFF;
            color: #fff;
        }
        .edit-btn:hover { background: #0056b3; }

        .save-btn {
            background: #28a745;
            color: #fff;
        }
        .save-btn:hover { background: #218838; }

        .cancel-btn {
            background: #dc3545;
            color: #fff;
        }
        .cancel-btn:hover { background: #b52a37; }

        .styled-input {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        #heading{
            color:  #fff;
        }

        .logo-container {
  text-align: center;
  margin: 15px 0;
}

.dashboard-logo {
  max-width: 100px;   /* Adjust size */
  height: auto;
  border-radius: 50%; /* Makes it circular */
}

              
    </style>
</head>
<body>
    <aside>
          <div class="logo-container">
    <img src="../uploads/logo.png" alt="Student Logo" class="dashboard-logo">
  </div>
        <h1>Teacher dashboard</h1>
        <nav>
            <ul>
                <li><a href="#" class="menu-item" data-content="dashboard">Dashboard</a></li>
                <li><a href="#" class="menu-item" data-content="courses">Assigned Courses</a></li>
                <li><a href="#" class="menu-item" data-content="profile">Profile Management</a></li>
                <li><a href="#" class="menu-item" data-content="notice">Notice Board</a></li>
                <li><a href="#" class="menu-item" data-content="about">About Us</a></li>
                <li><a href="#" class="menu-item" data-content="setting">Setting</a></li>
                <li><a href="../teacher_dashboard/logout.php">Logout</a></li>
            </ul>
        </nav>
    </aside>

    <div id="content">
        <div class="header">
            <h2 id="heading">Welcome to Your Dashboard</h2>
            <div class="profile-picture">
                <?php if (!empty($teacher['profile_picture'])): ?>
                    <img src="../uploads/teachers/<?php echo htmlspecialchars($teacher['profile_picture']); ?>" 
                         alt="Profile Picture" class="profile-preview">
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

    <script src="script_teachers.js"></script>
</body>
</html>
