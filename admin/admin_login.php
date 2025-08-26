<?php
// Start session
session_start();

// Database connection
require '../student_dashboard/db_connection.php'; // Replace with your database connection file

// Handle form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username exists in the database
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $admin['password'])) {
            // Set session and redirect
            $_SESSION['admin_id'] = $admin['admin_id'];
            header("Location: admin_dashboard.php"); // Replace with your admin dashboard file
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .login-container h1 {
            margin-bottom: 1rem;
            font-size: 1.8rem;
            color: #333;
        }
        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }
        .form-group label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 0.5rem;
        }
        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }
        .form-group input:focus {
            border-color: #4facfe;
            outline: none;
        }
        .submit-btn {
            background: #4facfe;
            color: white;
            padding: 0.8rem 1.2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
        }
        .submit-btn:hover {
            background: #00f2fe;
        }
        .error {
            color: red;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Admin Login</h1>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="submit-btn">Login</button>
            <a href="admin_reg.php">click here to register</a>
        </form>
    </div>
</body>
</html>
