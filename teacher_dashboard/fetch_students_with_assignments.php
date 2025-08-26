<?php
require 'teachers_db_connection.php';
header('Content-Type: application/json');

// ✅ Validate parameters
if (!isset($_GET['course_id'], $_GET['class_id'])) {
    echo json_encode(["error" => "Missing required parameters"]);
    exit;
}


$course_id = intval($_GET['course_id']);
$class_id  = intval($_GET['class_id']);


// ✅ Fetch only students who submitted assignments
$sql = "
    SELECT 
        ad.assignments_data_id,
        ad.students_id,
        s.first_name,
        s.last_name,
        ad.assignment_id,
        ad.assignment_uploads,
        ad.marks AS obtained_marks,
        ad.created_at AS submission_date,
        a.assignment_title,
        a.assignment_description,
        a.due_date,
        a.marks AS assignment_total_marks,
        a.assignment_file
    FROM assignments_data ad
    INNER JOIN students s 
        ON ad.students_id = s.students_id
    INNER JOIN assignments a 
        ON ad.assignment_id = a.assignment_id
    WHERE ad.course_id = ? 
      AND ad.class_id = ?
    ORDER BY s.first_name ASC, s.last_name ASC, a.assignment_id DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "Database prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("ii", $course_id, $class_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();
$conn->close();

if (empty($data)) {
    echo json_encode(["message" => "❌ No submissions found."]);
} else {
    echo json_encode($data);
}
?>
