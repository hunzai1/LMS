<?php
session_start();
require 'teachers_db_connection.php'; 

// ✅ Ensure teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    echo "Unauthorized access. Please log in.";
    exit;
}

$teacher_id = intval($_SESSION['teacher_id']);

// ✅ Handle profile picture upload separately
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . "/../uploads/teachers/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES['profile_picture']['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFile)) {
        $sql = "UPDATE teachers SET profile_picture = ?, updated_at = NOW() WHERE teacher_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $fileName, $teacher_id);
        $stmt->execute();
        echo "Profile picture updated successfully.";
    } else {
        echo "Failed to upload profile picture.";
    }
    exit;
}

// ✅ Define allowed fields
$allowedFields = [
    "first_name", "last_name", "teachers_email", "contact_number",
    "gender", "country", "province", "district",
    "subject_expertise", "qualification", "teachers_password"
];

// ✅ Loop through POST data and update only provided fields
foreach ($_POST as $field => $value) {
    if (in_array($field, $allowedFields)) {
        // Hash password if it's being updated
        if ($field === "teachers_password" && !empty($value)) {
            $value = password_hash($value, PASSWORD_DEFAULT);
        }

        $sql = "UPDATE teachers SET $field = ?, updated_at = NOW() WHERE teacher_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("si", $value, $teacher_id);
            $stmt->execute();
            echo ucfirst(str_replace("_", " ", $field)) . " updated successfully.<br>";
        } else {
            echo "Error preparing query: " . $conn->error . "<br>";
        }
    }
}
