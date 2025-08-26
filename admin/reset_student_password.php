<?php
// reset_student_password.php
include '../student_dashboard/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $students_id = $_POST['students_id'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    if (empty($students_id) || empty($new_password)) {
        echo "❌ Missing student ID or new password.";
        exit;
    }

    // ✅ Hash the password before storing (secure)
    $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);

    // ✅ Update query
    $stmt = $conn->prepare("UPDATE students SET students_password = ? WHERE students_id = ?");
    $stmt->bind_param("si", $hashedPassword, $students_id);

    if ($stmt->execute()) {
        echo "✅ Password reset successfully!";
    } else {
        echo "❌ Failed to reset password. Try again.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "❌ Invalid request method.";
}
