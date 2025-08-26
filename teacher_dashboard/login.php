<?php
session_start();
require 'teachers_db_connection.php'; // Update with your connection path

// Initialize variables for error message
$error_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL query to check the teacher's credentials
    $sql = "SELECT teacher_id, teachers_password FROM teachers WHERE teachers_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verify user exists and password matches
    if ($result->num_rows > 0) {
        $teacher = $result->fetch_assoc();
        if (password_verify($password, $teacher['teachers_password'])) {
            // Set session and redirect to dashboard
            $_SESSION['teacher_id'] = $teacher['teacher_id'];
            header("Location: teacher_dashboard.php");
            exit;
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "Invalid email or password.";
    }

    // Close statement
    $stmt->close();
}



// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <link rel="stylesheet" href="style.css">

    <style>
        /* Reset basic styles */
body, html {
    margin: 0;
    padding: 0;
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    color: #333;
}

/* Center the login container */
.login-container {
    width: 100%;
    max-width: 400px;
    margin: 80px auto;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
}

/* Heading style */
.login-container h1 {
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
}

/* Error message styling */
.error-message {
    color: #d9534f;
    background: #f8d7da;
    border: 1px solid #f5c2c7;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    font-size: 14px;
}

/* Form group styling */
.form-group {
    margin-bottom: 20px;
    text-align: left;
}

/* Label styling */
.form-group label {
    font-size: 14px;
    color: #555;
    margin-bottom: 5px;
    display: block;
}

/* Input field styling */
.form-group input {
    width: 100%;
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    outline: none;
    transition: border-color 0.3s ease;
}

/* Input focus effect */
.form-group input:focus {
    border-color: #007bff;
}

/* Button styling */
.login-btn {
    background: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    width: 100%;
    transition: background 0.3s ease;
}

.login-btn:hover {
    background: #0056b3;
}

/* Add some spacing below the button */
.form-group:last-child {
    margin-bottom: 0;
}

/* Responsive design */
@media (max-width: 480px) {
    .login-container {
        padding: 15px;
    }

    .login-container h1 {
        font-size: 20px;
    }
}

    </style>
</head>
<body>
    <div class="login-container">
        <h1>Teacher Login</h1>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST" class="login-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit" class="login-btn">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
