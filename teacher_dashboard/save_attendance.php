<?php
session_start();
require 'teachers_db_connection.php'; // Teacher DB connection

// ✅ Ensure teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    die("❌ Unauthorized: Please log in as a teacher.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("❌ Invalid request method.");
}

$teacher_id = intval($_SESSION['teacher_id']);
$class_id   = isset($_POST['class_id']) ? intval($_POST['class_id']) : 0;
$course_id  = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
$date       = isset($_POST['date']) ? $_POST['date'] : date("Y-m-d");
$attendanceData = $_POST['attendance'] ?? [];

// ✅ Validate
if ($class_id === 0 || $course_id === 0 || empty($attendanceData)) {
    die("❌ Missing required data.");
}

$successCount = 0;
$skipCount = 0;

// ✅ Insert Attendance
$sql = "INSERT INTO attendance (student_id, class_id, course_id, teacher_id, date, status) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

foreach ($attendanceData as $student_id => $status) {
    $student_id = intval($student_id);
    $status = ($status === "present") ? "present" : "absent";

    // 🔍 Ensure student is actually assigned to this class & course
    $check = $conn->prepare("
        SELECT COUNT(*) 
        FROM student_course_assign 
        WHERE students_id = ? AND class_id = ? AND course_id = ?
    ");
    $check->bind_param("iii", $student_id, $class_id, $course_id);
    $check->execute();
    $check->bind_result($exists);
    $check->fetch();
    $check->close();

    if ($exists == 0) {
        $skipCount++;
        continue; // Skip if not enrolled
    }

    $stmt->bind_param("iiiiss", $student_id, $class_id, $course_id, $teacher_id, $date, $status);
    if ($stmt->execute()) {
        $successCount++;
    }
}

$stmt->close();
$conn->close();

echo "✅ Attendance saved successfully. $successCount records inserted, $skipCount skipped.";
?>
