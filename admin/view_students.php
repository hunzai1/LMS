<?php
// backend/view_students.php
header('Content-Type: application/json; charset=utf-8');

// Database connection (already configured in your project)
include '../student_dashboard/db_connection.php';

try {
    // --- FETCH Students (class-wise) ---
    $sql = "
        SELECT 
            s.students_id,
            s.first_name,
            s.last_name,
            s.students_email,
            s.contact_num,
            s.gender,
            s.country,
            s.provience,
            s.district,
            s.class_id,
            c.class_name,
            s.profile_picture,
            s.created_at,
            s.updated_at
        FROM students s
        LEFT JOIN classes c ON c.class_id = s.class_id
        ORDER BY c.class_name ASC, s.first_name ASC, s.last_name ASC
    ";

    $result = $conn->query($sql);
    if ($result === false) {
        throw new Exception('Query error: ' . $conn->error);
    }

    $rows = [];
    while ($r = $result->fetch_assoc()) {
        $r['class_name']      = $r['class_name'] ?? '';
        $r['profile_picture'] = $r['profile_picture'] ?: 'default_avatar.png';
        $rows[] = $r;
    }

    echo json_encode($rows, JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
} finally {
    if (isset($result) && $result instanceof mysqli_result) { $result->free(); }
    if (isset($conn) && $conn instanceof mysqli) { $conn->close(); }
}
