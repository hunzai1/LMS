<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['teacher_id'])) {
    echo json_encode(['teacher_id' => $_SESSION['teacher_id']]);
} else {
    echo json_encode(['error' => 'Not logged in']);
}
?>
