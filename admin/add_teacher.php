<?php
// Include database connection file
include_once '../student_dashboard/db_connection.php'; // Adjust path as necessary

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];

    // Collect form data
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['teachers_email'] ?? '';
    $contactNumber = $_POST['contact_number'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $country = $_POST['country'] ?? '';
    $province = $_POST['province'] ?? '';
    $district = $_POST['district'] ?? '';
    $subjectExpertise = $_POST['subject_expertise'] ?? '';
    $qualification = $_POST['qualification'] ?? '';
    $password = $_POST['teachers_password'] ?? '';

    // File upload handling
    $uploadDirectory = 'uploads/teacher_profiles/';
    $profilePicture = $_FILES['profile_picture']['name'] ?? '';
    $targetFile = $uploadDirectory . basename($profilePicture);

    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
        $response = ['success' => false, 'message' => 'Error uploading profile picture.'];
        echo json_encode($response);
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Prepare SQL query
    $query = "INSERT INTO teachers (
        first_name, last_name, teachers_email, contact_number, gender, country,
        province, district, subject_expertise, qualification, profile_picture, teachers_password
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";


    // Prepare and execute statement
    $stmt = $dbConnection->prepare($query);
    if ($stmt) {
        $stmt->bind_param(
            "ssssssssssss",
            $firstName,
            $lastName,
            $email,
            $contactNumber,
            $gender,
            $country,
            $province,
            $district,
            $subjectExpertise,
            $qualification,
            $profilePicture,
            $hashedPassword
        );

        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'Teacher added successfully.'];
        } else {
            $response = ['success' => false, 'message' => 'Database error: ' . $stmt->error];
        }

        $stmt->close();
    } else {
        $response = ['success' => false, 'message' => 'Error preparing statement: ' . $dbConnection->error];
    }

    echo json_encode($response);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
