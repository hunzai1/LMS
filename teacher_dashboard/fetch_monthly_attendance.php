<?php
require 'teachers_db_connection.php';
session_start();

if (!isset($_SESSION['teacher_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$class_id  = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

if ($class_id === 0 || $course_id === 0) {
    echo json_encode(["success" => false, "message" => "Missing IDs"]);
    exit;
}

$currentMonth = date("n");
$currentYear  = date("Y");

// Get students
$sql_students = "
    SELECT DISTINCT s.students_id, s.first_name, s.last_name
    FROM students s
    INNER JOIN student_course_assign sca 
        ON sca.students_id = s.students_id
    WHERE sca.class_id = ? AND sca.course_id = ?
    ORDER BY s.first_name
";
$stmt = $conn->prepare($sql_students);
$stmt->bind_param("ii", $class_id, $course_id);
$stmt->execute();
$students_res = $stmt->get_result();
$students = [];
while ($row = $students_res->fetch_assoc()) {
    $students[$row['students_id']] = $row;
}
$stmt->close();

// Get attendance
$sql_attendance = "
    SELECT student_id, DATE(date) as att_date, status
    FROM attendance
    WHERE class_id = ? AND course_id = ? 
      AND MONTH(date) = ? AND YEAR(date) = ?
";
$stmt = $conn->prepare($sql_attendance);
$stmt->bind_param("iiii", $class_id, $course_id, $currentMonth, $currentYear);
$stmt->execute();
$res_att = $stmt->get_result();

$attendanceData = [];
while ($row = $res_att->fetch_assoc()) {
    $attendanceData[$row['student_id']][$row['att_date']] = $row['status'];
}

header("Content-Type: application/json");
echo json_encode([
    "success" => true,
    "students" => array_values($students),
    "attendance" => $attendanceData,
    "daysInMonth" => cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear),
    "monthName" => date("F", mktime(0, 0, 0, $currentMonth, 1)),
    "year" => $currentYear
]);
