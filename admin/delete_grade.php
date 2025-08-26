<?php
require '../student_dashboard/db_connection.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $sql = "DELETE FROM grades WHERE grade_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    echo $stmt->execute() ? "🗑 Grade deleted!" : "❌ Delete failed.";
} else {
    echo "❌ Invalid request.";
}
