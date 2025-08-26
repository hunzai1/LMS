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

// Fetch admin details
$admin_id = $_SESSION['admin_id'];
$sql = "SELECT * FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="admin_dashboard_style.css">
    <style>
        body { margin: 0; display: flex; font-family: Arial, sans-serif; }
        aside { width: 260px; background: #333; color: #fff; min-height: 100vh; padding: 20px; }
        aside h1 { text-align: center; margin-bottom: 20px; }
        nav ul { list-style: none; padding: 0; }
        .menu-item { display: block; padding: 12px 18px; color: #fff; text-decoration: none; border-radius: 6px; transition: 0.3s; }
        .menu-item:hover { background: #444; }
        .menu-item i { margin-right: 8px; }
        #content { flex: 1; padding: 20px; overflow-y: auto; height: 100vh; }
        .settings-buttons { margin: 15px 0; }
        .settings-buttons button {
            margin: 5px; padding: 10px 15px;
            border: none; border-radius: 5px; cursor: pointer;
        }
        .btn-pass { background: #007bff; color: #fff; }
        .btn-name { background: #28a745; color: #fff; }

        .logo-container {
  text-align: center;
  margin: 15px 0;
}

.dashboard-logo {
  max-width: 100px;   /* Adjust size */
  height: auto;
  border-radius: 50%; /* Makes it circular */
}
.header {
    position: sticky;   /* Keeps header at top while scrolling */
    top: 0;
    background: #070616ff;
    padding: 10px 0;
    font-size: 20px;
    font-weight: bold;
    border-bottom: 2px solid #ddd;
    z-index: 10;
}
#heading1 {
    color: white;
}
    </style>
</head>
<body>
    <aside>

        <nav>
            <ul>
                <li><a href="#" class="menu-item" data-content="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="#" class="menu-item" data-content="teachers"><i class="fas fa-chalkboard-teacher"></i> Manage Teachers</a></li>
                <li><a href="#" class="menu-item" data-content="students"><i class="fas fa-user-graduate"></i> Manage Students</a></li>
                <li><a href="#" class="menu-item" data-content="courses"><i class="fas fa-book"></i> Manage Courses</a></li>
                <li><a href="#" class="menu-item" data-content="classes"><i class="fas fa-chalkboard"></i> Manage Classes</a></li>
                <li><a href="#" class="menu-item" data-content="results"><i class="fas fa-chalkboard"></i> Manage Results</a></li>
                <li><a href="#" class="menu-item" data-content="settings"><i class="fas fa-cogs"></i> Settings</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </aside>

    <div id="content">
        <div class="header">
                    <!-- Logo Section -->
             <div class="logo-container">
                    <img src="../uploads/logo.png" alt="Student Logo" class="dashboard-logo">
                    </div>
      <h1>Admin Dashboard</h1>
     <h2 id="heading1">Welcome, <?php echo htmlspecialchars($admin['username']); ?>!</h2>
         </div>
       
        
        <div class="main-content" id="main-content">
            <p>Please click any option from the left menu.</p>
        </div>
    </div>

<!-- Load external JS files -->
<script src="admin_script.js"></script>


</body>
</html>