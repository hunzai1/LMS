<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
        }
        .registration-form {
            background-color: #fff;
            color: #333;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .registration-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .registration-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .registration-form input[type="text"],
        .registration-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .registration-form button {
            width: 100%;
            padding: 10px;
            background-color: #6a11cb;
            border: none;
            color: #fff;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }
        .registration-form button:hover {
            background-color: #2575fc;
        }
        .error, .success {
            text-align: center;
            margin-top: 10px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>

<div class="registration-form">
    <h2>Admin Registration</h2>
    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Database connection
        require '../student_dashboard/db_connection.php';

        // Retrieve form data
        $name = trim($_POST['name']);
        $password = trim($_POST['password']);

        // Validation
        if (empty($name) || empty($password)) {
            echo "<p class='error'>All fields are required.</p>";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert into database
            $sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $name, $hashed_password);

            if ($stmt->execute()) {
                echo "<p class='success'>Registration successful!</p>";
            } else {
                echo "<p class='error'>Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
            $conn->close();
        }
    }
    ?>

    <form method="POST" action="">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Enter your name" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
        <button type="submit">Register</button>
    </form>
</div>

</body>
</html>
