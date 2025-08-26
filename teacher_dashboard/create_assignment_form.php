<!-- This file only returns HTML form (no PHP required in this version) -->
<form id="assignmentForm" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="teacher_id" id="teacher_id">
    <input type="hidden" name="course_id" id="course_id">
    <input type="hidden" name="class_id" id="class_id">

    <label><strong>Assignment Title:</strong></label><br>
    <input type="text" name="assignment_title" required><br><br>

    <label><strong>Description:</strong></label><br>
    <textarea name="assignment_description" rows="4" cols="50" required></textarea><br><br>

    <label><strong>Due Date:</strong></label><br>
    <input type="date" name="due_date" required><br><br>

    <label><strong>Marks for assignment</strong></label><br>
    <input type="number" name="marks" required><br><br>

    <label><strong>Upload Attachment (PDF/DOC):</strong></label><br>
    <input type="file" name="assignment_file" accept=".pdf,.doc,.docx"><br><br>

    <button type="submit">📤 Submit Assignment</button>
</form>
