<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_lms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT course_id, course_name, course_des FROM courses";
$result = $conn->query($sql);

$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}
$conn->close();

header('Content-Type: application/json');
echo json_encode($courses);
?>
