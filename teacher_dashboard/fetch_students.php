<?php 
require 'teachers_db_connection.php';
session_start();

// ✅ Ensure teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized access. Please log in as a teacher."
    ]);
    exit;
}

$teacher_id = intval($_SESSION['teacher_id']);
$class_id   = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;
$course_id  = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

if ($class_id === 0 || $course_id === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Missing class or course ID."
    ]);
    exit;
}

// ✅ Fetch unique students for this class & course (including profile picture)
$sql = "
    SELECT DISTINCT 
           s.students_id,
           s.first_name,
           s.last_name,
           s.students_email,
           s.contact_num,
           s.gender,
           s.country,
           s.provience,
           s.district,
           s.profile_picture
    FROM students s
    INNER JOIN student_course_assign sca 
        ON sca.students_id = s.students_id
    WHERE sca.class_id = ? AND sca.course_id = ?
    ORDER BY s.first_name ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $class_id, $course_id);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    // ✅ If no picture, set a default
    if (empty($row['profile_picture'])) {
        $row['profile_picture'] = "default.png";
    }
    $students[] = $row;
}

header('Content-Type: application/json');
echo json_encode([
    "success" => true,
    "students" => $students
]);
