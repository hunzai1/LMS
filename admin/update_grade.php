<?php
require '../student_dashboard/db_connection.php';

$grade_id = (int)$_POST['grade_id'];
$grade_name = trim($_POST['grade_name']);
$min_percentage = (int)$_POST['min_percentage'];
$max_percentage = (int)$_POST['max_percentage'];

// ❌ Validation
if ($min_percentage > $max_percentage) {
    die("❌ Min % cannot be greater than Max %");
}

// ✅ Check if another grade with same name exists
$check = $conn->prepare("SELECT grade_id FROM grades WHERE grade_name=? AND grade_id != ?");
$check->bind_param("si", $grade_name, $grade_id);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    die("❌ Another grade with the same name exists.");
}

// ✅ Check for overlapping ranges
$sql = "SELECT * FROM grades WHERE grade_id != ? AND (
       (min_percentage <= ? AND max_percentage >= ?) 
    OR (min_percentage <= ? AND max_percentage >= ?)
    OR (? <= min_percentage AND ? >= max_percentage)
)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiiiii", $grade_id, $max_percentage, $min_percentage, $min_percentage, $max_percentage, $min_percentage, $max_percentage);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die("❌ Overlapping range! Please choose another range.");
}

// ✅ Update grade
$sql = "UPDATE grades SET grade_name=?, min_percentage=?, max_percentage=? WHERE grade_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("siii", $grade_name, $min_percentage, $max_percentage, $grade_id);

echo $stmt->execute() ? "✅ Grade updated!" : "❌ Update failed.";
