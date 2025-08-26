<?php
require 'teachers_db_connection.php';

$assignment_id = intval($_POST['assignment_id']);
$due_date = $_POST['due_date'];

if ($assignment_id && $due_date) {
    $stmt = $conn->prepare("UPDATE assignments SET due_date = ? WHERE assignment_id = ?");
    $stmt->bind_param("si", $due_date, $assignment_id);
    if ($stmt->execute()) {
        echo "✅ Due date updated successfully.";
    } else {
        echo "❌ Failed to update due date.";
    }
    $stmt->close();
} else {
    echo "❌ Invalid data.";
}

$conn->close();
?>
