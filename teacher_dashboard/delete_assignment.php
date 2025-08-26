<?php
require 'teachers_db_connection.php';

$assignment_id = intval($_POST['assignment_id']);

if ($assignment_id) {
    $stmt = $conn->prepare("DELETE FROM assignments WHERE assignment_id = ?");
    $stmt->bind_param("i", $assignment_id);
    if ($stmt->execute()) {
        echo "✅ Assignment deleted successfully.";
    } else {
        echo "❌ Failed to delete assignment.";
    }
    $stmt->close();
} else {
    echo "❌ Invalid assignment ID.";
}

$conn->close();
?>
