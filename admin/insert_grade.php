<?php
require '../student_dashboard/db_connection.php';

$grade_name = trim($_POST['grade_name']);
$min_percentage = (int)$_POST['min_percentage'];
$max_percentage = (int)$_POST['max_percentage'];

// ❌ Validation
if ($min_percentage > $max_percentage) {
    die("❌ Min % cannot be greater than Max %");
}

// ✅ Check if grade already exists with same name
$check = $conn->prepare("SELECT grade_id FROM grades WHERE grade_name=?");
$check->bind_param("s", $grade_name);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    die("❌ Grade '$grade_name' already exists.");
}

// ✅ Check for overlapping ranges
$sql = "SELECT * FROM grades WHERE 
       (min_percentage <= ? AND max_percentage >= ?) 
    OR (min_percentage <= ? AND max_percentage >= ?)
    OR (? <= min_percentage AND ? >= max_percentage)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiiii", $max_percentage, $min_percentage, $min_percentage, $max_percentage, $min_percentage, $max_percentage);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die("❌ Overlapping range! Please choose another range.");
}

// ✅ Insert grade if valid
$sql = "INSERT INTO grades (grade_name, min_percentage, max_percentage) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $grade_name, $min_percentage, $max_percentage);

echo $stmt->execute() ? "✅ Grade added!" : "❌ Error adding grade.";
