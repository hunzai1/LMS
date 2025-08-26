<?php
include '../student_dashboard/db_connection.php';

// ✅ Get all classes with their publish status
$sql = "
    SELECT 
        c.class_id, 
        c.class_name, 
        COALESCE(p.status, 'off') AS status
    FROM classes c
    LEFT JOIN publish_result p 
        ON c.class_id = p.class_id
    ORDER BY c.class_id ASC
";

$result = $conn->query($sql);

$classes = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = [
            "class_id"   => (int)$row['class_id'],
            "class_name" => $row['class_name'],
            "status"     => $row['status']   // "on" or "off"
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($classes);

$conn->close();
?>
