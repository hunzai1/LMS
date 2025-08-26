<?php
require 'teachers_db_connection.php'; 
header("Content-Type: text/plain");

$assignments_data_id = intval($_POST['assignments_data_id'] ?? 0);
$marks = intval($_POST['marks'] ?? -1);

if ($assignments_data_id <= 0 || $marks < 0 || $marks > 100) {
    echo "❌ Invalid data provided.";
    exit;
}

$sql = "UPDATE assignments_data SET marks = ? WHERE assignments_data_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $marks, $assignments_data_id);

if ($stmt->execute()) {
    echo "✅ Marks saved successfully.";
} else {
    echo "❌ Failed to save marks.";
}

$stmt->close();
$conn->close();
?>
