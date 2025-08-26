<?php
require '../student_dashboard/db_connection.php';

// ✅ Get class_id from request (if provided)
$class_id = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;

$sql = "
    SELECT 
        s.students_id,
        CONCAT(s.first_name, ' ', s.last_name) AS student_name,
        sc.class_id,                -- ✅ include class_id
        c.class_name,
        sc.course_id,
        co.course_name,
        COALESCE(SUM(r.quiz_obtained),0) AS quiz_obtained,
        COALESCE(SUM(r.quiz_total),0) AS quiz_total,
        COALESCE(SUM(r.assignment_obtained),0) AS assignment_obtained,
        COALESCE(SUM(r.assignment_total),0) AS assignment_total,
        COALESCE(SUM(r.exam_obtained),0) AS exam_obtained,
        COALESCE(SUM(r.exam_total),0) AS exam_total
    FROM student_course_assign sc
    JOIN students s ON sc.students_id = s.students_id
    JOIN classes c ON sc.class_id = c.class_id
    JOIN courses co ON sc.course_id = co.course_id
    LEFT JOIN results r 
        ON r.student_id = s.students_id 
        AND r.course_id = sc.course_id 
        AND r.class_id = sc.class_id
    WHERE (? = 0 OR sc.class_id = ?)   -- ✅ filter by class_id if provided
    GROUP BY s.students_id, sc.course_id, sc.class_id
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $class_id, $class_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $totalObtained = $row['quiz_obtained'] + $row['assignment_obtained'] + $row['exam_obtained'];
    $totalMarks = $row['quiz_total'] + $row['assignment_total'] + $row['exam_total'];
    $percentage = $totalMarks > 0 ? round(($totalObtained / $totalMarks) * 100, 2) : 0;

    // ✅ Fetch grade from grades table
    $grade = "N/A";
    $gsql = "SELECT grade_name 
             FROM grades 
             WHERE ? BETWEEN min_percentage AND max_percentage 
             LIMIT 1";
    $gstmt = $conn->prepare($gsql);
    $gstmt->bind_param("d", $percentage);
    $gstmt->execute();
    $gResult = $gstmt->get_result()->fetch_assoc();
    if ($gResult) {
        $grade = $gResult['grade_name'];
    }
    $gstmt->close();

    $row['percentage'] = $percentage;
    $row['grade'] = $grade;

    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
$conn->close();
