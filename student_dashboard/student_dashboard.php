<?php
// Start the session
session_start();

// Check if student is logged in
if (!isset($_SESSION['students_id'])) {
    header("Location: student_login.html");
    exit;
}

require 'db_connection.php';

// Fetch user ID from session
$students_id = $_SESSION['students_id'];

// Prepare the SQL query
$sql = "SELECT * FROM students WHERE students_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// ✅ Corrected the variable name here
$stmt->bind_param("i", $students_id);
$stmt->execute();

$result = $stmt->get_result();
$student = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body {
    margin: 0;
    font-family: Arial, sans-serif;
    display: flex;
    height: 100vh; /* Full height */
    overflow: hidden; /* Prevent whole page scroll */
}

aside {
    width: 250px;
    background: #333;
    color: #fff;
    height: 100vh; /* Fixed full height */
    overflow-y: auto; /* Scroll if menu is long */
    position: fixed; /* Sidebar fixed */
    left: 0;
    top: 0;
}

#content {
    margin-left: 250px; /* Push content aside */
    flex: 1;
    padding: 20px;
    height: 100vh; 
    overflow-y: auto; /* ✅ Scrollable content */
    background: #f9f9f9;
    box-sizing: border-box;
}

    body {
        margin: 0;
        font-family: Arial, sans-serif;
        display: flex;
    }
    aside {
        width: 250px;
        background: #333;
        color: #fff;
        min-height: 100vh;
        padding: 20px;
    }
    aside h1 {
        text-align: center;
        margin-bottom: 20px;
    }
    nav ul {
        list-style: none;
        padding: 0;
    }
    .menu-item {
        display: block;
        padding: 12px 18px;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        transition: 0.3s;
    }
    .menu-item:hover {
        background: #444;
    }
    .menu-item i {
        margin-right: 8px;
    }
    /* Submenu */
    .has-submenu .submenu {
        display: none;
        list-style: none;
        padding-left: 20px;
        margin: 5px 0;
    }
    .submenu-item {
        display: block;
        padding: 8px 12px;
        background: #222;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
        margin: 3px 0;
        transition: background 0.3s;
    }
    .submenu-item:hover {
        background: #555;
    }
    .has-submenu.active .submenu {
        display: block;
    }
    #content {
        flex: 1;
        padding: 20px;
    }
    .header {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .page-break {
  page-break-before: always;
}


  /* Avoid breaking inside rows and sections */
  #result-section, #result-section * {
    page-break-inside: avoid;
  }

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
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-top: 5px;
    border: 2px solid #ddd;
    object-fit: cover;
}

.edit-btn {
    background: #007BFF;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s;
}
.edit-btn:hover {
    background: #0056b3;
}

.styled-input {
    padding: 6px 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    margin-top: 5px;
}

.action-btns {
    margin-top: 8px;
    display: flex;
    gap: 8px;
}

.save-btn {
    background: #28a745;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s;
}
.save-btn:hover {
    background: #218838;
}

.cancel-btn {
    background: #dc3545;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s;
}
.cancel-btn:hover {
    background: #b52a37;
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

#content {
    margin-left: 250px;     /* Push content aside */
    flex: 1;
    height: 100vh;          /* Full height */
    display: flex;
    flex-direction: column; /* Stack header + main-content */
    background: #f9f9f9;
    box-sizing: border-box;
}

/* ✅ Keep header fixed */
.header {
    flex: 0 0 auto;
    padding: 15px;
    background: #160f0fff;
    border-bottom: 1px solid #ccc;
    font-size: 18px;
    font-weight: bold;
   
}

/* ✅ Make only main-content scrollable */
#main-content {
    flex: 1;                 /* Take remaining space */
    overflow-y: auto;        /* Scrollable */
    padding: 20px;
}



</style>

</head>
<body>
  <aside>
  <!-- Logo Section -->
  <div class="logo-container">
    <img src="../uploads/logo.png" alt="Student Logo" class="dashboard-logo">
  </div>

  <h1>Student Dashboard</h1>
  
  <nav>
    <ul>
      <li><a href="#" class="menu-item" data-content="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li><a href="#" class="menu-item" data-content="courses"><i class="fas fa-chalkboard-teacher"></i> Courses</a></li>
      <li><a href="#" class="menu-item" data-content="profile"><i class="fas fa-user-graduate"></i> Profile</a></li>
      <li><a href="#" class="menu-item" data-content="notice_board"><i class="fas fa-book"></i> Notice Board</a></li>
      <li><a href="#" class="menu-item" data-content="complaints"><i class="fas fa-chalkboard"></i> Complaints</a></li>

      <!-- ✅ Submenu for Examination -->
      <li class="has-submenu">
        <a href="#" class="menu-item"><i class="fas fa-star"></i> Examination</a>
        <ul class="submenu">
          <li><a href="#" class="submenu-item" onclick="loadBtnContent('Btn1')">📊 Result</a></li>
          <li><a href="#" class="submenu-item" onclick="loadBtnContent('Btn2')">📈 Btn 2</a></li>
          <li><a href="#" class="submenu-item" onclick="loadBtnContent('Btn3')">📘 Btn 3</a></li>
        </ul>
      </li>

      <li><a href="#" class="menu-item" data-content="setting"><i class="fas fa-chart-line"></i> Setting</a></li>
      <li><a href="#" class="menu-item" data-content="about"><i class="fas fa-chart-line"></i> About Us</a></li>
      <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>
</aside>


  <div id="content">
    

    <div class="header">Welcome  <?php echo htmlspecialchars($student['first_name']); ?>
      <div class="profile-picture">
        <?php if (!empty($student['profile_picture'])): ?>
          <img src="../uploads/profile_pictures/<?php echo htmlspecialchars($student['profile_picture']); ?>" 
               alt="Profile Picture" 
               style="width:150px; height:150px; border-radius:50%;">
        <?php else: ?>
          <p>No profile picture available.</p>
        <?php endif; ?>
      </div>
    </div>

    <div id="main-content">
      <h2>Welcome!</h2>
      <p>Select an option from the menu to display content.</p>
    </div>
  </div>

  <script src="script.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

 
</body>
</html>
