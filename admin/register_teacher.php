<?php
include '../student_dashboard/db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name       = $_POST['first_name'] ?? '';
    $last_name        = $_POST['last_name'] ?? '';
    $teachers_email   = $_POST['teachers_email'] ?? '';
    $teachers_password= $_POST['teachers_password'] ?? '';
    $contact_number   = $_POST['contact_number'] ?? '';
    $gender           = $_POST['gender'] ?? '';
    $country          = $_POST['country'] ?? '';
    $province         = $_POST['province'] ?? '';
    $district         = $_POST['district'] ?? '';
    $subject_expertise= $_POST['subject_expertise'] ?? '';
    $qualification    = $_POST['qualification'] ?? '';

    // ✅ File upload
    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../uploads/teachers/"; // relative to PHP file
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName    = time() . "_" . basename($_FILES['profile_picture']['name']);
        $destPath    = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // ✅ Save only filename, not "../uploads/..."
            $profile_picture = $fileName;
        }
    }

    // ✅ Hash password
    $hashedPassword = password_hash($teachers_password, PASSWORD_BCRYPT);

    // ✅ Insert into DB
    $stmt = $conn->prepare("
        INSERT INTO teachers 
        (first_name, last_name, teachers_email, contact_number, gender, country, province, district, subject_expertise, qualification, profile_picture, teachers_password, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");

    if ($stmt) {
        $stmt->bind_param(
            "ssssssssssss",
            $first_name, 
            $last_name, 
            $teachers_email, 
            $contact_number, 
            $gender, 
            $country, 
            $province, 
            $district, 
            $subject_expertise, 
            $qualification, 
            $profile_picture, 
            $hashedPassword
        );

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Teacher registered successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to insert teacher.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'DB error: ' . $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
