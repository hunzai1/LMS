<?php
// backend/register_student.php

// Start session if needed
session_start();

// Database connection
include '../student_dashboard/db_connection.php';

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo "❌ Invalid request method.";
    exit;
}

// Collect form data safely
$first_name   = trim($_POST['first_name'] ?? '');
$last_name    = trim($_POST['last_name'] ?? '');
$email        = trim($_POST['students_email'] ?? '');
$password     = trim($_POST['students_password'] ?? '');
$contact      = trim($_POST['contact_num'] ?? '');
$gender       = trim($_POST['gender'] ?? '');
$country      = trim($_POST['country'] ?? '');
$province     = trim($_POST['province'] ?? '');
$district     = trim($_POST['district'] ?? '');
$class_id     = intval($_POST['class_id'] ?? 0);

// Validate required fields
if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || $class_id <= 0) {
    echo "⚠️ Please fill all required fields.";
    exit;
}

// Handle profile picture upload (optional)
$profile_picture = null;
if (!empty($_FILES['profile_picture']['name'])) {
    $upload_dir = "../uploads/profile_pictures/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $filename = time() . "_" . basename($_FILES['profile_picture']['name']);
    $target   = $upload_dir . $filename;

    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
        $profile_picture = $filename;
    }
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

try {
    $sql = "INSERT INTO students 
            (first_name, last_name, students_email, students_password, contact_num, gender, country, provience, district, class_id, profile_picture, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssiss",
        $first_name,
        $last_name,
        $email,
        $hashed_password,
        $contact,
        $gender,
        $country,
        $province,
        $district,
        $class_id,
        $profile_picture
    );

    if ($stmt->execute()) {
        echo "✅ Student registered successfully!";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} catch (Throwable $e) {
    echo "❌ Exception: " . $e->getMessage();
}
