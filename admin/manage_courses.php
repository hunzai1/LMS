<?php
// Start the session
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Database connection
require '../student_dashboard/db_connection.php';

// Fetch courses from the database
$query = "SELECT * FROM courses";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file -->
</head>
<body>
    <h1>Manage Courses</h1>

    <!-- Add Course Form -->
    <div class="add-course">
        <h2>Add New Course</h2>
        <form action="action_button.php" method="POST">
            <label for="course">Course Name:</label>
            <input type="text" id="course" name="course" required>
            <button type="submit">Add Course</button>
        </form>
    </div>

    <!-- Course List -->
    <div class="course-list">
        <h2>Existing Courses</h2>
        <?php if ($result && $result->num_rows > 0): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Course ID</th>
                        <th>Course Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['course_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                            <td>
                                <a href="edit_course.php?course_id=<?php echo $row['course_id']; ?>">Edit</a>
                                <a href="delete_course.php?course_id=<?php echo $row['course_id']; ?>" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No courses found.</p>
        <?php endif; ?>
    </div>

    <?php
    // Close database connection
    $conn->close();
    ?>
</body>
</html>
