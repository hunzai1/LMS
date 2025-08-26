// ======================
// Navigation Handler
// ======================
document.querySelectorAll('.menu-item, .btn').forEach(item => {
    item.addEventListener('click', event => {
        const content = item.getAttribute('data-content'); // ✅ FIXED
        const mainContent = document.getElementById('main-content');
        
        // Reset colors for all items
        document.querySelectorAll('.menu-item, .btn').forEach(i => {
            i.style.color = ''; // Reset to default color
        });

        // Highlight the active menu
        item.style.color = "blue";

        // Handle content updates
        switch (content) {
            case 'dashboard':
                admin_dashboard();
                break;
            case 'teachers':
                Manage_Teachers();
                break;
            case 'students':
                Manage_Students();
                break;
            case 'courses':
                viewCourses();
                break;
            case 'classes':
                viewclasses();
                break;
            case 'results':
                loadResultsSection();
                break;
            case 'reports':
                mainContent.innerHTML = `
                    <h2>Reports</h2>
                    <p>View system reports and analytics.</p>`;
                break;
            case 'settings':
                  mainContent.innerHTML = `
                    <h2>Reports</h2>
                    <p>View system reports and analytics.</p>`
                loadSettingsSection();
                break;
            default:
                admin_dashboard();
        }
    });
});


// ======================
// DASHBOARD SECTION
// ======================
function admin_dashboard() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <h2 class="text-2xl font-bold mb-4">📊 Admin Dashboard</h2>
        <p class="text-gray-600 mb-6">Select an option below to manage the system.</p>
        
        <div class="dashboard-grid">
            <div class="dashboard-card" onclick="Manage_Teachers()">
                <i class="fas fa-chalkboard-teacher fa-2x text-blue-500"></i>
                <h3>Manage Teachers</h3>
            </div>
            <div class="dashboard-card" onclick="Manage_Students()">
                <i class="fas fa-user-graduate fa-2x text-green-500"></i>
                <h3>Manage Students</h3>
            </div>
            <div class="dashboard-card" onclick="Manage_Courses()">
                <i class="fas fa-book fa-2x text-purple-500"></i>
                <h3>Manage Courses</h3>
            </div>
            <div class="dashboard-card" onclick="Manage_Classes()">
                <i class="fas fa-chalkboard fa-2x text-orange-500"></i>
                <h3>Manage Classes</h3>
            </div>
            <div class="dashboard-card" onclick="Manage_Results()">
                <i class="fas fa-poll fa-2x text-pink-500"></i>
                <h3>Manage Results</h3>
            </div>
            <div class="dashboard-card" onclick="View_Reports()">
                <i class="fas fa-chart-line fa-2x text-teal-500"></i>
                <h3>View Reports</h3>
            </div>
            <div class="dashboard-card" onclick="Admin_Settings()">
                <i class="fas fa-cogs fa-2x text-gray-700"></i>
                <h3>Settings</h3>
            </div>
        </div>
    `;

    // Add styling dynamically if not already included
    if (!document.getElementById("dashboard-styles")) {
        const style = document.createElement("style");
        style.id = "dashboard-styles";
        style.innerHTML = `
            .dashboard-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 20px;
                margin-top: 20px;
            }
            .dashboard-card {
                background: #fff;
                border-radius: 12px;
                padding: 20px;
                text-align: center;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .dashboard-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 16px rgba(0,0,0,0.15);
                background: #f9fafb;
            }
            .dashboard-card i {
                margin-bottom: 10px;
                display: block;
            }
            .dashboard-card h3 {
                font-size: 1rem;
                font-weight: 600;
                color: #333;
            }
        `;
        document.head.appendChild(style);
    }
}

// ======================
// RESULTS SECTION
// ======================
function loadResultsSection() {
    const mainContent = document.getElementById("main-content");
    mainContent.innerHTML = `
        <h2 class="section-heading">📊 Manage Results</h2>
        <div style="margin-top:20px;">
            <button class="btn" onclick="manageGrades()">Manage Grades</button>
            <button class="btn"  onclick="showResults()"> results</button>
        </div>
        <div id="results-content" style="margin-top:20px;"></div>
    `;

    // Fetch current publish_result status
    fetch("get_publish_status.php")
        .then(res => res.json())
        .then(data => {
            const btn = document.getElementById("toggleAllBtn");
            if (data.all_on) {
                btn.textContent = "Hide All Results";
            } else {
                btn.textContent = "Show All Results";
            }
        });
}


// ✅ Manage Grades
function manageGrades() {
    const resultsContent = document.getElementById("results-content");
    resultsContent.innerHTML = `
        <h3>📝 Manage Grades</h3>
        <form id="gradeForm">
            <label>Grade Name:</label>
            <input type="text" name="grade_name" required placeholder="e.g., A"><br><br>
            <label>Min Percentage:</label>
            <input type="number" name="min_percentage" required placeholder="e.g., 80"><br><br>
            <label>Max Percentage:</label>
            <input type="number" name="max_percentage" required placeholder="e.g., 100"><br><br>
            <button type="submit" class="btn">➕ Add Grade</button>
        </form>
        <div id="grade-message" style="margin-top:15px;"></div>
        <div id="grade-list" style="margin-top:20px;"></div>
    `;

    document.getElementById("gradeForm").onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("insert_grade.php", { method: "POST", body: formData })
        .then(res => res.text())
        .then(msg => {
            document.getElementById("grade-message").innerHTML = msg;
            loadGradesList();
            this.reset();
        });
    };

    loadGradesList();
}

// ✅ Load Grades
function loadGradesList() {
    fetch("fetch_grades.php")
        .then(res => res.json())
        .then(data => {
            let listHTML = `
                <h4>📋 Existing Grades</h4>
                <table border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse; width:100%; text-align:center;">
                    <tr style="background:#f4f4f4;">
                        <th>Grade</th>
                        <th>Min %</th>
                        <th>Max %</th>
                        <th>Action</th>
                    </tr>
            `;

            data.forEach(g => {
                listHTML += `
                    <tr>
                        <td><strong>${g.grade_name}</strong></td>
                        <td>${g.min_percentage}</td>
                        <td>${g.max_percentage}</td>
                        <td>
                            <button onclick="editGrade(${g.grade_id}, '${g.grade_name}', ${g.min_percentage}, ${g.max_percentage})">✏️ Edit</button>
                            <button onclick="deleteGrade(${g.grade_id})" style="color:red;">🗑 Delete</button>
                        </td>
                    </tr>
                `;
            });

            listHTML += "</table>";
            document.getElementById("grade-list").innerHTML = listHTML;
        });
}

// ✅ Edit Grade
function editGrade(id, name, min, max) {
    const form = document.getElementById("gradeForm");
    form.grade_name.value = name;
    form.min_percentage.value = min;
    form.max_percentage.value = max;
    document.querySelector("#gradeForm button").innerText = "✏️ Update Grade";

    form.onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append("grade_id", id);

        fetch("update_grade.php", { method: "POST", body: formData })
        .then(res => res.text())
        .then(msg => {
            document.getElementById("grade-message").innerHTML = msg;
            loadGradesList();
            form.reset();
            document.querySelector("#gradeForm button").innerText = "➕ Add Grade";
            manageGrades();
        });
    };
}

// ✅ Delete Grade
function deleteGrade(id) {
    if (!confirm("Are you sure you want to delete this grade?")) return;
    fetch(`delete_grade.php?id=${id}`)
        .then(res => res.text())
        .then(msg => {
            document.getElementById("grade-message").innerHTML = msg;
            loadGradesList();
        });
}

function togglePublishResult(classId, button) {
    const newStatus = button.innerText === "Show Results" ? "on" : "off";

    fetch("update_publish_result.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ class_id: classId, status: newStatus })
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            // ✅ Instead of just changing button text locally, re-fetch from DB
            showResults();  
        } else {
            alert("Failed to update: " + response.error);
        }
    })
    .catch(err => console.error("Error:", err));
}




// ✅ Show Results
function showResults(class_id = null) {
    const resultsContent = document.getElementById("results-content");
    resultsContent.innerHTML = `<p>Loading results...</p>`;

    Promise.all([
        fetch("fetch_all_students_results.php").then(r => r.json()),
        fetch("fetch_classes_with_status.php").then(r => r.json())
    ])
    .then(([results, classStatuses]) => {
        if (!results || results.length === 0) {
            resultsContent.innerHTML = `<p>❌ No results found.</p>`;
            return;
        }

        // Build a quick lookup map: class_id -> "on" | "off"
        const statusMap = {};
        (classStatuses || []).forEach(c => {
            statusMap[String(c.class_id)] = c.status; // e.g., { "27": "on" }
        });

        let html = `
            <div style="margin-bottom:15px;">
                <button class="btn" id="toggleAllBtn" onclick="toggleAllResults()">Loading...</button>
            </div>
        `;

        // Group results by class (id + name)
        const groupedByClass = {};
        results.forEach(r => {
            const id = r.class_id;
            const name = r.class_name;
            const key = `${id}__${name}`;

            if (!groupedByClass[key]) {
                groupedByClass[key] = { id, name, students: [] };
            }
            groupedByClass[key].students.push(r);
        });

        // Render each class
        Object.values(groupedByClass).forEach(group => {
            if (class_id !== null && group.id != class_id) return;

            const status = statusMap[String(group.id)] || "off";
            const isOn = status === "on";

            // 🔹 Class summary row with your exact columns & button
            html += `
                <table border="1" cellpadding="10" cellspacing="0" style="width:100%; text-align:center; margin-top:10px;">
                    <thead>
                        <tr>
                            <th>Class ID</th>
                            <th>Class Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>${group.id}</td>
                            <td>${group.name}</td>
                            <td>${isOn ? "✅ ON" : "❌ OFF"}</td>
                            <td>
                                <button class="btn"
                                    style="margin-bottom:10px; background:${isOn ? "green" : "red"}; color:white;"
                                    onclick="togglePublishResult(${group.id}, this)">
                                    ${isOn ? "Hide Results" : "Show Results"}
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            `;

            // 🔹 Detailed results table for the class
            html += `
                <table border="1" cellspacing="0" cellpadding="8"
                       style="width:100%; border-collapse: collapse; margin-top:10px; text-align:center;">
                    <thead style="background:#f4f4f4;">
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Quiz Obtained</th>
                            <th>Quiz Total</th>
                            <th>Assignment Obtained</th>
                            <th>Assignment Total</th>
                            <th>Exam Obtained</th>
                            <th>Exam Total</th>
                            <th>Overall %</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            group.students.forEach(r => {
                html += `
                    <tr>
                        <td><strong>${r.student_name}</strong></td>
                        <td>${r.course_name}</td>
                        <td>${r.quiz_obtained}</td>
                        <td>${r.quiz_total}</td>
                        <td>${r.assignment_obtained}</td>
                        <td>${r.assignment_total}</td>
                        <td>${r.exam_obtained}</td>
                        <td>${r.exam_total}</td>
                        <td><strong>${r.percentage}%</strong></td>
                        <td><strong>${r.grade}</strong></td>
                    </tr>
                `;
            });

            html += `</tbody></table>`;
        });

        resultsContent.innerHTML = html;

        // ✅ Update the "Toggle All" button label/state after render
        if (typeof checkAllStatus === "function") {
            checkAllStatus();
        }
    })
    .catch(err => {
        console.error("Fetch error:", err);
        resultsContent.innerHTML = `<p style="color:red;">⚠️ Error loading results</p>`;
    });
}



// Global tracker
let allResultsStatus = "off"; 

// ✅ Check if all are ON
function checkAllStatus() {
    fetch("check_all_status.php")
    .then(res => res.json())
    .then(data => {
        const btn = document.getElementById("toggleAllBtn"); // FIXED ID
        if (data.all_on) {
            allResultsStatus = "on";
            btn.textContent = "Hide All Results";
        } else {
            allResultsStatus = "off";
            btn.textContent = "Show All Results";
        }
    })
    .catch(err => console.error(err));
}

// ✅ Toggle ALL
function toggleAllResults() {
    const btn = document.getElementById("toggleAllBtn");
    const newStatus = btn.textContent === "Show All Results" ? "on" : "off";

    fetch("update_publish_all.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ status: newStatus })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Update button text
            btn.textContent = newStatus === "on" ? "Hide All Results" : "Show All Results";

            // ✅ Refresh the tables after status change
            showResults();
        } else {
            alert("Error: " + data.error);
        }
    })
    .catch(err => console.error("Error updating all results:", err));
}



// ======================
// SETTINGS SECTION
// ======================
function loadSettingsSection() {
    const mainContent = document.getElementById("main-content");
    mainContent.innerHTML = `
        <h2>⚙️ Settings</h2>
        <div class="settings-buttons">
            <button class="btn-pass" onclick="showPasswordForm()">🔑 Change Password</button>
            <button class="btn-name" onclick="showNameForm()">📝 Change Name</button>
        </div>
        <div id="settings-form" style="margin-top:20px;"></div>
    `;
}

// ✅ Change Password
function showPasswordForm() {
    document.getElementById("settings-form").innerHTML = `
        <h3>Change Password</h3>
        <form id="changePasswordForm">
            <label>Current Password:</label><br>
            <input type="password" id="currentPassword" required><br><br>
            <label>New Password:</label><br>
            <input type="password" id="newPassword" required><br><br>
            <label>Confirm Password:</label><br>
            <input type="password" id="confirmPassword" required><br><br>
            <button type="submit" class="btn">Update Password</button>
        </form>
        <p id="passwordMessage" style="margin-top:10px;"></p>
    `;

    document.getElementById("changePasswordForm").onsubmit = async function(e) {
        e.preventDefault();
        const currentPassword = document.getElementById("currentPassword").value;
        const newPassword = document.getElementById("newPassword").value;
        const confirmPassword = document.getElementById("confirmPassword").value;
        const message = document.getElementById("passwordMessage");

        if (newPassword !== confirmPassword) {
            message.innerHTML = "❌ Passwords do not match!";
            message.style.color = "red";
            return;
        }

        const res = await fetch("change_password.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ currentPassword, newPassword })
        });

        const result = await res.json();
        message.innerHTML = result.success ? "✅ Password updated!" : "❌ " + result.error;
        message.style.color = result.success ? "green" : "red";
    };
}

// ✅ Change Name
function showNameForm() {
    document.getElementById("settings-form").innerHTML = `
        <h3>Change Username</h3>
        <form id="changeNameForm">
            <label>New Username:</label><br>
            <input type="text" id="newUsername" required><br><br>
            <button type="submit" class="btn">Update Name</button>
        </form>
        <p id="nameMessage" style="margin-top:10px;"></p>
    `;

    document.getElementById("changeNameForm").onsubmit = async function(e) {
        e.preventDefault();
        const newUsername = document.getElementById("newUsername").value;
        const message = document.getElementById("nameMessage");

        const res = await fetch("change_admin_name.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ newUsername })
        });

        const result = await res.json();
        message.innerHTML = result.success ? "✅ Name updated!" : "❌ " + result.error;
        message.style.color = result.success ? "green" : "red";
    };
}




// teachers management //
/*******************************
 * Utility helpers
 *******************************/
function normalizeProfilePath(raw) {
    let p = (raw || '').trim();

    if (!p) return 'default_avatar.png'; // fallback image

    // Allow full URLs
    if (/^https?:\/\//i.test(p)) return p;

    // Remove leading "./" or "../"
    while (p.startsWith('./')) p = p.slice(2);
    while (p.startsWith('../')) p = p.slice(3);

    // If it already points to uploads/teachers, keep it
    if (p.includes('uploads/teachers/')) return p;

    // Otherwise assume it's a filename only
    return `uploads/teachers/${p}`;
}

function safeJSON(res) {
    // helpful for catching PHP notices/HTML before JSON
    return res.text().then(t => {
        try { return JSON.parse(t); }
        catch (e) {
            console.error('Invalid JSON from server:', t);
            throw new Error('Server response was not valid JSON.');
        }
    });
}

function backButton() {
    return `<button class="action-button" onclick="Manage_Teachers()">Back</button>`;
}

/*******************************
 * Menu / Landing
 *******************************/
/*******************************
 * Manage Teachers (Updated UI)
 *******************************/
function Manage_Teachers() {
    const mainContent = document.getElementById("main-content");
    mainContent.innerHTML = `
        <h2 class="section-heading">👨‍🏫 Manage Teachers</h2>
        <div style="margin-top:20px;">
            <button class="btn" onclick="Teacher_Profile()">📋 Teacher Profiles</button>
            <button class="btn" onclick="Add_Teacher()">➕ Add Teacher</button>
            <button class="btn" onclick="Remove_Teacher()">🗑 Remove Teacher</button>
            <button class="btn" onclick="Teacher_Setting()">🔑 Reset Teacher Password</button>
            <button class="btn" onclick="manageEnrollments()">📚 Manage Enrollment</button>
        </div>
        <div id="teacher-content" style="margin-top:20px;"></div>
    `;
}

/*******************************
 * Reset Teacher Password
 *******************************/
function Teacher_Setting() {
    const teacherContent = document.getElementById('teacher-content');

    teacherContent.innerHTML = `
        <h3>🔑 Reset Teacher Password</h3>
        <form id="reset-password-form">
            <label for="teacher_id">Select Teacher:</label>
            <select id="teacher_id" name="teacher_id" required>
                <option value="" disabled selected>Select a teacher</option>
            </select>
            <button type="submit" class="btn">Reset Password</button>
        </form>
        <div id="reset-password-message" style="margin-top:10px;"></div>
    `;

    // Populate teacher list
    fetch('fetch_teachers.php')
        .then(safeJSON)
        .then(teachers => {
            const teacherSelect = document.getElementById('teacher_id');
            teachers.forEach(t => {
                const opt = document.createElement('option');
                opt.value = t.teacher_id;
                opt.textContent = `${t.first_name} ${t.last_name} (${t.teachers_email})`;
                teacherSelect.appendChild(opt);
            });
        });

    // Handle reset
    document.getElementById('reset-password-form').addEventListener('submit', e => {
        e.preventDefault();
        const teacherId = document.getElementById('teacher_id').value;

        fetch('reset_teacher_password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ teacher_id: teacherId })
        })
        .then(safeJSON)
        .then(result => {
            const msg = document.getElementById('reset-password-message');
            if (result.success) {
                msg.innerHTML = `<p class="success-message">✅ Password reset. Default: <b>teacher123</b></p>`;
            } else {
                msg.innerHTML = `<p class="error-message">❌ ${result.message || 'Reset failed.'}</p>`;
            }
        });
    });
}

/*******************************
 * Teacher Profile (with images)
 *******************************/
function Teacher_Profile() {
    const teacherContent = document.getElementById("teacher-content");

    teacherContent.innerHTML = `
        <h3 style="font-size:22px; margin-bottom:15px; color:#2c3e50;">📋 Teacher Profiles</h3>
        <div id="teacher-list" style="overflow-x:auto;">Loading teachers...</div>
    `;

    fetch("fetch_teachers.php")
        .then(res => res.json())
        .then(data => {
            const teacherList = document.getElementById("teacher-list");

            if (!data || data.length === 0) {
                teacherList.innerHTML = "<p>No teachers found.</p>";
                return;
            }

            let table = `
                <table style="
                    width:100%;
                    border-collapse: collapse;
                    font-family: Arial, sans-serif;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                    border-radius: 6px;
                    overflow: hidden;
                ">
                    <thead>
                        <tr style="background:linear-gradient(90deg, #4facfe, #00f2fe); color:white; text-align:left;">
                            <th style="padding:12px;">ID</th>
                            <th style="padding:12px;">Profile</th>
                            <th style="padding:12px;">Full Name</th>
                            <th style="padding:12px;">Email</th>
                            <th style="padding:12px;">Subject Expertise</th>
                            <th style="padding:12px;">Contact</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.forEach((teacher, i) => {
                const filename = teacher.profile_picture ? teacher.profile_picture.trim() : '';
                const profilePic = filename
                    ? `../uploads/teachers/${teacher.profile_picture}`
                    : `../uploads/teachers/default.png`;

                table += `
                    <tr style="background:${i % 2 === 0 ? "#fdfdfd" : "#f7faff"}; transition: background 0.3s;">
                        <td style="padding:12px; border-bottom:1px solid #eee;">${teacher.teacher_id}</td>
                        <td style="padding:12px; border-bottom:1px solid #eee;">
                            <div style="
                                width:70px; 
                                height:70px; 
                                border-radius:6px; 
                                overflow:hidden; 
                                box-shadow: 0 2px 6px rgba(0,0,0,0.1);
                            ">
                                <img src="${profilePic}" 
                                     alt="Profile Picture" 
                                     onerror="this.src='../uploads/teachers/default.png';"
                                     style="width:100%; height:100%; object-fit:cover;">
                            </div>
                        </td>
                        <td style="padding:12px; border-bottom:1px solid #eee; font-weight:600; color:#2c3e50;">
                            ${teacher.first_name} ${teacher.last_name}
                        </td>
                        <td style="padding:12px; border-bottom:1px solid #eee; color:#34495e;">
                            ${teacher.teachers_email}
                        </td>
                        <td style="padding:12px; border-bottom:1px solid #eee; color:#16a085;">
                            ${teacher.subject_expertise}
                        </td>
                        <td style="padding:12px; border-bottom:1px solid #eee; color:#8e44ad;">
                            ${teacher.contact_number}
                        </td>
                    </tr>
                `;
            });

            table += `</tbody></table>`;
            teacherList.innerHTML = table;

            // Hover styling
            document.querySelectorAll("tbody tr").forEach((row, i) => {
                row.addEventListener("mouseover", () => row.style.background = "#eaf6ff");
                row.addEventListener("mouseout", () => {
                    row.style.background = i % 2 === 0 ? "#fdfdfd" : "#f7faff";
                });
            });
        })
        .catch(err => {
            document.getElementById("teacher-list").innerHTML = `<p style="color:red;">❌ Error loading teachers: ${err}</p>`;
        });
}



/*******************************
 * Add Teacher
 *******************************/
function Add_Teacher() {
    const teacherContent = document.getElementById('teacher-content');

    teacherContent.innerHTML = `
        <h3>➕ Add New Teacher</h3>
        <form id="add-teacher-form" enctype="multipart/form-data">
            <label>First Name:</label>
            <input type="text" name="first_name" required>
            <label>Last Name:</label>
            <input type="text" name="last_name" required>
            <label>Email:</label>
            <input type="email" name="teachers_email" required>
            <label>Password:</label>
            <input type="password" name="teachers_password" required>
            <label>Contact Number:</label>
            <input type="text" name="contact_number" required>
            <label>Gender:</label>
            <select name="gender" required>
                <option value="">--Select--</option>
                <option>Male</option>
                <option>Female</option>
                <option>Other</option>
            </select>
            <label>Country:</label>
            <input type="text" name="country" required>
            <label>Province:</label>
            <input type="text" name="province" required>
            <label>District:</label>
            <input type="text" name="district" required>
            <label>Subject Expertise:</label>
            <input type="text" name="subject_expertise" required>
            <label>Qualification:</label>
            <input type="text" name="qualification" required>
            <label>Profile Picture:</label>
            <input type="file" name="profile_picture" accept="image/*" required>
            <button type="submit" class="btn">➕ Add Teacher</button>
        </form>
        <div id="add-teacher-message" style="margin-top:10px;"></div>
    `;

    document.getElementById('add-teacher-form').addEventListener('submit', handleAddTeacher);
}

function handleAddTeacher(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    fetch('register_teacher.php', { method: 'POST', body: formData })
    .then(safeJSON)
    .then(result => {
        const msg = document.getElementById('add-teacher-message');
        if (result.success) {
            msg.innerHTML = `<p class="success-message">✅ Teacher added successfully!</p>`;
            form.reset();
        } else {
            msg.innerHTML = `<p class="error-message">❌ ${result.message || 'Failed to add teacher.'}</p>`;
        }
    });
}

/*******************************
 * Remove Teacher
 *******************************/
function Remove_Teacher() {
    const teacherContent = document.getElementById('teacher-content');

    teacherContent.innerHTML = `
        <h3>🗑 Remove Teacher</h3>
        <form id="remove-teacher-form">
            <label for="teacher_id">Select Teacher:</label>
            <select id="teacher_id" name="teacher_id" required>
                <option value="" disabled selected>Select a teacher</option>
            </select>
            <button type="submit" class="btn">🗑 Remove</button>
        </form>
        <div id="remove-teacher-message" style="margin-top:10px;"></div>
    `;

    fetch('fetch_teachers.php')
        .then(safeJSON)
        .then(teachers => {
            const sel = document.getElementById('teacher_id');
            teachers.forEach(t => {
                const o = document.createElement('option');
                o.value = t.teacher_id;
                o.textContent = `${t.first_name} ${t.last_name}`;
                sel.appendChild(o);
            });
        });

    document.getElementById('remove-teacher-form').addEventListener('submit', handleRemoveTeacher);
}

function handleRemoveTeacher(e) {
    e.preventDefault();
    const formData = new FormData(e.target);

    fetch('delete_teacher.php', { method: 'POST', body: formData })
    .then(safeJSON)
    .then(result => {
        const msg = document.getElementById('remove-teacher-message');
        if (result.success) {
            msg.innerHTML = `<p class="success-message">✅ Teacher removed!</p>`;
            e.target.reset();
        } else {
            msg.innerHTML = `<p class="error-message">❌ ${result.message || 'Failed to remove teacher.'}</p>`;
        }
    });
}

/*******************************
 * Manage Enrollments
 *******************************/
function manageEnrollments() {
    Promise.all([
        fetch('fetch_teachers.php').then(safeJSON),
        fetch('fetch_classes.php').then(safeJSON),
        fetch('fetch_courses.php').then(safeJSON)
    ])
    .then(([teachers, classes, courses]) => {
        const teacherContent = document.getElementById('teacher-content');

        teacherContent.innerHTML = `
            <h3>📚 Assign Teacher to Class & Course</h3>
            <form id="assign-teacher-form">
                <label>Teacher:</label>
                <select id="teacher-select" name="teacher_id" required>
                    <option value="">--Select--</option>
                    ${teachers.map(t => `<option value="${t.teacher_id}">${t.first_name} ${t.last_name}</option>`).join('')}
                </select>
                <label>Class:</label>
                <select id="class-select" name="class_id" required>
                    <option value="">--Select--</option>
                    ${classes.map(c => `<option value="${c.class_id}">${c.class_name}</option>`).join('')}
                </select>
                <label>Course:</label>
                <select id="course-select" name="course_id" required>
                    <option value="">--Select--</option>
                    ${courses.map(c => `<option value="${c.course_id}">${c.course_name}</option>`).join('')}
                </select>
                <button type="submit" class="btn">Assign</button>
            </form>
            <div id="assign-message" style="margin-top:10px;"></div>
            <div id="teacher-assignments-container" style="margin-top:20px;"></div>
        `;

        document.getElementById('assign-teacher-form').addEventListener('submit', handleAssignTeacher);
        showTeacherAssignments();
    });
}

function handleAssignTeacher(e) {
    e.preventDefault();

    const teacherId = document.getElementById('teacher-select').value;
    const classId = document.getElementById('class-select').value;
    const courseId = document.getElementById('course-select').value;

    fetch('assign_course_teacher.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ teacher_id: teacherId, class_id: classId, course_id: courseId })
    })
    .then(safeJSON)
    .then(result => {
        const msg = document.getElementById('assign-message');
        msg.innerHTML = result.success
            ? `<p class="success-message">✅ Assigned successfully!</p>`
            : `<p class="error-message">❌ ${result.message || 'Failed to assign.'}</p>`;
        showTeacherAssignments();
    });
}

function showTeacherAssignments() {
    fetch('fetch_teacher_assignments.php')
    .then(safeJSON)
    .then(data => {
        const container = document.getElementById('teacher-assignments-container');
        if (Array.isArray(data) && data.length > 0) {
            container.innerHTML = `
                <h4>📋 Teacher Assignments</h4>
                <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse; text-align:center;">
                    <tr style="background:#f4f4f4;">
                        <th>Teacher</th><th>Course</th><th>Class</th><th>Action</th>
                    </tr>
                    ${data.map(item => `
                        <tr>
                            <td>${item.teacher_name}</td>
                            <td>${item.course_name}</td>
                            <td>${item.class_name}</td>
                            <td><button onclick="deleteTeacherAssignment(${item.teacher_assign_id})" style="color:red;">🗑 Delete</button></td>
                        </tr>`).join('')}
                </table>
            `;
        } else {
            container.innerHTML = `<p>No assignments yet.</p>`;
        }
    });
}

function deleteTeacherAssignment(id) {
    if (!confirm("Delete this assignment?")) return;

    fetch('delete_teacher_assignment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ teacher_assign_id: id })
    })
    .then(safeJSON)
    .then(data => {
        if (data.success) {
            showTeacherAssignments();
        } else {
            alert("❌ Failed to delete");
        }
    });
}


/*******************************
 * Safe placeholders to prevent errors
 *******************************/
function uploadCourseResources() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        ${backButton()}
        <h2>Upload Course Resources</h2>
        <p>(Placeholder) Implement your upload UI here.</p>
    `;
}

function viewCourse() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        ${backButton()}
        <h2>View Courses</h2>
        <p>(Placeholder) Implement your course viewer here.</p>
    `;
}

// student management //


// ---------------- Manage Students ----------------
function Manage_Students() {
    const mainContent = document.getElementById("main-content");
    mainContent.innerHTML = `
        <h2 class="section-heading">👨‍🎓 Manage Students</h2>
        <div style="margin-top:20px;" class="btn-grid">
            <button class="btn" onclick="view_students()">📋 View Students</button>
            <button class="btn" onclick="Register_student()">➕ Register Student</button>
            <button class="btn" onclick="Remove_student()">🗑 Remove Student</button>
            <button class="btn" onclick="students_Setting()">🔑 Reset Student Password</button>
        </div>
        <div id="student-content" style="margin-top:20px;"></div>
    `;
}

// ---------------- View Students (Class Wise) ----------------
function view_students() {
    fetch("view_students.php")
        .then(res => res.json())
        .then(data => {
            let html = `
                <h3>All Students (Class Wise)</h3>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Profile</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Gender</th>
                            <th>Class</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            data.forEach(stu => {
                html += `
                    <tr>
                        <td>${stu.students_id}</td>
                        <td><img src="../uploads/profile_pictures/${stu.profile_picture}" width="100" height="100" style="border-radius:50%;"></td>
                        <td>${stu.first_name} ${stu.last_name}</td>
                        <td>${stu.students_email}</td>
                        <td>${stu.contact_num}</td>
                        <td>${stu.gender}</td>
                        <td>${stu.class_id}</td>
                        <td>${stu.created_at}</td>
                    </tr>
                `;
            });
            html += `</tbody></table>`;
            document.getElementById("student-content").innerHTML = html;
        })
        .catch(err => {
            document.getElementById("student-content").innerHTML = `<p style="color:red;">❌ Error loading students</p>`;
        });
}

// ---------------- Register Student ----------------
function Register_student() {
    document.getElementById("student-content").innerHTML = `
        <style>
            .student-form-container {
                max-width: 500px;
                margin: 20px auto;
                padding: 25px;
                background: #ffffff;
                border-radius: 15px;
                box-shadow: 0px 8px 18px rgba(0, 0, 0, 0.1);
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .student-form-container h3 {
                text-align: center;
                margin-bottom: 20px;
                color: #333;
            }
            .student-form-container input,
            .student-form-container select {
                width: 100%;
                padding: 12px;
                margin: 10px 0;
                border: 1px solid #ddd;
                border-radius: 8px;
                font-size: 14px;
                transition: 0.3s;
            }
            .student-form-container input:focus,
            .student-form-container select:focus {
                border-color: #007bff;
                outline: none;
                box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
            }
            .student-form-container button {
                width: 100%;
                padding: 12px;
                background: linear-gradient(135deg, #007bff, #00bfff);
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 16px;
                cursor: pointer;
                transition: 0.3s;
            }
            .student-form-container button:hover {
                background: linear-gradient(135deg, #0056b3, #009acd);
            }
        </style>

        <div class="student-form-container">
            <h3>Register New Student</h3>
            <form id="studentForm">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="email" name="students_email" placeholder="Email" required>
                <input type="text" name="contact_num" placeholder="Contact Number" required>
                
                <select name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>

                <input type="text" name="country" placeholder="Country" required>
                <input type="text" name="provience" placeholder="Province" required>
                <input type="text" name="district" placeholder="District" required>
                
                <select name="class_id" id="classDropdown" required>
                    <option value="">Loading Classes...</option>
                </select>

                <input type="password" name="students_password" placeholder="Password" required>
                <input type="file" name="profile_picture" required>

                <button type="submit" class="btn">Register Student</button>
            </form>
        </div>
    `;

    // Fetch classes dynamically
    fetch("fetch_classes.php")
    .then(res => res.json())
    .then(data => {
        let dropdown = document.getElementById("classDropdown");
        dropdown.innerHTML = `<option value="">Select Class</option>`;
        data.forEach(cls => {
            dropdown.innerHTML += `<option value="${cls.class_id}">${cls.class_name}</option>`;
        });
    })
    .catch(() => {
        document.getElementById("classDropdown").innerHTML = `<option value="">Error loading classes</option>`;
    });

    // Handle form submit
    document.getElementById("studentForm").onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("register_student.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(msg => {
            alert(msg);
            view_students();
        });
    }
}

// ---------------- Remove Student ----------------
function Remove_student() {
    const studentContent = document.getElementById("student-content");
    studentContent.innerHTML = "<p>⏳ Loading students...</p>";

    // Fetch student list from backend
    fetch("fetch_students.php")
        .then(res => {
            if (!res.ok) throw new Error("Failed to fetch students");
            return res.json();
        })
        .then(students => {
            if (!students || students.length === 0) {
                studentContent.innerHTML = "<p>⚠️ No students found.</p>";
                return;
            }

            // Generate student options
            let options = students.map(s =>
                `<option value="${s.students_id}">${s.full_name}</option>`
            ).join("");

            // Insert form
            studentContent.innerHTML = `
                <h3>Remove Student</h3>
                <form id="removeForm">
                    <label>Select Student to Remove:</label><br>
                    <select name="students_id" required class="btn" 
                        style="padding:8px; margin:10px 0; width:250px;">
                        <option value="">-- Select Student --</option>
                        ${options}
                    </select>
                    <br>
                    <button type="submit" class="btn" 
                        style="background:#d9534f; color:white;">Remove</button>
                </form>
                <div id="removeMessage" style="margin-top:10px; font-weight:bold;"></div>
            `;

            // Handle form submit
            document.getElementById("removeForm").onsubmit = function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const studentId = formData.get("students_id");

                if (!studentId) {
                    document.getElementById("removeMessage").innerHTML = "⚠️ Please select a student.";
                    return;
                }

                // Confirm deletion
                if (!confirm("Are you sure you want to remove this student?")) {
                    return;
                }

                fetch("remove_student.php", {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.text())
                    .then(msg => {
                        document.getElementById("removeMessage").innerHTML = "✅ " + msg;
                        view_students(); // refresh list after removal
                    })
                    .catch(err => {
                        document.getElementById("removeMessage").innerHTML = "❌ Error: " + err.message;
                    });
            };
        })
        .catch(err => {
            console.error("Error fetching students:", err);
            studentContent.innerHTML = "<p>❌ Unable to load students. Please try again later.</p>";
        });
}

// Reset Student Password ----------------// Reset Student Password ----------------
function students_Setting() {
    // Fetch students from backend
    fetch("fetch_students.php")
    .then(res => res.json())
    .then(students => {
        let options = `<option value="">Select Student</option>`;
        students.forEach(s => {
            options += `<option value="${s.students_id}">${s.full_name}</option>`;
        });

        // Create form with dropdown
        document.getElementById("student-content").innerHTML = `
            <h3>Reset Student Password</h3>
            <form id="resetForm" class="styled-form">
                <label>Select Student:</label>
                <select name="students_id" required>
                    ${options}
                </select><br><br>
                
                <label>New Password:</label>
                <input type="password" name="new_password" placeholder="Enter New Password" required><br>
                
                <button type="submit" class="btn">Reset Password</button>
            </form>
        `;

        // Handle form submit
        document.getElementById("resetForm").onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch("reset_student_password.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(msg => {
                alert(msg);
                view_students();
            });
        }
    })
    .catch(err => {
        console.error("Error fetching students:", err);
        alert("❌ Failed to load students list.");
    });
}


// manage courses //

//manage courses

function viewCourse() {
    // Fetch courses from the server via AJAX
    
    fetch('fetch_courses.php')
        .then(response => response.json()) // Parse the JSON response
        .then(data => {
            const mainContent = document.getElementById('main-content'); // Target the main content area

            if (data.length > 0) {
                mainContent.innerHTML = `
                <button class="action-button" onclick="viewCourses()">back</button>
                    <h2>Available Courses</h2>
                    <p>Below is a list of all available courses:</p>
                    <table>
                        <thead>
                            <tr>
                                <th>Course Name</th>
                            </tr>
                        </thead>
                        <tbody>
                      
                            ${data
                                .map(
                                    course => `
                                        <tr>
                                            <td>${course.course_name}</td>
                                        </tr>
                                    `
                                )
                                .join('')}
                        </tbody>
                    </table>
                `;
            } else {
                mainContent.innerHTML = `
                    <h2>Available Courses</h2>
                    <p>No courses found. Please check back later.</p>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching courses:', error);
            const mainContent = document.getElementById('main-content');
            mainContent.innerHTML = `
                <h2>Error</h2>
                <p>Unable to fetch courses. Please try again later.</p>
            `;
        });
}





// ======================
// Manage Courses (Main)
// ======================
function viewCourses() {
    const mainContent = document.getElementById("main-content");
    mainContent.innerHTML = `
        <h2>📚 Manage Courses</h2>
        <p>Select a task to perform:</p>
    
            <button class="btn" onclick="Course_Profile()">
                <i class="fas fa-list"></i>
                <span>View Courses</span>
            </button>
            <button class="btn" onclick="Add_Course()">
                <i class="fas fa-plus"></i>
                <span>Add Course</span>
            </button>
            <button class="btn" onclick="Remove_Course()">
                <i class="fas fa-trash"></i>
                <span>Remove Course</span>
            </button>
            <button class="btn" onclick="Course_Setting()">
                <i class="fas fa-cog"></i>
                <span>Course Settings</span>
            </button>
            <button class="btn" onclick="Assign_Course()">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Assign Course</span>
            </button>
       

        <!-- Sub-section container -->
        <div id="course-content" class="sub-content"></div>
    `;
}

// ======================
// Course Profile (Table)
// ======================
function Course_Profile() {
    const courseContent = document.getElementById("course-content");
    courseContent.innerHTML = `
        ${backButton("Manage_Courses")}
        <h3>📋 Course List</h3>
        <div id="course-list">Loading courses...</div>
    `;

    fetch("fetch_courses.php")
        .then(response => response.json())
        .then(data => {
            const courseList = document.getElementById("course-list");

            if (data.length === 0) {
                courseList.innerHTML = "<p>No courses found.</p>";
                return;
            }

            let table = `
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Course Name</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.forEach(course => {
                table += `
                    <tr>
                        <td>${course.course_id}</td>
                        <td>${course.course_name}</td>
                        <td>${course.course_des}</td>
                    </tr>
                `;
            });

            table += `</tbody></table>`;
            courseList.innerHTML = table;
        })
        .catch(error => {
            document.getElementById("course-list").innerHTML =
                `<p style="color:red;">❌ Error loading courses: ${error}</p>`;
        });
}

// ======================
// Add Course
// ======================
function Add_Course() {
    const courseContent = document.getElementById("course-content");
    courseContent.innerHTML = `
        ${backButton("Manage_Courses")}
        <h3>➕ Add New Course</h3>
        <form id="add-course-form">
            <label>Course Name:</label>
            <input type="text" name="course_name" required><br><br>

            <label>Description:</label>
            <textarea name="course_des" required></textarea><br><br>

            <button type="submit" class="btn-primary">Add Course</button>
        </form>
        <div id="add-course-msg"></div>
    `;

    document.getElementById("add-course-form").onsubmit = e => {
        e.preventDefault();
        fetch("add_course.php", {
            method: "POST",
            body: new FormData(e.target)
        })
        .then(res => res.text())
        .then(msg => {
            document.getElementById("add-course-msg").innerHTML = msg;
            e.target.reset();
        });
    };
}

// ======================
// Remove Course
// ======================
function Remove_Course() {
    const courseContent = document.getElementById("course-content");
    courseContent.innerHTML = `
        ${backButton("Manage_Courses")}
        <h3>🗑 Remove Course</h3>
        <div id="remove-course-list">Loading...</div>
    `;

    fetch("fetch_courses.php")
        .then(res => res.json())
        .then(data => {
            let html = "<ul>";
            data.forEach(c => {
                html += `
                    <li>
                        ${c.course_name} 
                        <button onclick="deleteCourse(${c.course_id})">Delete</button>
                    </li>
                `;
            });
            html += "</ul>";
            document.getElementById("remove-course-list").innerHTML = html;
        });
}

function deleteCourse(id) {
    if (!confirm("Are you sure you want to delete this course?")) return;
    fetch(`delete_course.php?id=${id}`)
        .then(res => res.text())
        .then(msg => {
            alert(msg);
            Remove_Course();
        });
}

// ======================
// Course Setting
// ======================
function Course_Setting() {
    const courseContent = document.getElementById("course-content");
    courseContent.innerHTML = `
        ${backButton("Manage_Courses")}
        <h3>⚙️ Course Settings</h3>
        <p>Here you can update course settings (coming soon...)</p>
    `;
}

// ======================
// Assign Course
// ======================
function Assign_Course() {
    const courseContent = document.getElementById("course-content");
    courseContent.innerHTML = `
        ${backButton("Manage_Courses")}
        <h3>👨‍🏫 Assign Course to Teacher</h3>
        <form id="assign-course-form">
            <label>Select Course:</label>
            <select name="course_id" id="assign-course-select"></select><br><br>

            <label>Select Teacher:</label>
            <select name="teacher_id" id="assign-teacher-select"></select><br><br>

            <button type="submit" class="btn-primary">Assign</button>
        </form>
        <div id="assign-msg"></div>
    `;

    // Fetch dropdown data
    fetch("fetch_courses.php").then(res => res.json()).then(courses => {
        const select = document.getElementById("assign-course-select");
        select.innerHTML = courses.map(c => `<option value="${c.course_id}">${c.course_name}</option>`).join("");
    });

    fetch("fetch_teachers.php").then(res => res.json()).then(teachers => {
        const select = document.getElementById("assign-teacher-select");
        select.innerHTML = teachers.map(t => `<option value="${t.teacher_id}">${t.first_name} ${t.last_name}</option>`).join("");
    });

    document.getElementById("assign-course-form").onsubmit = e => {
        e.preventDefault();
        fetch("assign_course.php", {
            method: "POST",
            body: new FormData(e.target)
        })
        .then(res => res.text())
        .then(msg => {
            document.getElementById("assign-msg").innerHTML = msg;
        });
    };
}


// manage classes //


// for manage classes //

// ---------------- Main Classes Management ----------------
function  viewclasses() {
    const mainContent = document.getElementById("main-content");
    mainContent.innerHTML = `
        <h2 class="section-heading">🏫 Manage Classes</h2>
        <p>Select a task to perform:</p>

        
            <button class="btn" onclick="Add_Class()">
                <i class="fas fa-plus-circle"></i>
                <span>Add Class</span>
            </button>
            <button class="btn" onclick="Class_List()">
                <i class="fas fa-list-ul"></i>
                <span>Classes List</span>
            </button>
            <button class="btn" onclick="Edit_Class()">
                <i class="fas fa-edit"></i>
                <span>Edit Classes</span>
            </button>
            <button class="btn" onclick="Delete_Class()">
                <i class="fas fa-trash-alt"></i>
                <span>Delete Class</span>
            </button>
 

        <div id="class-content" style="margin-top:20px;"></div>
    `;
}

// ---------------- Sub Functions ----------------

// Add a new class
function Add_Class() {
    const content = document.getElementById("class-content");
    content.innerHTML = `
        <h3>➕ Add a New Class</h3>
        <form id="add-class-form" class="form-style">
            <label for="class-name">Class Name:</label>
            <input type="text" id="class-name" name="class_name" required placeholder="Enter class name">
            <button type="submit" class="btn">Add Class</button>
        </form>
        <div id="add-class-message"></div>
    `;

    document.getElementById("add-class-form").addEventListener("submit", handleAddClass);
}

function handleAddClass(event) {
    event.preventDefault();
    const className = document.getElementById("class-name").value.trim();

    fetch("add_class.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ class_name: className }),
    })
        .then(res => res.json())
        .then(result => {
            const msg = document.getElementById("add-class-message");
            if (result.success) {
                msg.innerHTML = `<p class="success-message">✅ Class added successfully!</p>`;
                document.getElementById("add-class-form").reset();
            } else {
                msg.innerHTML = `<p class="error-message">❌ ${result.message}</p>`;
            }
        })
        .catch(err => console.error("Error adding class:", err));
}

// View classes list
function Class_List() {
    fetch("fetch_classes.php")
        .then(res => res.json())
        .then(data => {
            const content = document.getElementById("class-content");
            if (data.length > 0) {
                content.innerHTML = `
                    <h3>📋 Available Classes</h3>
                    <table class="styled-table">
                        <thead>
                            <tr><th>Class Name</th></tr>
                        </thead>
                        <tbody>
                            ${data.map(c => `<tr><td>${c.class_name}</td></tr>`).join("")}
                        </tbody>
                    </table>
                `;
            } else {
                content.innerHTML = `<p>No classes found. Add one first.</p>`;
            }
        })
        .catch(err => console.error("Error fetching classes:", err));
}

// Edit class
function Edit_Class() {
    fetch("fetch_classes.php")
        .then(res => res.json())
        .then(data => {
            const content = document.getElementById("class-content");
            if (data.length > 0) {
                content.innerHTML = `
                    <h3>✏️ Edit Class</h3>
                    <form id="edit-class-form" class="form-style">
                        <label>Select Class:</label>
                        <select id="class-select">
                            ${data.map(c => `<option value="${c.class_id}">${c.class_name}</option>`).join("")}
                        </select>
                        <label>New Class Name:</label>
                        <input type="text" id="new-class-name" required>
                        <button type="submit" class="btn">Update</button>
                    </form>
                `;

                const select = document.getElementById("class-select");
                const input = document.getElementById("new-class-name");
                select.addEventListener("change", () => {
                    input.value = select.options[select.selectedIndex].text;
                });

                document.getElementById("edit-class-form").addEventListener("submit", e => {
                    e.preventDefault();
                    fetch("edit_class.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({
                            class_id: select.value,
                            new_class_name: input.value,
                        }),
                    })
                        .then(res => res.json())
                        .then(r => {
                            alert(r.success ? "✅ Updated!" : "❌ " + r.message);
                            if (r.success) Class_List();
                        });
                });
            } else {
                content.innerHTML = `<p>No classes found to edit.</p>`;
            }
        });
}

// Delete class
function Delete_Class() {
    fetch("fetch_classes.php")
        .then(res => res.json())
        .then(data => {
            const content = document.getElementById("class-content");
            if (data.length > 0) {
                content.innerHTML = `
                    <h3>🗑️ Delete Class</h3>
                    <form id="delete-class-form" class="form-style">
                        <label>Select Class:</label>
                        <select id="delete-class-select">
                            ${data.map(c => `<option value="${c.class_id}">${c.class_name}</option>`).join("")}
                        </select>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    <div id="delete-message"></div>
                `;

                document.getElementById("delete-class-form").addEventListener("submit", e => {
                    e.preventDefault();
                    const select = document.getElementById("delete-class-select");
                    fetch("delete_class.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ class_id: select.value }),
                    })
                        .then(res => res.json())
                        .then(r => {
                            const msg = document.getElementById("delete-message");
                            msg.innerHTML = r.success ? 
                                `<p class="success-message">✅ Deleted!</p>` : 
                                `<p class="error-message">❌ ${r.message}</p>`;
                            if (r.success) Delete_Class();
                        });
                });
            } else {
                content.innerHTML = `<p>No classes available to delete.</p>`;
            }
        });
}


