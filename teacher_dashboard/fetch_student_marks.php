<?php
require 'teachers_db_connection.php';
header('Content-Type: application/json');

// ---- validate
if (!isset($_GET['course_id'], $_GET['class_id'])) {
    echo json_encode(["success" => false, "error" => "Missing parameters"]);
    exit;
}

$course_id = (int)$_GET['course_id'];
$class_id  = (int)$_GET['class_id'];

// ---- fetch all results for this course & class
$sql = "SELECT r.result_id, r.student_id, r.quiz_total, r.assignment_total, r.exam_total,
               r.quiz_obtained, r.assignment_obtained, r.exam_obtained,
               s.first_name, s.last_name
        FROM results r
        JOIN students s ON r.student_id = s.students_id
        WHERE r.course_id=? AND r.class_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $course_id, $class_id);
$stmt->execute();
$res = $stmt->get_result();

$updated = [];

while ($r = $res->fetch_assoc()) {
    $student_id = (int)$r['student_id'];

    // ---- fetch aggregated data from summary
    $q = "SELECT 
              COALESCE(SUM(quiz_obtained),0) as quiz_obt_sum,
              COALESCE(SUM(quiz_total),0)    as quiz_tot_sum,
              COALESCE(SUM(total_obtained_marks),0) as assign_obt_sum,
              COALESCE(SUM(total_marks),0)   as assign_tot_sum
          FROM student_assignment_summary
          WHERE student_id=? AND course_id=? AND class_id=?";
    $st2 = $conn->prepare($q);
    $st2->bind_param("iii", $student_id, $course_id, $class_id);
    $st2->execute();
    $agg = $st2->get_result()->fetch_assoc();
    $st2->close();

    $quiz_obt_sum   = (float)$agg['quiz_obt_sum'];
    $quiz_tot_sum   = (float)$agg['quiz_tot_sum'];
    $assign_obt_sum = (float)$agg['assign_obt_sum'];
    $assign_tot_sum = (float)$agg['assign_tot_sum'];

    // ---- calculate scaled values with decimals
    $scaled_quiz = ($quiz_tot_sum > 0 && $r['quiz_total'] > 0)
        ? ($quiz_obt_sum / $quiz_tot_sum) * $r['quiz_total']
        : 0.0;

    $scaled_assign = ($assign_tot_sum > 0 && $r['assignment_total'] > 0)
        ? ($assign_obt_sum / $assign_tot_sum) * $r['assignment_total']
        : 0.0;

    $total_max = $r['quiz_total'] + $r['assignment_total'] + $r['exam_total'];
    $total_obt = $scaled_quiz + $scaled_assign + $r['exam_obtained'];
    $percentage = $total_max > 0 ? ($total_obt / $total_max) * 100 : 0.0;

    // ---- update results with decimals preserved
    $upd = $conn->prepare("UPDATE results 
                           SET quiz_obtained=?, assignment_obtained=?, percentage=?, updated_at=NOW()
                           WHERE result_id=?");
    $upd->bind_param("dddi", $scaled_quiz, $scaled_assign, $percentage, $r['result_id']);
    $upd->execute();
    $upd->close();

    $updated[] = [
        "student_id" => $student_id,
        "student" => $r['first_name']." ".$r['last_name'],
        "quiz_obtained" => $scaled_quiz,
        "assignment_obtained" => $scaled_assign,
        "percentage" => $percentage
    ];
}

$stmt->close();
$conn->close();

echo json_encode(["success" => true, "updated" => $updated]);
?>
