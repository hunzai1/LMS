<?php
session_start();
require 'db_connection.php';

$student_id = $_SESSION['students_id'] ?? null;
$assignment_id = $_GET['assignment_id'] ?? null;


if (!$student_id || !$assignment_id) {
    die("❌ Missing student or assignment ID.");
}

// Fetch assignment
$sql = "SELECT * FROM assignments WHERE assignment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $assignment_id);
$stmt->execute();
$result = $stmt->get_result();
$assignment = $result->fetch_assoc();

if (!$assignment) {
    die("❌ Assignment not found.");
}

$currentDate = date('Y-m-d');
$isExpired = ($currentDate > $assignment['due_date']);
?>

<h2>📄 Submit Assignment</h2>
<p><strong>Title:</strong> <?= htmlspecialchars($assignment['assignment_title']) ?></p>
<p><strong>Description:</strong> <?= htmlspecialchars($assignment['assignment_description']) ?></p>
<p><strong>Due Date:</strong> <?= $assignment['due_date'] ?></p>

<form action="submit_assignment.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="assignment_id" value="<?= $assignment_id ?>">
    <input type="file" name="assignment_file" required <?= $isExpired ? 'disabled' : '' ?>>
    <br><br>
    <button type="submit" <?= $isExpired ? 'disabled' : '' ?>>Submit Assignment</button>
</form>

<?php if ($isExpired): ?>
    <p style="color:red;"><strong>⛔ Submission closed. Due date has passed.</strong></p>
<?php endif; ?>