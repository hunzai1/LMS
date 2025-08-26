// Add event listeners to menu items
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', async event => {
        event.preventDefault(); // Prevent default link behavior

        const content = event.target.getAttribute('data-content');
        const mainContent = document.getElementById('main-content');

        // Handle menu options
        switch (content) {
            case 'dashboard':
                showDashboard();
                break;
            case 'courses':
                await showCourses();
                break;
            case 'profile':
                await showProfile();
                break;
            case 'notice':
                renderStaticContent('Notice Board', '');
                break;
        
            case 'about':
                await About();
                break;

            case 'setting':
                await make_setting();
                
                break;
            default:
                renderStaticContent('Welcome!', 'Select an option from the menu to display content.');
        }
    });
});

// Helper function to render static content
function renderStaticContent(title, message) {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <h2>${title}</h2>
        <p>${message}</p>
        `;
}

function About() {
    const mainContent = document.getElementById("main-content");
    mainContent.innerHTML = `
        <h2>ℹ️ About Our LMS</h2>
        <p>Welcome to the Learning Management System for teachers and students.</p>
        <p>This system allows teachers to manage courses, students, and share resources.</p>
               
                    
                
    `;
}

async function make_setting() {
    const mainContent = document.getElementById("main-content");
    mainContent.innerHTML = `<h2>Loading settings...</h2>`;

    try {
        const response = await fetch("fetch_teacher_profile.php");
        if (!response.ok) throw new Error("Failed to fetch teacher profile");
        const teacher = await response.json();

        if (teacher.error) {
            mainContent.innerHTML = `<p style="color:red;">${teacher.error}</p>`;
            return;
        }

        mainContent.innerHTML = `
            <h2 style="font-size:22px; font-weight:bold; margin-bottom:20px;">⚙️ Teacher Profile Settings</h2>
            <div class="settings-container">
                ${renderSettingRow("First Name", "first_name", teacher.first_name)}
                ${renderSettingRow("Last Name", "last_name", teacher.last_name)}
                ${renderSettingRow("Email", "teachers_email", teacher.teachers_email)}
                ${renderSettingRow("Contact", "contact_number", teacher.contact_number)}
                ${renderSettingRow("Gender", "gender", teacher.gender)}
                ${renderSettingRow("Country", "country", teacher.country)}
                ${renderSettingRow("Province", "province", teacher.province)}
                ${renderSettingRow("District", "district", teacher.district)}
                ${renderSettingRow("Subject Expertise", "subject_expertise", teacher.subject_expertise)}
                ${renderSettingRow("Qualification", "qualification", teacher.qualification)}
                ${renderSettingRow("Password", "teachers_password", "********")}
                
                <div class="setting-row">
                    <div>
                        <strong>Profile Picture:</strong><br>
                        <img src="../uploads/teachers/${teacher.profile_picture || 'default.png'}" 
                             alt="Profile Picture" class="profile-preview">
                    </div>
                    <button class="edit-btn" onclick="editTeacherPicture()">Edit</button>
                </div>
            </div>
        `;
    } catch (err) {
        mainContent.innerHTML = `<p style="color:red;">⚠ ${err.message}</p>`;
    }
}

// 🔹 Helper to render a row
function renderSettingRow(label, field, value) {
    return `
        <div class="setting-row" id="row_${field}">
            <div>
                <strong>${label}:</strong> 
                <span id="value_${field}" class="field-value">${value}</span>
            </div>
            <button class="edit-btn" onclick="editTeacherField('${field}')">Edit</button>
        </div>
    `;
}

// 🔹 Edit a field
function editTeacherField(field) {
    const valueSpan = document.getElementById(`value_${field}`);
    const currentValue = valueSpan.innerText;

    let inputElement;
    if (field === "gender") {
        inputElement = `
            <select id="input_${field}" class="styled-input">
                <option value="Male" ${currentValue === "Male" ? "selected" : ""}>Male</option>
                <option value="Female" ${currentValue === "Female" ? "selected" : ""}>Female</option>
            </select>`;
    } else if (field === "teachers_password") {
        inputElement = `<input type="password" id="input_${field}" class="styled-input" placeholder="Enter new password">`;
    } else {
        inputElement = `<input type="text" id="input_${field}" class="styled-input" value="${currentValue}">`;
    }

    valueSpan.innerHTML = `
        ${inputElement}
        <div class="action-btns">
            <button class="save-btn" onclick="saveTeacherField('${field}')">Save</button>
            <button class="cancel-btn" onclick="make_setting()">Cancel</button>
        </div>
    `;
}

// 🔹 Save field
function saveTeacherField(field) {
    const newValue = document.getElementById(`input_${field}`).value;

    const formData = new FormData();
    formData.append(field, newValue);

    fetch("update_teacher_profile.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(msg => {
        alert(msg);
        make_setting();
    });
}

// 🔹 Edit profile picture
function editTeacherPicture() {
    const row = document.querySelector(".setting-row:last-child");
    row.innerHTML = `
        <form id="picForm" enctype="multipart/form-data" class="pic-form">
            <input type="file" name="profile_picture" class="styled-input" required>
            <div class="action-btns">
                <button type="submit" class="save-btn">Upload</button>
                <button type="button" class="cancel-btn" onclick="make_setting()">Cancel</button>
            </div>
        </form>
    `;

    document.getElementById("picForm").onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch("update_teacher_profile.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(msg => {
            alert(msg);
            make_setting();
        });
    };
}


// Function to display the dashboard
function showDashboard() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <h2>Dashboard</h2>
        <div class="dashboard-grid">
            <div class="dashboard-item" onclick="showCourses()">
                <i class="fas fa-book"></i>
                <p>Courses</p>
            </div>
            <div class="dashboard-item">
                <i class="fas fa-tasks"></i>
                <p>Assignments</p>
            </div>
            <div class="dashboard-item">
                <i class="fas fa-question-circle"></i>
                <p>Quizzes</p>
            </div>
            <div class="dashboard-item">
                <i class="fas fa-calendar-check"></i>
                <p>Attendance</p>
            </div>
          
            <div class="dashboard-item">
                <i class="fas fa-chart-line"></i>
                <p>Reports</p>
            </div>
            <div class="dashboard-item">
                <i class="fas fa-bullhorn"></i>
                <p>Announcements</p>
            </div>
            <div class="dashboard-item">
                <i class="fas fa-bell"></i>
                <p>Notifications</p>
            </div>
            <div class="dashboard-item" onclick="location.href='../teacher_dashboard/logout.php'">
                <i class="fas fa-sign-out-alt"></i>
                <p>Logout</p>
            </div>
        </div>`;
}

// Function to display the courses section
async function showCourses() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `<h2>Loading courses...</h2>`;

    try {
        const response = await fetch('fetch_teacher_courses.php');
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const data = await response.json();

        if (data.error) {
            console.error(data.error);
            mainContent.innerHTML = `<p>Error: ${data.error}</p>`;
            return;
        }

        if (data.courses.length === 0) {
            mainContent.innerHTML = `
                <h2>Courses</h2>
                <p>No courses assigned yet.</p>`;
            return;
        }

        mainContent.innerHTML = `
            <h2>Courses</h2>
            <p>Explore your assigned courses below:</p>
            <div id="courses-list">
                ${data.courses.map(course => createCourseItem(course)).join('')}
            </div>`;

        document.querySelectorAll('.course-item').forEach(courseDiv => {
            courseDiv.addEventListener('click', () => toggleOptions(courseDiv));
        });

    } catch (error) {
        console.error('Error fetching courses:', error);
        mainContent.innerHTML = `<p>An error occurred while loading the courses. Please try again later.</p>`;
    }
}

// Create the course item HTML
function createCourseItem(course) {
    return `
        <div class="course-item">
            <div class="course-header">
                <h3>${course.course_name}</h3>
                <p>Class: ${course.class_name}</p>
            </div>
            <div class="options" style="display: none;">
                <button class="option-button" onclick="viewStudents('${course.course_name}' , '${course.course_id}', '${course.class_id}')">View Students</button>
                <button class="option-button" onclick="makeQuizzes(${course.course_id}, ${course.class_id}, '${course.course_name}')">Make Quizzes</button>
                <button class="option-button" onclick="makeAssignment('${course.course_id}', '${course.class_id}', '${course.course_name.replace(/'/g, "\\'")}')">make Assignments</button>
                <button class="option-button" onclick="DisplayStudentsAssignments('${course.course_id}', '${course.class_id}', '${course.assignment_id || ''}')">View Assignment</button>
                <button class="option-button" onclick="showCourseOutlineForm('${course.course_id}', '${course.class_id}', '${course.course_name.replace(/'/g, "\\'")}')">Course Outline</button>
                <button class="option-button" onclick="Message('${course.course_name}', ${course.course_id}, ${course.class_id})">Messages</button>
                <button class="option-button" onclick="model(${course.course_id}, ${course.class_id}, '${course.course_name.replace(/'/g, "\\'")}')">Assign Course</button>
                <button class="option-button" onclick="Attendence('${course.course_name}', ${course.class_id}, ${course.course_id})">Attendance</button>
                <button class="option-button" onclick="Result('${course.course_name}', '${course.class_id}', '${course.course_id}')">Results</button>
            </div>
        </div>`;
}

async function Result(courseName, classId, courseId) {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `<h2>Loading results for ${courseName}...</h2>`;

    try {
        const response = await fetch(`fetch_results.php?class_id=${classId}&course_id=${courseId}`);
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const results = await response.json();
        const rows = Array.isArray(results) ? results : [];

        mainContent.innerHTML = `
            <style>
                table.results-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 15px;
                    font-size: 14px;
                }
                table.results-table th, table.results-table td {
                    border: 1px solid #444;
                    padding: 8px;
                    text-align: center;
                }
                table.results-table th {
                    background: #007BFF;
                    color: #fff;
                }
                table.results-table tr:nth-child(even) {
                    background: #f9f9f9;
                }
                table.results-table tr:hover {
                    background: #f1f1f1;
                }
                .action-button {
                    margin: 5px;
                    padding: 6px 12px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                .insert-btn { background: #28a745; color: #fff; }
                .edit-btn { background: #ffc107; color: #000; }
                .refresh-btn { background: #6c757d; color: #fff; }
                .total-form {
                    display: none;
                    margin: 15px 0;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    background: #f9f9f9;
                }
                .total-form input {
                    margin: 5px;
                    padding: 6px;
                }
            </style>

            <button class="action-button" onclick="showCourses()">🔙 Back</button>
            <button class="action-button insert-btn" onclick="document.getElementById('total-form').style.display='block'">➕ Insert Total Marks</button>
            <button class="action-button" style="background:#17a2b8; color:#fff;" onclick="fetchStudentMarks(${classId}, ${courseId})">📥 Fetch Student Marks</button>
            <button class="action-button refresh-btn" onclick="refreshStudents(${classId}, ${courseId}, '${courseName}')">🔄 Refresh Students</button>

            <div id="total-form" class="total-form">
                <h3>Insert Total Marks (Applies to All Students)</h3>
                <label>Quiz Total: <input type="number" id="quizTotalInput"></label>
                <label>Assignment Total: <input type="number" id="assignmentTotalInput"></label>
                <label>Exam Total: <input type="number" id="examTotalInput"></label>
                <button class="action-button insert-btn" onclick="submitTotals(${classId}, ${courseId})">✅ Submit</button>
            </div>

            <h2>📊 Results for ${courseName} (Class ${classId})</h2>
            
            <table class="results-table">
                <thead>
                    <tr>
                        <th>👨‍🎓 Student</th>
                        <th>📊 Quiz Total</th>
                        <th>✅ Quiz Obtained</th>
                        <th>📚 Assignment Total</th>
                        <th>📝 Assignment Obtained</th>
                        <th>🎯 Exam Total</th>
                        <th>✍️ Exam Obtained</th>
                        <th>📈 Percentage</th>
                        <th>⚙️ Action</th>
                    </tr>
                </thead>
                <tbody>
                    ${rows.map(r => {
                        const examObtained = r.exam_obtained || 0;
                        const hasExam = examObtained > 0;

                        return `
                        <tr>
                            <td>${r.first_name || ""} ${r.last_name || ""}</td>
                            <td>${r.quiz_total || 0}</td>
                            <td>${r.quiz_obtained || 0}</td>
                            <td>${r.assignment_total || 0}</td>
                            <td>${r.assignment_obtained || 0}</td>
                            <td>${r.exam_total || 0}</td>
                            <td>${examObtained || 0}</td>
                            <td>${
                                r.percentage !== null && !isNaN(r.percentage)
                                    ? parseFloat(r.percentage).toFixed(2) + "%"
                                    : "N/A"
                            }</td>
                            <td>
                                ${hasExam 
                                    ? `<button class="action-button edit-btn" onclick="editExam(${r.result_id}, ${examObtained})">✏️ Edit</button>`
                                    : `<button class="action-button insert-btn" onclick="insertExam(${r.student_id}, ${classId}, ${courseId})">➕ Insert</button>`}
                            </td>
                        </tr>`;
                    }).join('')}
                </tbody>
            </table>
        `;
    } catch (error) {
        console.error("Error loading results:", error);
        mainContent.innerHTML = `<p style="color:red;">An error occurred while loading results: ${error.message}</p>`;
    }
}

// --- NEW: Refresh Students into results table
async function refreshStudents(classId, courseId, courseName) {
    if (!confirm("🔄 This will add any missing students into results. Continue?")) return;

    try {
        const res = await fetch("refresh_students.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ class_id: classId, course_id: courseId })
        });
        const data = await res.json();

        if (data.success) {
            alert("✅ Students refreshed successfully!");
            Result(courseName, classId, courseId); // reload results
        } else {
            alert("❌ Error: " + data.error);
        }
    } catch (err) {
        console.error("Refresh failed:", err);
        alert("❌ Error refreshing students.");
    }
}


async function fetchStudentMarks(classId, courseId) {
    if (!confirm("⚡ This will fetch and update marks for all students. Continue?")) return;

    try {
        const res = await fetch(`fetch_student_marks.php?class_id=${classId}&course_id=${courseId}`);
        const data = await res.json();
        if (data.success) {
            alert(data.message);
            Result("Course", classId, courseId); // reload table
        } else {
            alert("❌ " + data.error);
        }
    } catch (err) {
        console.error("Error:", err);
        alert("❌ Failed to fetch student marks");
    }
}


// ---- new function to handle totals submission
async function submitTotals(classId, courseId) {
    const quizTotal = document.getElementById("quizTotalInput").value;
    const assignmentTotal = document.getElementById("assignmentTotalInput").value;
    const examTotal = document.getElementById("examTotalInput").value;

    if (!quizTotal && !assignmentTotal && !examTotal) {
        alert("⚠️ Please enter at least one total mark value.");
        return;
    }

    try {
        const response = await fetch("insert_totals.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({
                class_id: classId,
                course_id: courseId,
                quiz_total: quizTotal,
                assignment_total: assignmentTotal,
                exam_total: examTotal
            })
        });

        const data = await response.json();
        if (data.success) {
            alert("✅ Totals inserted successfully!");
            Result("", classId, courseId); // reload results table
        } else {
            alert("❌ Failed to insert totals: " + data.error);
        }
    } catch (err) {
        console.error("Error inserting totals:", err);
        alert("❌ Error inserting totals.");
    }
}



// 🔹 Insert new exam marks for a student
async function insertExam(studentId, classId, courseId) {
    const examObtained = prompt("Enter exam obtained marks:");
    if (examObtained === null) return; // cancelled

    try {
        const response = await fetch("add_result.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                student_id: studentId,
                course_id: courseId,
                class_id: classId,
                exam_obtained: parseInt(examObtained, 10)
            })
        });

        const data = await response.json();
        if (data.success) {
            alert("✅ Exam marks inserted successfully!");
            Result("Course", classId, courseId); // reload
        } else {
            alert("❌ Failed: " + data.error);
        }
    } catch (err) {
        console.error("Error inserting exam:", err);
        alert("❌ Error inserting exam.");
    }
}

// 🔹 Edit existing exam marks
async function editExam(resultId, currentValue) {
    const examObtained = prompt("Edit exam obtained marks:", currentValue);
    if (examObtained === null) return;

    // ✅ allow decimals
    const examValue = parseFloat(examObtained);
    if (isNaN(examValue)) {
        alert("❌ Please enter a valid number (can include decimals).");
        return;
    }

    try {
        const response = await fetch("update_exam_result.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                result_id: resultId,
                exam_obtained: examValue   // ✅ send float instead of int
            })
        });

        const data = await response.json();
        if (data.success) {
            alert("✅ Exam marks updated successfully!");
            // ✅ update UI without reloading
            const row = document.querySelector(`[data-id='${resultId}']`);
            if (row) row.textContent = examValue.toFixed(2);

            // reload results to recalc percentage
            Result("Course", data.class_id, data.course_id); 
        } else {
            alert("❌ Failed: " + data.error);
        }
    } catch (err) {
        console.error("Error updating exam:", err);
        alert("❌ Error updating exam.");
    }
}


async function updateResults(courseId, classId) {
    try {
        const res = await fetch(`update_results.php?course_id=${courseId}&class_id=${classId}`);
        const data = await res.json();

        if (data.success) {
            alert("✅ Results updated successfully!");
            Result("Updated Course", classId, courseId); // reload table
        } else {
            alert("❌ Error: " + data.error);
        }
    } catch (err) {
        console.error("❌ Failed to update results:", err);
        alert("❌ Failed to update results.");
    }
}

function manage_mark() {
    // Toggle the form
    const form = document.getElementById('marks-form');
    form.style.display = form.style.display === "none" ? "block" : "none";
}



function applyTotals() {
    const quizTotal = document.getElementById('quizTotalInput').value;
    const assignmentTotal = document.getElementById('assignmentTotalInput').value;
    const examTotal = document.getElementById('examTotalInput').value;

    // Apply values to all "total" input fields in the table (readonly)
    document.querySelectorAll('input[data-field="quiz_total"]').forEach(el => el.value = quizTotal);
    document.querySelectorAll('input[data-field="assignment_total"]').forEach(el => el.value = assignmentTotal);
    document.querySelectorAll('input[data-field="exam_total"]').forEach(el => el.value = examTotal);

    // Send totals to backend
    const classId = document.querySelector('tbody tr td:nth-child(3)')?.innerText; 
    const courseId = document.querySelector('tbody tr td:nth-child(2)')?.innerText;

    fetch("update_totals.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            class_id: classId,
            course_id: courseId,
            quiz_total: quizTotal,
            assignment_total: assignmentTotal,
            exam_total: examTotal
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("✅ Totals updated successfully!");
        } else {
            alert("❌ Failed to update totals: " + data.error);
        }
    })
    .catch(err => {
        console.error("Error updating totals:", err);
        alert("❌ Error updating totals");
    });

    // Hide form after applying
    document.getElementById('marks-form').style.display = "none";
}






function Message(courseName, courseId, classId) {
    const mainContent = document.getElementById("main-content");
    mainContent.innerHTML = `
        <button class="action-button" onclick="showCourses()">🔙 Back</button>
        <h2>💬 Chat - ${courseName}</h2>
        <p>Select a student to chat with or view your sent messages:</p>
        <div id="student-list">Loading...</div>
    `;

    fetch(`fetch_students.php?class_id=${classId}&course_id=${courseId}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                document.getElementById("student-list").innerHTML = `<p>❌ ${data.message}</p>`;
                return;
            }
            if (!data.students.length) {
                document.getElementById("student-list").innerHTML = `<p>No students found.</p>`;
                return;
            }

            let html = `<ul style="list-style:none; padding:0;">`;
            data.students.forEach(student => {
                html += `
                    <li style="margin-bottom:10px;">
                        <button class="action-button" 
                            onclick="openChat(${student.students_id}, '${student.first_name} ${student.last_name}', ${courseId}, ${classId})">
                            💬 Chat with ${student.first_name} ${student.last_name}
                        </button>
                        <button class="option-button" style="margin-left:5px;" 
                            onclick="viewSentMessages(${student.students_id}, ${courseId}, ${classId})">
                            📤 View Sent
                        </button>
                    </li>
                `;
            });
            html += `</ul>`;
            document.getElementById("student-list").innerHTML = html;
        });
}

function openChat(studentId, studentName, courseId, classId) {
    const teacherId = window.loggedInTeacherId; // from PHP session
    const mainContent = document.getElementById("main-content");

    mainContent.innerHTML = `
        <button class="action-button" onclick="Message('', ${courseId}, ${classId})">🔙 Back to Students</button>
        <h2>Chat with ${studentName}</h2>
        <div id="chat-box" style="border:1px solid #ccc; height:300px; overflow-y:auto; padding:10px; background:#f9f9f9;"></div>
        <div style="margin-top:10px;">
            <input type="text" id="chat-input" placeholder="Type a message..." style="width:80%; padding:5px;">
            <button onclick="sendChatMessage(${teacherId}, ${studentId}, ${courseId}, ${classId})">Send</button>
        </div>
    `;

    loadMessages(teacherId, studentId, courseId, classId);
    window.chatInterval = setInterval(() => loadMessages(teacherId, studentId, courseId, classId), 3000);
}

function loadMessages(teacherId, studentId, courseId, classId) {
    fetch(`fetch_messages.php?teacher_id=${teacherId}&student_id=${studentId}&course_id=${courseId}&class_id=${classId}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                document.getElementById("chat-box").innerHTML = `<p>❌ ${data.message}</p>`;
                return;
            }
            let html = "";
            data.messages.forEach(msg => {
                const isTeacher = msg.sender_type === "teacher";
                html += `
                    <div style="margin:5px 0; text-align:${isTeacher ? "right" : "left"};">
                        <span style="display:inline-block; padding:5px 10px; border-radius:5px; background:${isTeacher ? "#d1e7dd" : "#f8d7da"};">
                            ${msg.message}
                        </span>
                        <div style="font-size:0.8em; color:#666;">${msg.created_at}</div>
                    </div>
                `;
            });
            const chatBox = document.getElementById("chat-box");
            chatBox.innerHTML = html;
            chatBox.scrollTop = chatBox.scrollHeight;
        });
}
function sendChatMessage(teacherId, studentId, courseId, classId) {
    const input = document.getElementById("chat-input");
    const message = (input.value || "").trim();

    if (!teacherId || !studentId || !courseId || !classId) {
        console.error({ teacherId, studentId, courseId, classId });
        alert("Missing teacher/student/course/class id.");
        return;
    }
    if (!message) return;

    fetch("send_message.php", {
        method: "POST",
        body: new URLSearchParams({
            teacher_id: teacherId,
            student_id: studentId,
            course_id: courseId,
            class_id: classId,
            sender_type: "teacher",
            message: message
        })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) {
            console.error(data);
            alert(data.message || "Failed to send.");
            return;
        }
        input.value = "";
        loadMessages(teacherId, studentId, courseId, classId);
    })
    .catch(err => {
        console.error(err);
        alert("Network error sending message.");
    });
}


function viewSentMessages(studentId, courseId, classId) {
    const teacherId = window.loggedInTeacherId;
    const mainContent = document.getElementById("main-content");

    mainContent.innerHTML = `
        <button class="action-button" onclick="Message('', ${courseId}, ${classId})">🔙 Back to Students</button>
        <h2>📤 Sent Messages</h2>
        <div id="sent-box" style="border:1px solid #ccc; height:300px; overflow-y:auto; padding:10px; background:#f9f9f9;">Loading...</div>
    `;

    fetch(`fetch_messages.php?teacher_id=${teacherId}&student_id=${studentId}&course_id=${courseId}&class_id=${classId}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                document.getElementById("sent-box").innerHTML = `<p>❌ ${data.message}</p>`;
                return;
            }
            let html = "";
            data.messages
                .filter(msg => msg.sender_type === "teacher")
                .forEach(msg => {
                    html += `
                        <div style="margin:5px 0; text-align:right;">
                            <span style="display:inline-block; padding:5px 10px; border-radius:5px; background:#d1e7dd;">
                                ${msg.message}
                            </span>
                            <div style="font-size:0.8em; color:#666;">${msg.created_at}</div>
                        </div>
                    `;
                });
            document.getElementById("sent-box").innerHTML = html || `<p>No sent messages yet.</p>`;
        });
}


function model(courseId, classId, courseName) {
    const mainContent = document.getElementById("main-content");
    mainContent.innerHTML = `
       
        <h2>Assign / Deassign Students - ${courseName}</h2>
        <p>Loading students...</p>
    `;

    fetch(`fetch_students_with_assign_status.php?class_id=${classId}&course_id=${courseId}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                mainContent.innerHTML = `<p>❌ ${data.message}</p>`;
                return;
            }

            if (data.students.length === 0) {
                mainContent.innerHTML = `<p>No students found for this class.</p>`;
                return;
            }

            let html = `
             <button class="action-button" onclick="showCourses()">🔙 Back</button>
                <table border="1" style="width:100%; border-collapse: collapse;">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Action</th>
                    </tr>
            `;

            // Sort assigned students first
            data.students.sort((a, b) => b.is_assigned - a.is_assigned);

            data.students.forEach(st => {
                html += `
                    <tr>
                        <td>${st.first_name} ${st.last_name}</td>
                        <td>${st.students_email}</td>
                        <td>${st.contact_num}</td>
                        <td>
                            ${st.is_assigned == 1
                                ? `<button onclick="deassignStudent(${st.students_id}, ${classId}, ${courseId})">❌ Deassign</button>`
                                : `<button onclick="assignStudent(${st.students_id}, ${classId}, ${courseId})">✅ Assign</button>`}
                        </td>
                    </tr>
                `;
            });

            html += `</table>`;
            mainContent.innerHTML = html;
        });
}

function assignStudent(studentId, classId, courseId) {
    fetch("assign_student.php", {
        method: "POST",
        body: new URLSearchParams({ student_id: studentId, class_id: classId, course_id: courseId })
    })
    .then(res => res.text())
    .then(msg => {
        alert(msg);
        model(courseId, classId, ""); // Refresh
    });
}

function deassignStudent(studentId, classId, courseId) {
    fetch("deassign_student.php", {
        method: "POST",
        body: new URLSearchParams({ student_id: studentId, class_id: classId, course_id: courseId })
    })
    .then(res => res.text())
    .then(msg => {
        alert(msg);
        model(courseId, classId, ""); // Refresh
    });
}




// ✅ Attendance function

function Attendence(courseName, classId, courseId) {
    const mainContent = document.getElementById("main-content");
    mainContent.innerHTML = `<h2>Loading students for ${courseName}...</h2>`;

    // Step 1: Check if attendance already taken
    fetch(`check_attendance_today.php?class_id=${classId}&course_id=${courseId}`)
        .then(res => res.json())
        .then(checkData => {
            if (!checkData.success) {
                mainContent.innerHTML = `<p>❌ ${checkData.message}</p>`;
                return;
            }

            // Step 2: Fetch students
            fetch(`fetch_students.php?class_id=${classId}&course_id=${courseId}`)
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        mainContent.innerHTML = `<p>❌ ${data.message}</p>`;
                        return;
                    }

                    if (!data.students || data.students.length === 0) {
                        mainContent.innerHTML = `<p>No students found for this class and course.</p>`;
                        return;
                    }

                    let html = `
                        <button class="action-button" onclick="showCourses()">🔙 Back</button>
                        <button class="action-button" style="margin-left:10px;" onclick="viewMonthlyAttendance(${classId}, ${courseId})">📊 View Monthly Attendance</button>
                        <h2>Attendance - ${courseName}</h2>
                        <form id="attendanceForm">
                            <input type="hidden" name="class_id" value="${classId}">
                            <input type="hidden" name="course_id" value="${courseId}">
                            
                            <label><strong>Date:</strong></label>
                            <input type="date" name="date" value="${new Date().toISOString().split('T')[0]}" required ${checkData.alreadyTaken ? 'disabled' : ''}>
                            <br><br>

                            <table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                                <tr style="background: #f2f2f2;">
                                    <th>Student Name</th>
                                    <th>Present</th>
                                    <th>Absent</th>
                                </tr>
                    `;

                    data.students.forEach(student => {
                        const fullName = `${student.first_name} ${student.last_name}`;
                        html += `
                            <tr>
                                <td>${fullName}</td>
                                <td style="text-align:center;">
                                    <input type="radio" name="attendance[${student.students_id}]" value="present" checked ${checkData.alreadyTaken ? 'disabled' : ''}>
                                </td>
                                <td style="text-align:center;">
                                    <input type="radio" name="attendance[${student.students_id}]" value="absent" ${checkData.alreadyTaken ? 'disabled' : ''}>
                                </td>
                            </tr>
                        `;
                    });

                    html += `</table><br>`;

                    if (checkData.alreadyTaken) {
                        html += `<p style="color:green;">✅ Attendance already taken for today.</p>`;
                    } else {
                        html += `<button type="submit">💾 Save Attendance</button>`;
                    }

                    html += `</form>`;
                    mainContent.innerHTML = html;

                    if (!checkData.alreadyTaken) {
                        document.getElementById("attendanceForm").addEventListener("submit", function (e) {
                            e.preventDefault();
                            let formData = new FormData(this);

                            fetch("save_attendance.php", {
                                method: "POST",
                                body: formData
                            })
                            .then(res => res.text())
                            .then(response => {
                                alert(response);
                                showCourses();
                            })
                            .catch(err => {
                                console.error("Error saving attendance:", err);
                                alert("❌ Failed to save attendance.");
                            });
                        });
                    }
                })
                .catch(err => {
                    console.error("Error fetching students:", err);
                    mainContent.innerHTML = `<p>❌ Failed to load students.</p>`;
                });
        });
}


function viewMonthlyAttendance(classId, courseId) {
    const mainContent = document.getElementById("main-content");
    mainContent.innerHTML = `<h2>Loading monthly attendance...</h2>`;

    fetch(`fetch_monthly_attendance.php?class_id=${classId}&course_id=${courseId}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                mainContent.innerHTML = `<p>❌ ${data.message}</p>`;
                return;
            }

            const { students, attendance, daysInMonth, monthName, year } = data;

            let html = `
                <button class="action-button" onclick="Attendence('', ${classId}, ${courseId})">🔙 Back to Attendance</button>
                <h2>📊 Attendance for ${monthName} ${year}</h2>
                <table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; width:100%;">
                    <thead>
                        <tr>
                            <th>Student Name</th>
            `;

            // Header for all days
            for (let d = 1; d <= daysInMonth; d++) {
                html += `<th>${d}</th>`;
            }

            html += `</tr></thead><tbody>`;

            // Rows for each student
            students.forEach(student => {
                html += `<tr><td>${student.first_name} ${student.last_name}</td>`;

                for (let d = 1; d <= daysInMonth; d++) {
                    let dateStr = `${year}-${String(new Date(`${monthName} 1, ${year}`).getMonth() + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                    
                    if (attendance[student.students_id] && attendance[student.students_id][dateStr]) {
                        let status = attendance[student.students_id][dateStr];
                        html += `<td style="text-align:center;">${status === 'present' ? '✅' : '❌'}</td>`;
                    } else if (new Date(dateStr) > new Date()) {
                        html += `<td></td>`; // Future date
                    } else {
                        html += `<td>-</td>`; // No record
                    }
                }

                html += `</tr>`;
            });

            html += `</tbody></table>`;
            mainContent.innerHTML = html;
        })
        .catch(err => {
            mainContent.innerHTML = `<p>❌ Failed to load monthly attendance.</p>`;
            console.error(err);
        });
}



function makeQuizzes(courseId, classId, courseName) {
    const mainContent = document.getElementById('main-content');

    // Fetch existing quizzes for this course/class
    fetch(`fetch_quizzes.php?course_id=${courseId}&class_id=${classId}`)
        .then(res => res.json())
        .then(quizzes => {
            let html = `
                <button class="action-button" onclick="showCourses()">🔙 Back</button>
                <h2>📝 Create Quiz for ${courseName}</h2>
            `;

            // Show existing quizzes
            if (quizzes.length > 0) {
                html += `<h3>📋 Existing Quizzes</h3>
                <table border="1" cellpadding="5" style="width:100%; border-collapse: collapse;">
                    <tr>
                        <th>Title</th>
                        <th>Total Marks</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Actions</th>
                    </tr>`;
                quizzes.forEach(q => {
                    html += `
                        <tr>
                            <td>${q.title}</td>
                            <td>${q.total_marks}</td>
                            <td>${q.start_time}</td>
                            <td>${q.end_time}</td>
                            <td>
                                <button onclick="editQuiz(${q.quiz_id})">✏️ Edit</button>
                        
                                <button onclick="deleteQuiz(${q.quiz_id})">🗑️ Delete</button>
                            </td>
                        </tr>
                    `;
                });
                html += `</table><hr>`;
            }

            // Quiz creation form
            html += `
                <form id="quizForm">
                    <label>Marks per MCQ:</label>
                    <input type="number" id="marksPerMCQ" name="marks_per_mcq" min="1" required>

                    <label>Total Number of MCQs:</label>
                    <input type="number" id="totalMCQs" name="total_mcqs" min="1" required>

                    <label>Title:</label>
                    <input type="text" name="title_1" required>
                    
                    <label>Time Limit (minutes):</label>
                    <input type="number" name="time_limit_1" required>

                    <label>Start Time:</label>
                    <input type="datetime-local" name="start_time_1" required>

                    <label>End Time:</label>
                    <input type="datetime-local" name="end_time_1" required>

                    <input type="hidden" name="quiz_type_1" value="Choose the Best Answer">
                    <input type="hidden" id="totalMarksField" name="total_marks_1" value="0">

                    <h4>Questions</h4>
                    <div id="questions_1"></div>

                    <p><strong>Total Marks:</strong> <span id="totalMarksDisplay">0</span></p>

                    <input type="hidden" name="quiz_count" value="1">
                    <input type="hidden" name="course_id" value="${courseId}">
                    <input type="hidden" name="class_id" value="${classId}">
                    <button type="submit" class="submit-button">✅ Save Quiz</button>
                </form>
            `;

            mainContent.innerHTML = html;

            // Auto-generate MCQs when user enters number
            document.getElementById('totalMCQs').addEventListener('input', function () {
                let total = parseInt(this.value) || 0;
                let container = document.getElementById('questions_1');
                container.innerHTML = "";
                for (let i = 1; i <= total; i++) {
                    container.innerHTML += generateMCQInputs(1, i);
                }
                updateTotalMarks();
            });

            // Recalculate marks dynamically
            document.getElementById('quizForm').addEventListener('input', updateTotalMarks);

            // Submit quiz form
            document.getElementById('quizForm').addEventListener('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);

                fetch('save_quiz.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.text())
                .then(response => {
                    alert(response);
                    makeQuizzes(courseId, classId, courseName); // Reload quizzes after saving
                })
                .catch(err => console.error("❌ Error saving quiz:", err));
            });
        })
        .catch(err => console.error("❌ Error fetching quizzes:", err));
}

function updateTotalMarks() {
    let marksPerMCQ = parseInt(document.getElementById('marksPerMCQ').value) || 0;
    let totalMCQs = parseInt(document.getElementById('totalMCQs').value) || 0;
    let totalMarks = marksPerMCQ * totalMCQs;
    document.getElementById('totalMarksDisplay').textContent = totalMarks;
    document.getElementById('totalMarksField').value = totalMarks;
}

function generateMCQInputs(quizIndex, qNum) {
    return `
        <div class="mcq-question">
            <label>Question ${qNum}:</label>
            <input type="text" name="question_${quizIndex}_${qNum}" required>

            <label>Option A:</label>
            <input type="text" name="option_a_${quizIndex}_${qNum}" required>

            <label>Option B:</label>
            <input type="text" name="option_b_${quizIndex}_${qNum}" required>

            <label>Option C:</label>
            <input type="text" name="option_c_${quizIndex}_${qNum}" required>

            <label>Option D:</label>
            <input type="text" name="option_d_${quizIndex}_${qNum}" required>

            <label>Correct Option:</label>
            <select name="correct_option_${quizIndex}_${qNum}">
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>
        </div>
    `;
}


function deleteQuiz(quizId) {
    if (!confirm("Are you sure you want to delete this quiz?")) return;
    fetch(`delete_quiz.php?id=${quizId}`)
        .then(res => res.text())
        .then(msg => {
            alert(msg);
            location.reload();
        });
}

function editQuiz(quizId) {
    fetch(`fetch_quiz_details.php?id=${quizId}`)
        .then(res => res.json())
        .then(quiz => {
            if (quiz.error) {
                alert(quiz.error);
                return;
            }

            let html = `
                <button class="action-button" onclick="showCourses()">🔙 Back</button>
                <h2>✏️ Edit Quiz: ${quiz.title}</h2>
                <form id="editQuizForm">
                    <input type="hidden" name="quiz_id" value="${quiz.quiz_id}">
                    <label>Title:</label>
                    <input type="text" name="title" value="${quiz.title}" required>

                    <label>Time Limit (minutes):</label>
                    <input type="number" name="time_limit" value="${quiz.time_limit}" required>

                    <label>Start Time:</label>
                    <input type="datetime-local" name="start_time" value="${quiz.start_time.replace(' ', 'T')}" required>

                    <label>End Time:</label>
                    <input type="datetime-local" name="end_time" value="${quiz.end_time.replace(' ', 'T')}" required>

                    <label>Total Marks:</label>
                    <input type="number" name="total_marks" value="${quiz.total_marks}" required>

                    <h4>Questions</h4>
            `;

            quiz.questions.forEach((q, index) => {
                let num = index + 1;
                html += `
                    <input type="hidden" name="question_id_${num}" value="${q.question_id}">
                    <label>Question ${num}:</label>
                    <input type="text" name="question_${num}" value="${q.question_text}" required>

                    <label>Option A:</label>
                    <input type="text" name="option_a_${num}" value="${q.option_a}" required>

                    <label>Option B:</label>
                    <input type="text" name="option_b_${num}" value="${q.option_b}" required>

                    <label>Option C:</label>
                    <input type="text" name="option_c_${num}" value="${q.option_c}" required>

                    <label>Option D:</label>
                    <input type="text" name="option_d_${num}" value="${q.option_d}" required>

                    <label>Correct Option:</label>
                    <select name="correct_option_${num}">
                        <option value="A" ${q.correct_option === "A" ? "selected" : ""}>A</option>
                        <option value="B" ${q.correct_option === "B" ? "selected" : ""}>B</option>
                        <option value="C" ${q.correct_option === "C" ? "selected" : ""}>C</option>
                        <option value="D" ${q.correct_option === "D" ? "selected" : ""}>D</option>
                    </select>
                    <hr>
                `;
            });

            html += `
                    <button type="submit" class="submit-button">💾 Save Changes</button>
                </form>
            `;

            document.getElementById('main-content').innerHTML = html;

            // Handle form submission
            document.getElementById('editQuizForm').addEventListener('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                fetch('update_quiz.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.text())
                .then(response => {
                    alert(response);
                    showCourses();
                })
                .catch(err => console.error("❌ Error updating quiz:", err));
            });
        })
        .catch(err => console.error("❌ Error fetching quiz details:", err));
}


// Show assignment form
function makeAssignment(courseId, classId, courseName) {
    const mainContent = document.getElementById('main-content');

    fetch('create_assignment_form.php')
        .then(response => response.text())
        .then(formHtml => {
            mainContent.innerHTML = `
                <button class="action-button" onclick="showCourses()">🔙 Back</button>
                
                
                <h2>📝 Make Assignment for ${courseName}</h2>
                ${formHtml}
                <div id="existing-assignments">
                    <h3>📂 Existing Assignments</h3>
                    <p>Loading assignments...</p>
                </div>
            `;

            document.getElementById('course_id').value = courseId;
            document.getElementById('class_id').value = classId;

            fetch('get_teacher_id.php')
                .then(res => res.json())
                .then(data => {
                    if (!data.teacher_id) {
                        alert('❌ Teacher not logged in.');
                        return;
                    }
                    document.getElementById('teacher_id').value = data.teacher_id;

                    // Fetch assignments
                    loadAssignments(data.teacher_id, courseId, classId);

                    // Handle form submit
                    document.getElementById('assignmentForm').addEventListener('submit', function (e) {
                        e.preventDefault();
                        const formData = new FormData(this);

                        fetch('submit_assignment.php', {
                            method: 'POST',
                            body: formData
                        })
                            .then(res => res.text())
                            .then(response => {
                                alert(response);
                                loadAssignments(data.teacher_id, courseId, classId);
                            })
                            .catch(err => console.error(err));
                    });
                });
        })
        .catch(error => {
            console.error('Error loading form:', error);
            mainContent.innerHTML = '<p>❌ Could not load form.</p>';
        });
}

// Load assignments and display table
function loadAssignments(teacherId, courseId, classId) {
    fetch(`get_teacher_assignments.php?teacher_id=${teacherId}&course_id=${courseId}&class_id=${classId}`)
        .then(res => res.json())
        .then(assignmentsData => {
            const container = document.getElementById('existing-assignments');
            if (!assignmentsData.success || assignmentsData.assignments.length === 0) {
                container.innerHTML = `<p>ℹ No assignments found.</p>`;
                return;
            }

            let tableHTML = `
                <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width:100%;">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Due Date</th>
                            <th>File</th>
                            <th> Marks </th>
                            <th>Actions</th>
                            
                        </tr>
                    </thead>
                    <tbody>
            `;

            assignmentsData.assignments.forEach(a => {
                tableHTML += `
                    <tr>
                        <td>${a.assignment_title}</td>
                        <td>${a.assignment_description}</td>
                        <td>
                            <input type="date" value="${a.due_date}" id="due-${a.assignment_id}">
                        </td>
                        <td>${a.assignment_file ? `<a href="${a.file_path}" target="_blank">Download</a>` : 'No file'}</td>
                        <td> ${a.marks} </td>
                        <td>
                            <button onclick="updateDueDate(${a.assignment_id})">💾 Save Date</button>
                            <button onclick="deleteAssignment(${a.assignment_id})">🗑 Delete</button>
                        </td>
                        
                    </tr>
                `;
            });

            tableHTML += `</tbody></table>`;
            container.innerHTML = tableHTML;
        });
}

// Update due date
function updateDueDate(assignmentId) {
    const newDate = document.getElementById(`due-${assignmentId}`).value;
    fetch(`update_assignment_date.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `assignment_id=${assignmentId}&due_date=${newDate}`
    })
        .then(res => res.text())
        .then(msg => alert(msg))
        .catch(err => console.error(err));
}

// Delete assignment
function deleteAssignment(assignmentId) {
    if (!confirm("Are you sure you want to delete this assignment?")) return;

    fetch(`delete_assignment.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `assignment_id=${assignmentId}`
    })
        .then(res => res.text())
        .then(msg => {
            alert(msg);
            location.reload(); // reload to refresh assignments
        })
        .catch(err => console.error(err));
}

// Show course outline form
function showCourseOutlineForm(courseId, classId, courseName) {
    const mainContent = document.getElementById('main-content');

    fetch(`get_uploaded_outline.php?course_id=${courseId}&class_id=${classId}`)
        .then(res => res.json())
        .then(data => {
            let fileDisplay = data.exists
                ? `<p><strong>📎 Existing File:</strong> <a href="${data.path}" target="_blank">View / Download</a></p>`
                : `<p><strong>No course outline uploaded yet.</strong></p>`;

            mainContent.innerHTML = `
                <button class="action-button" onclick="showCourses()">🔙 Back</button>
                <h2>Upload Course Outline for: ${courseName}</h2>
                ${fileDisplay}
                <form id="outlineUploadForm">
                    <input type="file" name="course_outline" accept=".pdf,.docx" required /><br><br>
                    <input type="hidden" name="course_id" value="${courseId}" />
                    <input type="hidden" name="class_id" value="${classId}" />
                    <button type="submit">Upload / Replace</button>
                </form>
                <div id="uploadStatus"></div>
            `;

            const form = document.getElementById('outlineUploadForm');
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(form);

                fetch('upload_course_outline.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(response => {
                    const statusDiv = document.getElementById('uploadStatus');
                    if (response.success) {
                        statusDiv.innerHTML = `<p style="color:green;">✅ ${response.message}</p>`;
                        showCourseOutlineForm(courseId, classId, courseName); // Refresh
                    } else {
                        statusDiv.innerHTML = `<p style="color:red;">❌ ${response.message}</p>`;
                    }
                })
                .catch(error => {
                    document.getElementById('uploadStatus').innerHTML = `<p style="color:red;">Error uploading file.</p>`;
                    console.error(error);
                });
            });
        });
}



// Function to show the modal for assigning students to a course
function showAssignModal(courseId, classId, courseName) {
    // Fetch students and their assignment status for the selected course
    fetch(`fetch_students_assign.php?class_id=${classId}&course_id=${courseId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            const students = data.students;
            let modalContent = `
                <h3>Manage Assignments for Course: ${courseName}</h3>
                <div class="students-list">
                    ${students.map(student => `
                        <div class="student-item">
                            <p>${student.first_name} ${student.last_name} (${student.student_id})</p>
                            <button class="assign-button" 
                                onclick="toggleStudentAssignment(${student.student_id}, ${courseId}, ${student.is_assigned})">
                                ${student.is_assigned ? 'Deassign' : 'Assign'}
                            </button>
                        </div>`).join('')}
                </div>`;
            
            // Display the modal with the student assignment list
            showModal(modalContent); // Assume `showModal` displays the modal with the provided content
        })
        .catch(error => console.error('Error fetching students:', error));
}

// Function to assign or deassign a student
function toggleStudentAssignment(studentId, courseId, isAssigned) {
    const action = isAssigned ? 'deassign' : 'assign';

    // Send request to assign or deassign the student
    fetch('/assign_student_to_course.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ student_id: studentId, course_id: courseId, action })
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(`Student ${isAssigned ? 'deassigned' : 'assigned'} successfully.`);
                // Refresh the modal content to reflect updated assignment status
                showAssignModal(courseId, classId, courseName);
            } else {
                alert(result.error || 'Failed to update assignment.');
            }
        })
        .catch(error => console.error('Error updating assignment:', error));
}






// Toggle options visibility for a course
function toggleOptions(courseDiv) {
    const options = courseDiv.querySelector('.options');
    options.style.display = options.style.display === 'none' ? 'block' : 'none';
}

// Function to handle option clicks
function handleOption(courseName, option) {
    alert(`You selected ${option} for course: ${courseName}`);
}

// Function to fetch and display the teacher's profile
async function showProfile() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `<h2>Loading profile...</h2>`;

    try {
        const response = await fetch('fetch_teacher_profile.php');
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const data = await response.json();

        if (data.error) {
            console.error(data.error);
            mainContent.innerHTML = `<p>Error: ${data.error}</p>`;
        } else {
            mainContent.innerHTML = `
                <div id="profile-container">
                    <img id="profile-picture" src="uploads/teacher_profiles/${data.profile_picture}" alt="Profile Picture" style="width: 150px; height: 150px; border-radius: 50%;">
                    <h2>${data.first_name} ${data.last_name}</h2>
                    <p>Email: ${data.teachers_email}</p>
                    <p>Contact: ${data.contact_number}</p>
                    <p>Gender: ${data.gender}</p>
                    <p>Country: ${data.country}</p>
                    <p>Province: ${data.province}</p>
                    <p>District: ${data.district}</p>
                    <p>Subject Expertise: ${data.subject_expertise}</p>
                    <p>Qualification: ${data.qualification}</p>
                </div>`;
        }
    } catch (error) {
        console.error('Error fetching profile:', error);
        mainContent.innerHTML = `<p>An error occurred while loading the profile. Please try again later.</p>`;
    }
}

// ✅ Display Students' Submitted Assignments and Allow Marks Input
function DisplayStudentsAssignments(course_id, class_id, assignment_id = null) {
    if (!course_id || !class_id) {
        alert("❌ Missing course_id or class_id");
        return;
    }

    if (!assignment_id) {
        fetch(`get_latest_assignment.php?course_id=${course_id}&class_id=${class_id}`)
            .then(res => res.json())
            .then(data => {
                if (data.assignment_id) {
                    loadAssignmentSubmissions(course_id, class_id, data.assignment_id);
                } else {
                    document.getElementById("main-content").innerHTML = `<p>❌ No assignment found for this course/class.</p>`;
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById("main-content").innerHTML = `<p>❌ Error fetching assignment.</p>`;
            });
    } else {
        loadAssignmentSubmissions(course_id, class_id, assignment_id);
    }
}
function loadAssignmentSubmissions(course_id, class_id, assignment_id) {
    const mainContent = document.getElementById("main-content");

    // ✅ Inject internal CSS (only once)
    if (!document.getElementById("assignment-styles")) {
        const style = document.createElement("style");
        style.id = "assignment-styles";
        style.innerHTML = `
            .assignment-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 15px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                border-radius: 8px;
                overflow: hidden;
                font-family: Arial, sans-serif;
            }
            .assignment-table th {
                background: #4CAF50;
                color: white;
                text-align: center;
                padding: 12px;
                font-size: 14px;
            }
            .assignment-table td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: center;
                font-size: 14px;
            }
            .assignment-table tr:nth-child(even) { background-color: #f9f9f9; }
            .assignment-table tr:hover { background-color: #f1f1f1; }
            .file-link { text-decoration: none; color: #2196F3; font-weight: bold; }
            .file-link:hover { text-decoration: underline; }
            .badge.total-marks {
                background: #ff9800; color: white;
                padding: 4px 8px; border-radius: 6px; font-size: 13px;
            }
            .editable-input, .readonly-input {
                padding: 5px; border: 1px solid #ccc; border-radius: 6px;
                width: 80px; text-align: center;
            }
            .readonly-input { background: #eee; color: gray; }
            .status-done { color: green; font-weight: bold; }
            .save-btn, .edit-btn {
                padding: 5px 10px; border: none; border-radius: 6px;
                cursor: pointer; font-size: 13px; font-weight: bold;
            }
            .save-btn { background: #2196F3; color: white; }
            .edit-btn { background: #FF9800; color: white; }
            .save-btn:hover { background: #1976D2; }
            .edit-btn:hover { background: #e68900; }
            .desc-cell {
                text-align: left; font-size: 13px;
                color: #444; max-width: 250px;
            }
        `;
        document.head.appendChild(style);
    }

    mainContent.innerHTML = `
     <button class="action-button" onclick="showCourses()">🔙 Back</button>
     <h2 class="section-heading">📄 Students' Assignments</h2>
     <div id="assignment-list" style="margin-top:15px;">⏳ Loading...</div>`;

    fetch(`fetch_students_with_assignments.php?course_id=${course_id}&class_id=${class_id}&assignment_id=${assignment_id}`)
        .then(res => res.json())
        .then(data => {
            const list = document.getElementById("assignment-list");

            if (!Array.isArray(data) || data.length === 0) {
                list.innerHTML = `<p style="color:red; font-weight:bold;">❌ No submissions found.</p>`;
                return;
            }

            let html = `
                <table class="assignment-table">
                    <thead>
                        <tr>
                            <th>👨‍🎓 Student</th>
                            <th>📎 File</th>
                            <th>🎯 Total Marks</th>
                            <th>📝 Obtained</th>
                            <th>📖 Description</th>
                            <th>✅ Action</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.forEach(item => {
                const isMarked = item.marks !== null && item.marks !== "";

                html += `
                    <tr id="row-${item.assignments_data_id}">
                        <td><b>${item.first_name} ${item.last_name}</b></td>
                        <td>
                           <a href="../student_dashboard/${item.assignment_uploads}" 
   target="_blank" class="file-link">📄 download</a>


                        </td>
                        <td><span class="badge total-marks">${item.assignment_total_marks}</span></td>
                        <td>
                            <input type="number" id="marks-${item.assignments_data_id}" 
                                value="${item.marks || ''}" 
                                ${isMarked ? 'readonly class="readonly-input"' : 'class="editable-input"'} 
                                placeholder="Enter marks" min="0" max="${item.assignment_total_marks}">
                        </td>
                        <td class="desc-cell">${item.assignment_description}</td>
                        <td>
                            ${isMarked 
                                ? `<span class="status-done">✔️ Marked</span> <button class="edit-btn" onclick="enableEditMarks(${item.assignments_data_id})">✏️ Edit</button>` 
                                : `<button class="save-btn" onclick="saveAssignmentMarks(${item.assignments_data_id})">💾 Save</button>`}
                        </td>
                    </tr>

                   
                `;
            });

            html += `</tbody></table>
             <button class="action-button" onclick="loadStudentSummary(${course_id}, ${class_id})">📊 Result</button>

             
             `;
            list.innerHTML = html;
        })
        .catch(error => {
            console.error("❌ Error fetching assignments:", error);
            mainContent.innerHTML = `<p style="color:red;">❌ Error loading assignments.</p>`;
        });
}




function loadStudentSummary(course_id, class_id) {
    const mainContent = document.getElementById("main-content");
    mainContent.innerHTML = `
        <button class="action-button" onclick="showCourses()">🔙 Back</button>
        <h2 class="section-heading">📊 Student Performance Summary</h2>
        <div id="summary-list">⏳ Loading...</div>`;

    fetch(`fetch_student_summary.php?course_id=${course_id}&class_id=${class_id}`)
        .then(res => res.json())
        .then(data => {
            const list = document.getElementById("summary-list");

            if (!data.success || data.students.length === 0) {
                list.innerHTML = `
                <button class="action-button" onclick="updateStudentSummary(${course_id}, ${class_id})">🔄 Update Summary</button>
                <p style="color:red; font-weight:bold;">❌ No summary data found.</p>`;
                return;
            }

            let html = `
                <table class="assignment-table">
                    <thead>
                        <tr>
                            <th>👨‍🎓 Student</th>
                            <th>📝 Assignment Obtained</th>
                            <th>📝 Assignment Total</th>
                            <th>📝 Assignment %</th>
                            <th>🧮 Quiz Obtained</th>
                            <th>🧮 Quiz Total</th>
                            <th>📈 Overall %</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.students.forEach(st => {
                const assignmentPercent = st.total_marks > 0 
                    ? ((st.total_obtained_marks / st.total_marks) * 100).toFixed(2) + "%" 
                    : "N/A";

                const quizPercent = st.quiz_total > 0
                    ? ((st.quiz_obtained / st.quiz_total) * 100).toFixed(2) + "%"
                    : "N/A";

                const overallTotal = st.total_marks + st.quiz_total;
                const overallObtained = st.total_obtained_marks + st.quiz_obtained;

                const overallPercent = overallTotal > 0 
                    ? ((overallObtained / overallTotal) * 100).toFixed(2) + "%"
                    : "N/A";

                html += `
                    <tr>
                        <td><b>${st.first_name} ${st.last_name}</b></td>
                        <td>${st.total_obtained_marks}</td>
                        <td>${st.total_marks}</td>
                        <td><span style="color:${assignmentPercent >= "50%" ? 'green' : 'red'}">${assignmentPercent}</span></td>
                        <td>${st.quiz_obtained}</td>
                        <td>${st.quiz_total}</td>
                        <td><span style="color:${overallPercent >= "50%" ? 'green' : 'red'}">${overallPercent}</span></td>
                    </tr>
                `;
            });

            html += `</tbody></table>
            <button class="action-button" onclick="updateStudentSummary(${course_id}, ${class_id})">🔄 Update Summary</button>`;
            list.innerHTML = html;
        })
        .catch(err => {
            console.error("❌ Error fetching summary:", err);
            mainContent.innerHTML = `<p style="color:red;">❌ Error loading student summary.</p>`;
        });
}


function updateStudentSummary(course_id, class_id) {
    fetch(`update_student_summary.php?course_id=${course_id}&class_id=${class_id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("✅ Student summary updated successfully!");
                loadStudentSummary(course_id, class_id); // reload table
            } else {
                alert("❌ Error updating summary: " + data.error);
            }
        })
        .catch(err => {
            console.error("❌ Update error:", err);
            alert("❌ Error updating student summary.");
        });
}

// ✅ Enable editing marks
function enableEditMarks(assignments_data_id) {
    const marksInput = document.getElementById(`marks-${assignments_data_id}`);
    marksInput.removeAttribute("readonly");
    marksInput.style.background = "#fff";
    marksInput.style.color = "black";
    document.querySelector(`#row-${assignments_data_id} td:last-child`).innerHTML = `<button onclick="saveAssignmentMarks(${assignments_data_id})">Save</button>`;
}

// ✅ Save Marks to Database
function saveAssignmentMarks(assignments_data_id) {
    const marksInput = document.getElementById(`marks-${assignments_data_id}`);
    const marks = marksInput.value;

    if (marks === "" || marks < 0 || marks > 100) {
        alert("❌ Please enter valid marks between 0 and 100.");
        return;
    }

    fetch("save_assignment_marks.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `assignments_data_id=${assignments_data_id}&marks=${marks}`
    })
    .then(res => res.text())
    .then(response => {
        alert(response);
        if (response.includes("✅")) {
            marksInput.setAttribute("readonly", true);
            marksInput.style.background = "#ddd";
            marksInput.style.color = "gray";
            document.querySelector(`#row-${assignments_data_id} td:last-child`).innerHTML = `✔️ Submitted <button onclick="enableEditMarks(${assignments_data_id})">✏️ Edit</button>`;
        }
    })
    .catch(err => console.error("Error saving marks:", err));
}


function viewStudents(courseName, courseId, classId) {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <button class="action-button" onclick="showCourses()">🔙 Back</button>
        <h2 style="margin-top:15px;">👨‍🎓 Students for ${courseName}</h2>
        <p>Loading students...</p>
    `;

    fetch(`fetch_students.php?course_id=${courseId}&class_id=${classId}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                mainContent.innerHTML = `<p style="color:red;">❌ ${data.message}</p>`;
                return;
            }

            const students = data.students;
            if (!students || students.length === 0) {
                mainContent.innerHTML = `<p>ℹ No students found for this class and course.</p>`;
                return;
            }

            // ✅ Build attractive card grid
            let gridHTML = `
                <button class="action-button" onclick="showCourses()">🔙 Back</button>
                <div class="students-grid">
            `;

            students.forEach(student => {
                const pic = student.profile_picture 
                    ? `../uploads/profile_pictures/${student.profile_picture}` 
                    : `../uploads/profile_pictures/default.png`;

                gridHTML += `
                    <div class="student-card">
                        <img src="${pic}" alt="${student.first_name}" class="student-photo">
                        <div class="student-info">
                            <h3>${student.first_name} ${student.last_name}</h3>
                            <p><strong>ID:</strong> ${student.students_id}</p>
                            <p><strong>Email:</strong> ${student.students_email}</p>
                            <p><strong>Contact:</strong> ${student.contact_num}</p>
                            <p><strong>Gender:</strong> ${student.gender}</p>
                            <p><strong>Country:</strong> ${student.country}</p>
                            <p><strong>Province:</strong> ${student.provience}</p>
                            <p><strong>District:</strong> ${student.district}</p>
                        </div>
                    </div>
                `;
            });

            gridHTML += `</div>`;
            mainContent.innerHTML = gridHTML;
        })
        .catch(err => {
            mainContent.innerHTML = `<p style="color:red;">⚠ Error loading students: ${err}</p>`;
        });
}





async function Option1(classId, option) {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `<h2>Loading students for class ${classId}...</h2>`; // Show loading message

    try {
        // Fetch students by class_id
        const response = await fetch(`fetch_students_by_class.php?class_id=${classId}`);

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const students = await response.json();

        if (students.length === 0) {
            mainContent.innerHTML = `<h2>No students found for class ${classId}.</h2>`;
            return;
        }

        // Display the student list
        mainContent.innerHTML = `
            <h2>Students in Class ${classId}</h2>
            <table border="1" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                      
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Gender</th>
                        <th>Country</th>
                        <th>Province</th>
                        <th>District</th>
                    </tr>
                </thead>
                <tbody>
                    ${students.map(student => `
                        <tr>
                            
                            <td>${student.first_name}</td>
                            <td>${student.last_name}</td>
                            <td>${student.students_email}</td>
                            <td>${student.contact_num}</td>
                            <td>${student.gender}</td>
                            <td>${student.country}</td>
                            <td>${student.provience}</td>
                            <td>${student.district}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>`;
    } catch (error) {
        console.error('Error fetching students:', error);
        mainContent.innerHTML = `<p>An error occurred while loading the students. Please try again later.</p>`;
    }
}






async function showClass() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `<h2>Loading courses...</h2>`;

    try {
        const response = await fetch('fetch_teacher_courses.php');
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const data = await response.json();

        if (data.error) {
            console.error(data.error);
            mainContent.innerHTML = `<p>Error: ${data.error}</p>`;
            return;
        }

        if (data.courses.length === 0) {
            mainContent.innerHTML = `
                <h2>Courses</h2>
                <p>No courses assigned yet.</p>`;
            return;
        }

        mainContent.innerHTML = `
            <h2>Students Managements</h2>
            <p>Manage students :</p>
            <div id="courses-list">
            
                ${data.courses.map(course => createCourseItems(course)).join('')}
            </div>`;

        document.querySelectorAll('.course-item').forEach(courseDiv => {
            courseDiv.addEventListener('click', () => toggleOptions(courseDiv));
        });

    } catch (error) {
        console.error('Error fetching courses:', error);
        mainContent.innerHTML = `<p>An error occurred while loading the courses. Please try again later.</p>`;
    }
}






// Function to manage students
function manageStudents() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `<h2>Manage Students</h2> <p>Student management functionality coming soon!</p>`;
    showClass();
}
