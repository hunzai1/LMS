<?php
require 'db_connection.php';
session_start();

// Optional filter by course and class
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$class_id  = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;

$sql = "
    SELECT 
        q.quiz_id,
        q.teacher_id,
        q.course_id,
        q.class_id,
        q.title,
        q.time_limit,
        q.start_time,
        q.end_time,
        q.total_marks,
        q.quiz_type,
        q.created_at,
        qq.question_id,
        qq.question_text,
        qq.option_a,
        qq.option_b,
        qq.option_c,
        qq.option_d,
        qq.correct_option
    FROM quizzes q
    LEFT JOIN quiz_questions qq ON q.quiz_id = qq.quiz_id
    WHERE 1
";

if ($course_id > 0 && $class_id > 0) {
    $sql .= " AND q.course_id = ? AND q.class_id = ?";
}

$sql .= " ORDER BY q.quiz_id, qq.question_id ASC";

$stmt = $conn->prepare($sql);

if ($course_id > 0 && $class_id > 0) {
    $stmt->bind_param("ii", $course_id, $class_id);
}

$stmt->execute();
$result = $stmt->get_result();

$quizzes = [];
while ($row = $result->fetch_assoc()) {
    $quizId = $row['quiz_id'];

    if (!isset($quizzes[$quizId])) {
        $quizzes[$quizId] = [
            'quiz_id'      => $row['quiz_id'],
            'teacher_id'   => $row['teacher_id'],
            'course_id'    => $row['course_id'],
            'class_id'     => $row['class_id'],
            'title'        => $row['title'],
            'time_limit'   => $row['time_limit'],
            'start_time'   => $row['start_time'],
            'end_time'     => $row['end_time'],
            'total_marks'  => $row['total_marks'],
            'quiz_type'    => $row['quiz_type'],
            'created_at'   => $row['created_at'],
            'questions'    => []
        ];
    }

    if (!empty($row['question_id'])) {
        $quizzes[$quizId]['questions'][] = [
            'question_id'    => $row['question_id'],
            'question_text'  => $row['question_text'],
            'option_a'       => $row['option_a'],
            'option_b'       => $row['option_b'],
            'option_c'       => $row['option_c'],
            'option_d'       => $row['option_d'],
            'correct_option' => $row['correct_option']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode(array_values($quizzes));
