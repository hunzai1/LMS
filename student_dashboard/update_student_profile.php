<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['students_id'])) {
    echo "❌ Unauthorized access!";
    exit;
}

$students_id = $_SESSION['students_id'];

// Allowed editable fields
$allowed = [
    "first_name", "last_name", "students_email", "contact_num",
    "gender", "country", "provience", "district", "students_password"
];

$updates = [];
$params = [];
$types = "";

// ✅ Handle text fields
foreach ($allowed as $field) {
    if (!empty($_POST[$field])) {
        if ($field === "students_password") {
            // Hash password
            $updates[] = "students_password=?";
            $params[] = password_hash($_POST[$field], PASSWORD_DEFAULT);
            $types .= "s";
        } else {
            $updates[] = "$field=?";
            $params[] = $_POST[$field];
            $types .= "s";
        }
    }
}

// ✅ Handle profile picture upload
if (!empty($_FILES['profile_picture']['name'])) {
    $targetDir = "../uploads/profile_pictures/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES['profile_picture']['name']);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
        $updates[] = "profile_picture=?";
        $params[] = $fileName;
        $types .= "s";
    } else {
        echo "❌ Failed to upload picture.";
        exit;
    }
}

// ✅ If nothing was provided
if (empty($updates)) {
    echo "⚠️ No changes submitted.";
    exit;
}

// Build query
$sql = "UPDATE students SET " . implode(", ", $updates) . ", updated_at=NOW() WHERE students_id=?";
$params[] = $students_id;
$types .= "i";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "❌ Prepare failed: " . $conn->error;
    exit;
}

$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo "✅ Profile updated successfully!";
} else {
    echo "❌ Error updating profile: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
