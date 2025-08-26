<?php
require 'teachers_db_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = intval($_POST['course_id']);
    $class_id = intval($_POST['class_id']);
    $teacher_id = $_SESSION['teacher_id']; // Logged in teacher

    // ✅ Validate teacher_id exists
    $check = $conn->prepare("SELECT 1 FROM teachers WHERE teacher_id = ?");
    $check->bind_param("i", $teacher_id);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        exit("❌ Error: Teacher ID $teacher_id does not exist in the database.");
    }

    // ✅ Validate course_id exists
    $check = $conn->prepare("SELECT 1 FROM courses WHERE course_id = ?");
    $check->bind_param("i", $course_id);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        exit("❌ Error: Course ID $course_id does not exist in the database.");
    }

    // ✅ Validate class_id exists
    $check = $conn->prepare("SELECT 1 FROM classes WHERE class_id = ?");
    $check->bind_param("i", $class_id);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        exit("❌ Error: Class ID $class_id does not exist in the database.");
    }

    // ✅ Only one quiz now
    $title = $_POST["title_1"];
    $time_limit = intval($_POST["time_limit_1"]);
    $start_time = $_POST["start_time_1"];
    $end_time = $_POST["end_time_1"];
    $total_marks = intval($_POST["total_marks_1"]); // Already calculated in JS
    $quiz_type = $_POST["quiz_type_1"];

    // Insert quiz
    $sql = "INSERT INTO quizzes 
            (teacher_id, course_id, class_id, title, time_limit, start_time, end_time, total_marks, quiz_type)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiissssis", $teacher_id, $course_id, $class_id, $title, $time_limit, $start_time, $end_time, $total_marks, $quiz_type);
    if (!$stmt->execute()) {
        exit("❌ Database Error: " . $stmt->error);
    }
    $quiz_id = $stmt->insert_id;

    // Insert questions
    $qNum = 1;
    while (isset($_POST["question_1_{$qNum}"])) {
        $question_text = $_POST["question_1_{$qNum}"];
        $option_a = $_POST["option_a_1_{$qNum}"];
        $option_b = $_POST["option_b_1_{$qNum}"];
        $option_c = $_POST["option_c_1_{$qNum}"];
        $option_d = $_POST["option_d_1_{$qNum}"];
        $correct_option = $_POST["correct_option_1_{$qNum}"];

        $sqlQ = "INSERT INTO quiz_questions 
                 (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option)
                 VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtQ = $conn->prepare($sqlQ);
        $stmtQ->bind_param("issssss", $quiz_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option);
        if (!$stmtQ->execute()) {
            exit("❌ Database Error: " . $stmtQ->error);
        }

        $qNum++;
    }

    echo "✅ Quiz and questions saved successfully!";
}
?>
