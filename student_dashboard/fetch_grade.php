<?php
session_start();
require 'db_connection.php';

$percentage = $_GET['percentage'] ?? null;

if ($percentage === null) {
    echo json_encode(["error" => "Missing percentage"]);
    exit;
}

// ✅ Find grade where percentage fits
$sql = "SELECT grade_name FROM grades 
        WHERE ? BETWEEN min_percentage AND max_percentage 
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $percentage);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode($result ?: ["grade_name" => "N/A"]);
