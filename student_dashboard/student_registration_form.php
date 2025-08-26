<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "fyp";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form inputs
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $email = $_POST['students_email'];
    $contact_num = $_POST['contact_number'];
    $gender = $_POST['gender'];
    $country = $_POST['country'];
    $provience = $_POST['provience'];
    $district = $_POST['district'];
    $class_id = $_POST['class_id'];
    $password = $_POST['students_password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check for duplicate email or contact number
    $checkQuery = "SELECT * FROM students WHERE students_email = ? OR contact_num = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ss", $email, $contact_num);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        die("A student with this email or contact number already exists.");
    }

    // Handle file upload
    $profile_picture = null;
    $uploadDir = 'uploads/profile_pictures/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileName = uniqid() . '_' . basename($_FILES['profile_picture']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
            $profile_picture = $fileName;
        } else {
            die("Error uploading the profile picture.");
        }
    }

    // Insert data into students table
    $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, students_email, contact_num, gender, country, provience, district, class_id, students_password, profile_picture) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssssssssss",
        $firstname,
        $lastname,
        $email,
        $contact_num,
        $gender,
        $country,
        $provience,
        $district,
        $class_id,
        $hashed_password,
        $profile_picture
    );

    if ($stmt->execute()) {
        echo "Student registered successfully!";
    } else {
        die("Error: " . $stmt->error);
    }

    $stmt->close();
}

$conn->close();
?>
