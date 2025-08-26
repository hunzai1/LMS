// ✅ Attach event listeners to menu and icon items

document.querySelectorAll('.menu-item, .icon-card').forEach(item => {
    item.addEventListener('click', event => {
        const content = item.getAttribute('data-content');
        const mainContent = document.getElementById('main-content');

        document.querySelectorAll('.menu-item, .icon-card').forEach(i => i.style.color = '');
        item.style.color = 'blue';

        switch (content) {
            case 'dashboard': student_dashboard(); break;
            case 'courses': viewCourses(); break;
            case 'profile': loadProfile(); break;
            case 'notice_board': loadNoticeBoard(); break;
            case 'complaints': loadComplaints(); break;
            case 'setting' :loadSetting(); break;
            case 'about': loadAbout(); break;
            default:
                mainContent.innerHTML = `<h2>Welcome!</h2><p>Select an option from the menu to get started.</p>`;
        }
    });
});

function loadSetting() {
    fetch("get_student_profile.php")
        .then(res => res.json())
        .then(student => {
            const mainContent = document.getElementById("main-content");

            mainContent.innerHTML = `
                <h2 style="font-size:22px; font-weight:bold; margin-bottom:20px; color:#333;">
                    ⚙️ Profile Settings
                </h2>
                <div class="settings-container">
                    ${renderSettingRow("First Name", "first_name", student.first_name)}
                    ${renderSettingRow("Last Name", "last_name", student.last_name)}
                    ${renderSettingRow("Email", "students_email", student.students_email)}
                    ${renderSettingRow("Contact Number", "contact_num", student.contact_num)}
                    ${renderSettingRow("Gender", "gender", student.gender)}
                    ${renderSettingRow("Country", "country", student.country)}
                    ${renderSettingRow("Province", "provience", student.provience)}
                    ${renderSettingRow("District", "district", student.district)}
                    ${renderSettingRow("Password", "students_password", "********")}
                    
                    <div class="setting-row">
                        <div>
                            <strong>Profile Picture:</strong><br>
                            <img src="../uploads/profile_pictures/${student.profile_picture || 'default.png'}" 
                                 alt="Profile Picture" class="profile-preview">
                        </div>
                        <button class="edit-btn" onclick="editPicture()">Edit</button>
                    </div>
                </div>
            `;
        });
}

// Helper to render each row
function renderSettingRow(label, field, value) {
    return `
        <div class="setting-row" id="row_${field}">
            <div>
                <strong>${label}:</strong> 
                <span id="value_${field}" class="field-value">${value}</span>
            </div>
            <button class="edit-btn" onclick="editField('${field}')">Edit</button>
        </div>
    `;
}

// Edit a specific field
function editField(field) {
    const valueSpan = document.getElementById(`value_${field}`);
    const currentValue = valueSpan.innerText;

    let inputElement;
    if (field === "gender") {
        inputElement = `
            <select id="input_${field}" class="styled-input">
                <option value="Male" ${currentValue === "Male" ? "selected" : ""}>Male</option>
                <option value="Female" ${currentValue === "Female" ? "selected" : ""}>Female</option>
            </select>`;
    } else if (field === "students_password") {
        inputElement = `<input type="password" id="input_${field}" class="styled-input" placeholder="Enter new password">`;
    } else {
        inputElement = `<input type="text" id="input_${field}" class="styled-input" value="${currentValue}">`;
    }

    valueSpan.innerHTML = `
        ${inputElement}
        <div class="action-btns">
            <button class="save-btn" onclick="saveField('${field}')">Save</button>
            <button class="cancel-btn" onclick="loadSetting()">Cancel</button>
        </div>
    `;
}

// Edit profile picture
function editPicture() {
    const row = document.querySelector(".setting-row:last-child");
    row.innerHTML = `
        <form id="picForm" enctype="multipart/form-data" class="pic-form">
            <input type="file" name="profile_picture" class="styled-input" required>
            <div class="action-btns">
                <button type="submit" class="save-btn">Upload</button>
                <button type="button" class="cancel-btn" onclick="loadSetting()">Cancel</button>
            </div>
        </form>
    `;

    document.getElementById("picForm").onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch("update_student_profile.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(msg => {
            alert(msg);
            loadSetting();
        });
    };
}

// Save a single field
function saveField(field) {
    const newValue = document.getElementById(`input_${field}`).value;

    const formData = new FormData();
    formData.append(field, newValue);

    fetch("update_student_profile.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(msg => {
        alert(msg);
        loadSetting();
    });
}




// ✅ Dashboard
function student_dashboard() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <h2>Dashboard</h2>
        <p>Welcome to your student dashboard.</p>
        <div class="icon-grid">
            <div class="icon-card" data-content="courses"><i class="fas fa-book"></i><p>Your Courses</p></div>
            <div class="icon-card" data-content="profile"><i class="fas fa-user"></i><p>Profile</p></div>
            <div class="icon-card" data-content="notice_board"><i class="fas fa-bullhorn"></i><p>Notice Board</p></div>
            <div class="icon-card" data-content="complaints"><i class="fas fa-exclamation-circle"></i><p>Complaints</p></div>
            <div class="icon-card" data-content="about"><i class="fas fa-info-circle"></i><p>About Us</p></div>
        </div>
    `;
}




// ✅ View Courses
function viewCourses() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <h2 class="section-heading">📘 Your Courses</h2>
        <div id="course-list"></div>
        <div id="result-section" class="mt-6"></div> <!-- Added result section here -->
    `;

    fetch('fetch_student_courses.php')
        .then(res => res.json())
        .then(data => {
            const courseList = document.getElementById('course-list');

            if (data.error || data.length === 0) {
                courseList.innerHTML = `<p>${data.error || "No courses assigned yet."}</p>`;
                return;
            }

            data.forEach(course => {
                const container = document.createElement('div');
                container.className = 'course-container';

                const button = document.createElement('button');
                button.className = 'course-button';
                button.textContent = `📚 ${course.course_name}`;

                const subMenu = document.createElement('div');
                subMenu.className = 'submenu';
                subMenu.style.display = 'none';

                subMenu.innerHTML = `
                    <button class="option-button" onclick="DisplayCourseOutline('${course.course_id}', '${course.class_id}', '${course.course_name}')">Course Outline</button>
                    <button class="option-button" onclick="Assignments('${course.course_name}','${course.course_id}', '${course.class_id}')">Assignments</button>
                    <button class="option-button" onclick="Quizzes('${course.course_id}', '${course.class_id}', '${course.course_name}')">Quizzes</button>
                    <button class="option-button" onclick="handleOption('${course.course_name}', 'Time Table')">Time Table</button>
                    <button class="option-button" onclick="Messages('${course.course_name}', '${course.course_id}', '${course.class_id}')">Messages</button>
                    <button class="option-button" onclick="Display_attendence('${course.course_name}', '${course.course_id}', '${course.class_id}')">Attendence</button>
                    <button class="option-button" onclick="result('${course.course_name}', '${course.course_id}', '${course.class_id}')">Result</button>
                    <button class="option-button" onclick="instructor('${course.course_id}', '${course.class_id}', '${course.course_name}')">Instructor</button>
                `;

                button.addEventListener('click', () => {
                    subMenu.style.display = subMenu.style.display === 'flex' ? 'none' : 'flex';
                });

                container.appendChild(button);
                container.appendChild(subMenu);
                courseList.appendChild(container);
            });
        })
        .catch(error => {
            console.error("Course fetch error:", error);
            mainContent.innerHTML = "<p>Error loading courses.</p>";
        });

        // ftech instructor //

}


// attendence //
// ✅ Display Attendance
function Display_attendence(courseName, courseId, classId) {
    const mainContent = document.getElementById("main-content");
    mainContent.innerHTML = `<h2>📅 Attendance for ${courseName}</h2><div id="attendance-container"></div>`;

    fetch(`fetch_attendance.php?course_id=${courseId}&class_id=${classId}`)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                document.getElementById("attendance-container").innerHTML =
                    `<p style="color:red;">⚠ ${data.error}</p>`;
                return;
            }

            if (data.length === 0) {
                document.getElementById("attendance-container").innerHTML =
                    `<p>No attendance records found.</p>`;
                return;
            }

            // Group attendance by month
            const grouped = {};
            data.forEach(record => {
                const dateObj = new Date(record.date);
                const yearMonth = dateObj.toLocaleString('default', { month: 'long', year: 'numeric' });
                if (!grouped[yearMonth]) grouped[yearMonth] = [];
                grouped[yearMonth].push(record);
            });

            const container = document.getElementById("attendance-container");
            container.innerHTML = "";

            Object.keys(grouped).forEach(month => {
                const records = grouped[month];

                // Find all unique days in this month
                const monthDate = new Date(records[0].date);
                const year = monthDate.getFullYear();
                const monthIndex = monthDate.getMonth();
                const daysInMonth = new Date(year, monthIndex + 1, 0).getDate();

                // Create table
                let tableHTML = `<h3>${month}</h3><table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; margin-bottom:20px; width:100%;">`;
                tableHTML += `<tr><th>Day</th>`;

                for (let d = 1; d <= daysInMonth; d++) {
                    tableHTML += `<th>${d}</th>`;
                }
                tableHTML += `</tr>`;

                // Attendance row
                tableHTML += `<tr><td>Status</td>`;
                for (let d = 1; d <= daysInMonth; d++) {
                    const dateStr = `${year}-${String(monthIndex + 1).padStart(2, "0")}-${String(d).padStart(2, "0")}`;
                    const rec = records.find(r => r.date === dateStr);
                    if (rec) {
                        tableHTML += `<td style="text-align:center;">${rec.status === "present" ? "✅" : "❌"}</td>`;
                    } else {
                        tableHTML += `<td></td>`; // no record
                    }
                }
                tableHTML += `</tr></table>`;

                container.innerHTML += tableHTML;
            });
        })
        .catch(err => {
            console.error("Error fetching attendance:", err);
            document.getElementById("attendance-container").innerHTML =
                `<p style="color:red;">⚠ Error loading attendance records.</p>`;
        });
}



// ✅ View Instructor Details
function instructor(courseId, classId, courseName) {
    const mainContent = document.getElementById("main-content");

    // First show a loading message
    mainContent.innerHTML = `<h2 class="section-heading">👨‍🏫 Instructor Details for ${courseName}</h2>
                             <p>Loading instructor details...</p>`;

    fetch(`fetch_instructor.php?course_id=${courseId}&class_id=${classId}`)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                // If PHP sends an error message
                mainContent.innerHTML = `<h2 class="section-heading">👨‍🏫 Instructor for ${courseName}</h2>
                                         <p style="color:red;">${data.error}</p>`;
            } else {
                // Otherwise display instructor info
                mainContent.innerHTML = `
                    <h2 class="section-heading">👨‍🏫 Instructor for ${courseName}</h2>
                    <div class="instructor-card" style="
                        border: 2px solid #ddd; 
                        border-radius: 15px; 
                        padding: 20px; 
                        max-width: 500px; 
                        margin: 20px auto; 
                        text-align: center;
                        box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
                    ">
                        <img src="../uploads/teachers/${data.profile_picture}" 
                             alt="Instructor Picture" 
                             style="width:120px;height:120px;border-radius:50%;margin-bottom:15px;border:2px solid #aaa;">
                        <h3 style="margin: 5px 0;">${data.first_name} ${data.last_name} </h3>
                        <p><strong>Email:</strong> ${data.teachers_email}</p>
                        <p><strong>Phone:</strong> ${data.contact_number}</p>
                        <p><strong>Qualification:</strong> ${data.qualification}</p>
                    
                    </div>
                `;
            }
        })
        .catch(err => {
            console.error("Error fetching instructor:", err);
            mainContent.innerHTML = `<h2 class="section-heading">👨‍🏫 Instructor for ${courseName}</h2>
                                     <p style="color:red;">⚠ Error loading instructor details.</p>`;
        });
}


// ✅ Show Result in Clear Bordered Table
function result(courseName, courseId, classId) {
    const mainContent = document.getElementById("main-content");

    fetch(`fetch_result.php?course_id=${courseId}&class_id=${classId}`)
        .then(res => res.json())
        .then(data => {
            if (!data || Object.keys(data).length === 0 || data.error) {
                mainContent.innerHTML = `
                    <h2 style="font-size: 22px; font-weight: bold; margin-bottom: 10px;">📊 Result for ${courseName}</h2>
                    <div style="padding: 15px; background: #fff; border: 1px solid #000; border-radius: 8px; margin-bottom: 15px;">
                        <p style="color: gray;">❌ No result found for ${courseName}.</p>
                    </div>
                    <button style="margin-top: 10px; padding: 8px 15px; background: #007BFF; color: white; border: none; border-radius: 6px; cursor: pointer;"
                            onclick="viewCourses()">⬅️ Back</button>
                `;
                return;
            }

            let resultHTML = `
                <h2 style="font-size: 22px; font-weight: bold; margin-bottom: 10px;">📊 Result for ${courseName}</h2>
                <div style="overflow-x:auto; margin-bottom: 15px;">
                    <table style="width:100%; border: 2px solid black; border-collapse: collapse; text-align:center;">
                        <thead>
                            <tr style="background:#f0f0f0;">
                                <th style="border:2px solid black; padding:8px;">Component</th>
                                <th style="border:2px solid black; padding:8px;">Obtained</th>
                                <th style="border:2px solid black; padding:8px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border:2px solid black; padding:8px;">📝 Quiz</td>
                                <td style="border:2px solid black; padding:8px;">${data.quiz_obtained || 0}</td>
                                <td style="border:2px solid black; padding:8px;">${data.quiz_total || 0}</td>
                            </tr>
                            <tr>
                                <td style="border:2px solid black; padding:8px;">📚 Assignment</td>
                                <td style="border:2px solid black; padding:8px;">${data.assignment_obtained || 0}</td>
                                <td style="border:2px solid black; padding:8px;">${data.assignment_total || 0}</td>
                            </tr>
                            <tr>
                                <td style="border:2px solid black; padding:8px;">🧪 Exam</td>
                                <td style="border:2px solid black; padding:8px;">${data.exam_obtained || 0}</td>
                                <td style="border:2px solid black; padding:8px;">${data.exam_total || 0}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr style="background:#f0f0f0; font-weight:bold;">
                                <td style="border:2px solid black; padding:8px;">🎯 Percentage</td>
                                <td colspan="2" style="border:2px solid black; padding:8px;">${data.percentage || 0}%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <button style="margin-top: 10px; padding: 8px 15px; background: #007BFF; color: white; border: none; border-radius: 6px; cursor: pointer;"
                        onclick="viewCourses()">⬅️ Back</button>
            `;

            mainContent.innerHTML = resultHTML;
        })
        .catch(err => {
            console.error("Fetch error:", err);
            mainContent.innerHTML = `
                <p style="color:red;">⚠️ Error loading result</p>
                <button style="margin-top: 10px; padding: 8px 15px; background: #007BFF; color: white; border: none; border-radius: 6px; cursor: pointer;"
                        onclick="viewCourses()">⬅️ Back</button>
            `;
        });
}






// message
function Messages(courseName, courseId, classId) {
    const mainContent = document.getElementById("main-content");
    mainContent.innerHTML = `
        <button class="action-button" onclick="showCourses()">🔙 Back</button>
        <h2>💬 Chat - ${courseName}</h2>
        <p>Loading messages...</p>
    `;

    // Step 1: Fetch teacher_id for this course/class
    fetch(`get_teacher_for_course.php?course_id=${courseId}&class_id=${classId}`)
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            mainContent.innerHTML = `<p>❌ ${data.message}</p>`;
            return;
        }

        const teacherId = data.teacher_id;

        // Step 2: Fetch existing messages
        fetch(`fetch_messages.php?teacher_id=${teacherId}`)
        .then(res => res.json())
        .then(msgData => {
            if (!msgData.success) {
                mainContent.innerHTML = `<p>❌ ${msgData.message}</p>`;
                return;
            }

            let html = `
                <div id="chat-box" style="border:1px solid #ccc; height:300px; overflow-y:auto; padding:10px; background:#f9f9f9;">
            `;

            msgData.messages.forEach(msg => {
                let align = msg.sender_type === 'student' ? 'right' : 'left';
                let bg = msg.sender_type === 'student' ? '#dcf8c6' : '#fff';
                html += `
                    <div style="text-align:${align}; margin:5px;">
                        <div style="display:inline-block; background:${bg}; padding:8px; border-radius:5px; max-width:70%;">
                            ${msg.message}
                            <div style="font-size:10px; color:#777;">${msg.created_at}</div>
                        </div>
                    </div>
                `;
            });

            html += `</div>
                <div style="margin-top:10px;">
                    <input type="text" id="chat-message" placeholder="Type your message..." style="width:80%; padding:8px;">
                    <button onclick="sendStudentMessage(${teacherId})" style="padding:8px;">Send</button>
                </div>
            `;

            mainContent.innerHTML = html;

            // Auto scroll to bottom
            const chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    })
    .catch(err => {
        mainContent.innerHTML = `<p>❌ Failed to load messages.</p>`;
        console.error(err);
    });
}

function sendStudentMessage(teacherId) {
    const msgInput = document.getElementById("chat-message");
    const message = msgInput.value.trim();
    if (message === "") return;

    fetch("send_message.php", {
        method: "POST",
        body: new URLSearchParams({
            teacher_id: teacherId,
            message: message
        })
    })
    .then(res => res.text())
    .then(resp => {
        msgInput.value = "";
        // Refresh messages after sending
        fetch(`fetch_messages.php?teacher_id=${teacherId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const chatBox = document.getElementById("chat-box");
                chatBox.innerHTML = "";
                data.messages.forEach(msg => {
                    let align = msg.sender_type === 'student' ? 'right' : 'left';
                    let bg = msg.sender_type === 'student' ? '#dcf8c6' : '#fff';
                    chatBox.innerHTML += `
                        <div style="text-align:${align}; margin:5px;">
                            <div style="display:inline-block; background:${bg}; padding:8px; border-radius:5px; max-width:70%;">
                                ${msg.message}
                                <div style="font-size:10px; color:#777;">${msg.created_at}</div>
                            </div>
                        </div>
                    `;
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
    });
}


// ✅ Quizzes
function Quizzes(courseId, classId, courseName) {
    const mainContent = document.getElementById("main-content");

    fetch(`fetch_student_quizzes.php?course_id=${courseId}&class_id=${classId}`)
        .then(res => res.json())
        .then(quizzes => {
            let html = `<button class="action-button" onclick="viewCourses()">🔙 Back</button>
                        <h2>📝 Quizzes for ${courseName}</h2>`;

            if (!Array.isArray(quizzes) || quizzes.length === 0) {
                html += `<p>No quizzes available.</p>`;
                mainContent.innerHTML = html;
                return;
            }

            quizzes.forEach(q => {
                let now = new Date();
                let start = new Date(q.start_time);
                let end = new Date(q.end_time);
                let hasAttempted = q.attempted > 0;
                let notStarted = now < start;
                let ended = now > end;

                let disabled = notStarted || ended || hasAttempted;

                html += `
                    <div id="quiz_box_${q.quiz_id}" class="quiz-box" style="${disabled ? 'opacity:0.5;' : ''}">
                        <h3>${q.title}</h3>
                        <p><strong>Total Marks:</strong> ${q.total_marks}</p>
                        <p><strong>Start:</strong> ${start.toLocaleString()}</p>
                        <p><strong>End:</strong> ${end.toLocaleString()}</p>
                        <div id="timer_${q.quiz_id}"></div>
                        <div class="quiz-status">
                            ${hasAttempted ? `<p>✅ Already Attempted</p>` : ''}
                            ${notStarted ? `<p>⏳ Quiz will start soon</p>` : ''}
                        </div>
                        ${hasAttempted 
                            ? `<button onclick="viewResult(${q.quiz_id})">View Result</button>` 
                            : `<button onclick="AttendQuiz(${q.quiz_id}, '${q.title}', ${q.time_limit}, ${q.total_marks})" ${disabled ? 'disabled' : ''}>Attend Quiz</button>`
                        }
                    </div>
                `;

                // Countdown timers
                if (!disabled && !ended) {
                    startCountdown(`timer_${q.quiz_id}`, end);
                }
                if (notStarted) {
                    startCountdown(`timer_${q.quiz_id}`, start, true);
                }
            });

            mainContent.innerHTML = html;
        })
        .catch(err => {
            console.error("Error fetching quizzes:", err);
            mainContent.innerHTML = `<p>❌ Failed to load quizzes. Please try again later.</p>`;
        });
}

// ✅ Countdown helper
function startCountdown(elementId, targetTime, isStartCountdown = false) {
    let countDownDate = new Date(targetTime).getTime();

    let x = setInterval(() => {
        let now = new Date().getTime();
        let distance = countDownDate - now;

        if (distance <= 0) {
            clearInterval(x);
            document.getElementById(elementId).innerHTML = isStartCountdown 
                ? "<span style='color:green'>✅ Quiz Started</span>" 
                : "<span style='color:red'>⛔ Time Over</span>";
            return;
        }

        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById(elementId).innerHTML = `⏳ ${minutes}m ${seconds}s`;
    }, 1000);
}

// ✅ Attend Quiz
function AttendQuiz(quizId, quizTitle, timeLimit, totalMarks) {
    const mainContent = document.getElementById("main-content");

    fetch(`fetch_quiz_questions.php?quiz_id=${quizId}`)
        .then(res => res.json())
        .then(questions => {
            if (!Array.isArray(questions) || questions.length === 0) {
                mainContent.innerHTML = `<p>No questions available for this quiz.</p>`;
                return;
            }

            let html = `<button class="action-button" onclick="viewCourses()">🔙 Back</button>
                        <h2>📝 ${quizTitle}</h2>
                        <div id="quizTimer" style="font-weight:bold; color:red;"></div>
                        <form id="quizAttemptForm">
                            <input type="hidden" name="quiz_id" value="${quizId}">`;

            questions.forEach(q => {
                html += `
                    <div class="question-box">
                        <p><strong>${q.question_text}</strong></p>
                        <label><input type="radio" name="answer_${q.question_id}" value="A" required> ${q.option_a}</label><br>
                        <label><input type="radio" name="answer_${q.question_id}" value="B"> ${q.option_b}</label><br>
                        <label><input type="radio" name="answer_${q.question_id}" value="C"> ${q.option_c}</label><br>
                        <label><input type="radio" name="answer_${q.question_id}" value="D"> ${q.option_d}</label>
                    </div><hr>
                `;
            });

            html += `<button type="submit">Submit Quiz</button></form>`;
            mainContent.innerHTML = html;

            // Start quiz timer
            startQuizTimer(timeLimit, quizId, totalMarks);

            document.getElementById("quizAttemptForm").addEventListener("submit", function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                submitQuizForm(formData, quizId, totalMarks);
            });
        })
        .catch(err => {
            console.error("Error fetching quiz questions:", err);
            mainContent.innerHTML = `<p>❌ Failed to load quiz questions.</p>`;
        });
}

// ✅ Submit Quiz via AJAX
function submitQuizForm(formData, quizId, totalMarks) {
    fetch('submit_quiz.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(`✅ You scored ${data.score} / ${data.total}`);

            // Update quiz status in list
            const quizBox = document.getElementById(`quiz_box_${quizId}`);
            if (quizBox) {
                quizBox.querySelector(".quiz-status").innerHTML = `🏆 Score: ${data.score} / ${data.total}`;
                quizBox.querySelector("button").outerHTML = `<button onclick="viewResult(${quizId})">View Result</button>`;
                quizBox.style.opacity = "0.5";
            }

            viewCourses();
        } else {
            alert(`❌ ${data.message}`);
        }
    })
    .catch(err => {
        console.error("Error submitting quiz:", err);
        alert("❌ Failed to submit quiz. Please try again.");
    });
}

// ✅ Quiz Timer with Auto Submit
function startQuizTimer(minutes, quizId, totalMarks) {
    let endTime = Date.now() + minutes * 60 * 1000;
    let timerDisplay = document.getElementById("quizTimer");

    let timer = setInterval(() => {
        let remaining = endTime - Date.now();
        if (remaining <= 0) {
            clearInterval(timer);
            alert("⏰ Time is over! Your quiz will be submitted.");
            document.getElementById("quizAttemptForm").dispatchEvent(new Event('submit'));
            return;
        }

        let min = Math.floor((remaining / 1000) / 60);
        let sec = Math.floor((remaining / 1000) % 60);
        timerDisplay.innerHTML = `⏳ Time Left: ${min}m ${sec}s`;
    }, 1000);
}

// ✅ View Result
// ✅ View Result
function viewResult(quizId) {
    const mainContent = document.getElementById("main-content");

    fetch(`fetch_quiz_result.php?quiz_id=${quizId}`)
        .then(res => res.json())
        .then(data => {
            if (!Array.isArray(data) || data.length === 0) {
                mainContent.innerHTML = `<p>❌ No result data available.</p>`;
                return;
            }

            let html = `<button class="action-button" onclick="viewCourses()">🔙 Back</button>
                        <h2>📊 Quiz Result</h2>
                        <div class="result-container">`;

            data.forEach(q => {
                let correctAnswerText = q[`option_${q.correct_option.toLowerCase()}`];
                let studentAnswerText = q.selected_option 
                    ? q[`option_${q.selected_option.toLowerCase()}`] 
                    : "❌ Not Answered";

                let isCorrect = q.selected_option && q.selected_option === q.correct_option;

                html += `
                    <div class="result-question" style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                        <p><strong>${q.question_text}</strong></p>
                        <p>✅ Correct Answer: <b>${correctAnswerText}</b></p>
                        <p>📝 Your Answer: <b style="color:${isCorrect ? 'green' : 'red'}">${studentAnswerText}</b></p>
                        ${isCorrect ? `<p style="color:green;">✔ Correct</p>` : `<p style="color:red;">✖ Incorrect</p>`}
                    </div>
                `;
            });

            html += `</div>`;
            mainContent.innerHTML = html;
        })
        .catch(err => {
            console.error("Error fetching result:", err);
            mainContent.innerHTML = `<p>❌ Failed to load results.</p>`;
        });
}





// ✅ Course Outline
function DisplayCourseOutline(courseId, classId, courseName) {
    const mainContent = document.getElementById('main-content');

    fetch(`get_uploaded_outline.php?course_id=${courseId}&class_id=${classId}`)
        .then(res => res.json())
        .then(data => {
            let fileDisplay = data.exists ?
                `<p><strong>📎 Existing File:</strong> <a href="/MY-FYP/teacher_dashboard/uploads/course_outlines/${data.file_name}" target="_blank">View / Download</a></p>` :
                `<p><strong>No course outline uploaded yet.</strong></p>`;

            mainContent.innerHTML = `
                <button class="action-button" onclick="viewCourses()">Back</button>
                <h2>${courseName}</h2>
                <p>✅ Welcome to course outline</p>
                ${fileDisplay}
            `;
        })
        .catch(error => {
            console.error("Error loading course outline:", error);
            mainContent.innerHTML = `<p>Error loading course outline.</p>`;
        });
}


function showAssignmentForm(assignmentId, courseId, classId) {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <h2>📎 Submit Assignment</h2>
        <form action="submit_assignment.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="assignment_id" value="${assignmentId}">
            <input type="hidden" name="course_id" value="${courseId}">
            <input type="hidden" name="class_id" value="${classId}">

            <label for="assignment_file">Upload File:</label>
            <input type="file" name="assignment_file" required><br><br>

            <button type="submit">Submit Assignment</button>
        </form>
    `;
}

function openAssignmentForm(assignmentId) {
    const mainContent = document.getElementById("main-content");

    fetch(`submit_assignment_form.php?assignment_id=${assignmentId}`)
        .then(res => res.text())
        .then(data => {
            mainContent.innerHTML = data;
        })
        .catch(err => {
            console.error("Error loading form:", err);
        });
}


function showAssignmentForm(assignmentId, courseId, classId) {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <h2>📎 Submit Assignment</h2>
        <form action="submit_assignment.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="assignment_id" value="${assignmentId}">
            <input type="hidden" name="course_id" value="${courseId}">
            <input type="hidden" name="class_id" value="${classId}">

            <label for="assignment_file">Upload File:</label>
            <input type="file" name="assignment_file" required><br><br>

            <button type="submit">Submit Assignment</button>
        </form>
    `;
}

function openAssignmentForm(assignmentId) {
    const mainContent = document.getElementById("main-content");

    fetch(`submit_assignment_form.php?assignment_id=${assignmentId}`)
        .then(res => res.text())
        .then(data => {
            mainContent.innerHTML = data;
        })
        .catch(err => {
            console.error("Error loading form:", err);
        });
}


function Assignments(courseName, courseId, classId) {
    const mainContent = document.getElementById("main-content");

    fetch(`fetch_assignments.php?course_id=${courseId}&class_id=${classId}`)
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                mainContent.innerHTML = `
                    <h2 class="course-title">📂 Assignments for ${courseName}</h2>
                    <p class="no-assignments">No assignments found.</p>`;
                return;
            }

            let html = `<h2 class="course-title">📂 Assignments for ${courseName}</h2><div class="assignments-grid">`;

            data.forEach(item => {
                const dueDate = new Date(item.due_date);
                const currentDate = new Date();
                const expired = currentDate > dueDate;

                const buttonText = item.assignment_uploads ? "Change Assignment" : "Submit Assignment";

                // Extract file name from path (if uploaded)
                let fileName = "";
                if (item.assignment_uploads) {
                    fileName = item.assignment_uploads.split("/").pop(); 
                }

                html += `
                    <div class="assignment-card">
                        <h3>${item.assignment_title}</h3>
                        <p class="description">${item.assignment_description}</p>
                        <p><strong>Marks:</strong> ${item.marks}</p>
                        <p><strong>Due Date:</strong> ${item.due_date}</p>
                        
                        ${item.file_path ? `<p><a class="file-link" href="../teacher_dashboard/${item.file_path}" target="_blank">📎 Download Assignment File</a></p>` : ''}

                        ${item.assignment_uploads ? 
                            `<p><strong>📎 Your Submission:</strong> 
                                <a class="file-link" href="${item.assignment_uploads}" target="_blank">${fileName}</a>
                            </p>` : ''
                        }

                        <form class="upload-form" action="submit_assignment.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="assignment_id" value="${item.assignment_id}">
                            <input type="hidden" name="course_id" value="${courseId}">
                            <input type="hidden" name="class_id" value="${classId}">
                            <input class="file-input" type="file" name="assignment_file" ${expired ? 'disabled' : ''} required>
                            <button class="submit-btn" type="submit" ${expired ? 'disabled' : ''}>
                                ${expired ? '🚫 Submission Closed' : buttonText}
                            </button>
                        </form>
                    </div>
                `;
            });

            html += `</div>`;
            mainContent.innerHTML = html;

            // Add styles dynamically
            const style = document.createElement("style");
            style.innerHTML = `
                .course-title {
                    font-size: 24px;
                    margin-bottom: 20px;
                    color: #333;
                }
                .no-assignments {
                    font-size: 18px;
                    color: #888;
                }
                .assignments-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                    gap: 20px;
                }
                .assignment-card {
                    background: #fff;
                    border-radius: 12px;
                    padding: 20px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                    transition: transform 0.2s;
                }
                .assignment-card:hover {
                    transform: translateY(-4px);
                }
                .assignment-card h3 {
                    margin: 0 0 10px;
                    color: #0077cc;
                }
                .assignment-card .description {
                    font-size: 14px;
                    color: #555;
                    margin-bottom: 10px;
                }
                .file-link {
                    color: #0077cc;
                    text-decoration: none;
                }
                .file-link:hover {
                    text-decoration: underline;
                }
                .upload-form {
                    margin-top: 15px;
                }
                .file-input {
                    margin-bottom: 10px;
                }
                .submit-btn {
                    background: #0077cc;
                    color: white;
                    border: none;
                    padding: 10px 15px;
                    border-radius: 8px;
                    cursor: pointer;
                    font-size: 14px;
                    transition: background 0.3s;
                }
                .submit-btn:hover {
                    background: #005fa3;
                }
                .submit-btn:disabled {
                    background: #ccc;
                    cursor: not-allowed;
                }
            `;
            document.head.appendChild(style);
        })
        .catch(err => {
            console.error("Error fetching assignments:", err);
        });
}



function handleOption(courseName, action) {
    alert(`Selected "${action}" for course: ${courseName}`);
}

function loadProfile() {
    const mainContent = document.getElementById('main-content');
    fetch("profile_details.php")
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                mainContent.innerHTML = `<p>${data.error}</p>`;
                return;
            }
            mainContent.innerHTML = `
                <div class="profile-card-container">
                    <div class="profile-header">
                        <img src="../uploads/profile_pictures/${data.profile_picture}" alt="Profile Picture" class="profile-image">
                        <h2 class="profile-name">${data.first_name} ${data.last_name}</h2>
                        <p class="profile-class">📘 ${data.class_name}</p>
                    </div>
                    <div class="profile-body">
                        <div class="profile-row"><strong>📧 Email:</strong> ${data.students_email}</div>
                        <div class="profile-row"><strong>📱 Contact:</strong> ${data.contact_num}</div>
                        <div class="profile-row"><strong>🚻 Gender:</strong> ${data.gender}</div>
                        <div class="profile-row"><strong>🌍 Country:</strong> ${data.country}</div>
                        <div class="profile-row"><strong>🏞️ Province:</strong> ${data.provience}</div>
                        <div class="profile-row"><strong>🏡 District:</strong> ${data.district}</div>
                        <div class="profile-row"><strong>📅 Joined:</strong> ${new Date(data.created_at).toLocaleDateString()}</div>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error("Profile fetch error:", error);
            mainContent.innerHTML = `<p>Error loading profile.</p>`;
        });
}

function loadNoticeBoard() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <h2>Notice Board</h2>
        <ul>
            <li>Exam starts from next Monday</li>
            <li>Assignment deadline extended</li>
            <li>Library will be closed this Friday</li>
        </ul>
    `;
}

function loadComplaints() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <h2>Complaints</h2>
        <form>
            <label for="complaint">Enter your complaint:</label><br>
            <textarea id="complaint" rows="4" cols="50"></textarea><br><br>
            <button type="submit">Submit</button>
        </form>
    `;
}

function loadAbout() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <h2>About Us</h2>
        <p>This LMS helps students manage their academics easily and effectively.</p>
    `;
}


// Toggle submenu
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".has-submenu > .menu-item").forEach(item => {
        item.addEventListener("click", (e) => {
            e.preventDefault();
            const parent = item.parentElement;

            // Collapse other open submenus
            document.querySelectorAll(".has-submenu").forEach(sub => {
                if (sub !== parent) sub.classList.remove("active");
            });

            // Toggle current one
            parent.classList.toggle("active");
        });
    });
});
// ✅ Load content dynamically for Btn1, Btn2, Btn3
function loadBtnContent(buttonName) {
    const mainContent = document.getElementById("main-content");

    if (buttonName === "Btn1") {
        fetch("fetch_all_results.php")
            .then(res => res.json())
            .then(async data => {
                // ❌ If error (results not published or no results)
                if (!data || data.error || !data.results || data.results.length === 0) {
                    mainContent.innerHTML = `
                        <div style="text-align:center; margin-top:50px;">
                            <div style="display:inline-block; padding:20px 30px; background:#ffebee; color:#c62828; border:1px solid #c62828; border-radius:10px;">
                                <h2 style="margin:0; font-size:20px;">❌ ${data.error || "No results found."}</h2>
                            </div>
                        </div>
                    `;
                    return;
                }

                const studentName = data.student.first_name + " " + data.student.last_name;
                const className = data.student.class_name;

                let totalQuizObtained = 0, totalQuiz = 0;
                let totalAssignmentObtained = 0, totalAssignment = 0;
                let totalExamObtained = 0, totalExam = 0;

                // ✅ Wrap everything in a container so we can export it
                let tableHTML = `
                    <div id="result-section">
                        <div style="text-align:center; margin-bottom:20px;">
                            <img src="logo.png" alt="LMS Logo" style="max-width:120px; height:auto; margin-bottom:10px;">
                            <h2 style="margin:10px 0; font-size:24px; color:#222; font-weight:bold;">Result Sheet</h2>
                            
                            <div style="display:flex; justify-content:center; gap:40px; margin-top:10px; font-size:18px;">
                                <p><strong>👨‍🎓 Student:</strong> <span style="color:#007BFF;">${studentName}</span></p>
                                <p><strong>🏫 Class:</strong> <span style="color:#28A745;">${className}</span></p>
                                <p><strong>🆔 Registration #:</strong> <span style="color:#28A145;">${data.student.students_id}</span></p>
                            </div>
                        </div>
                       
                        <table border="1" cellspacing="0" cellpadding="8" 
                               style="width:100%; border-collapse: collapse; text-align:center; border:2px solid #333; font-size:15px;">
                            <thead style="background:#f4f4f4;">
                                <tr>
                                    <th rowspan="2">Course</th>
                                    <th colspan="2">Quiz</th>
                                    <th colspan="2">Assignment</th>
                                    <th colspan="2">Exam</th>
                                    <th rowspan="2">Percentage</th>
                                    <th rowspan="2">Grade</th>
                                </tr>
                                <tr>
                                    <th>Obtained</th>
                                    <th>Total</th>
                                    <th>Obtained</th>
                                    <th>Total</th>
                                    <th>Obtained</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                // ✅ Loop through results
                for (const result of data.results) {
                    totalQuizObtained += parseInt(result.quiz_obtained || 0);
                    totalQuiz += parseInt(result.quiz_total || 0);

                    totalAssignmentObtained += parseInt(result.assignment_obtained || 0);
                    totalAssignment += parseInt(result.assignment_total || 0);

                    totalExamObtained += parseInt(result.exam_obtained || 0);
                    totalExam += parseInt(result.exam_total || 0);

                    // ✅ Fetch grade for each course
                    let grade = "N/A";
                    try {
                        const res = await fetch(`fetch_grade.php?percentage=${result.percentage}`);
                        const gradeData = await res.json();
                        grade = gradeData.grade_name || "N/A";
                    } catch (e) {
                        console.error("Grade fetch error for course:", e);
                    }

                    tableHTML += `
                        <tr>
                            <td>${result.course_name}</td>
                            <td>${result.quiz_obtained}</td>
                            <td>${result.quiz_total}</td>
                            <td>${result.assignment_obtained}</td>
                            <td>${result.assignment_total}</td>
                            <td>${result.exam_obtained}</td>
                            <td>${result.exam_total}</td>
                            <td><strong>${result.percentage}%</strong></td>
                            <td><strong>${grade}</strong></td>
                        </tr>
                    `;
                }

                // ✅ Calculate overall percentage
                const grandTotalObtained = totalQuizObtained + totalAssignmentObtained + totalExamObtained;
                const grandTotalMarks = totalQuiz + totalAssignment + totalExam;
                const overallPercentage = grandTotalMarks > 0 
                                          ? ((grandTotalObtained / grandTotalMarks) * 100).toFixed(2) 
                                          : 0;

                // ✅ Fetch grade for overall
                let overallGrade = "N/A";
                try {
                    const res = await fetch(`fetch_grade.php?percentage=${overallPercentage}`);
                    const gradeData = await res.json();
                    overallGrade = gradeData.grade_name || "N/A";
                } catch (e) {
                    console.error("Grade fetch error for overall:", e);
                }

                // ✅ Add overall row
                tableHTML += `
                        <tr style="background:#e0f7fa; font-weight:bold;">
                            <td>Overall</td>
                            <td>${totalQuizObtained}</td>
                            <td>${totalQuiz}</td>
                            <td>${totalAssignmentObtained}</td>
                            <td>${totalAssignment}</td>
                            <td>${totalExamObtained}</td>
                            <td>${totalExam}</td>
                            <td><strong>${overallPercentage}%</strong></td>
                            <td><strong>${overallGrade}</strong></td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-top:25px; text-align:center;">
                    <div style="display:inline-block; padding:20px 40px; background:#007BFF; color:#fff; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.2);">
                        <h3 style="margin:0 0 10px 0; font-size:22px;">🏆 Final Result Summary</h3>
                        <p style="margin:5px 0; font-size:18px;"><strong>Overall Percentage:</strong> ${overallPercentage}%</p>
                        <p style="margin:5px 0; font-size:18px;"><strong>Grade:</strong> ${overallGrade}</p>
                    </div>
                </div>
            </div>
            `;

                // ✅ Add download button
                tableHTML += `
                    <div style="text-align:center; margin-top:20px;">
                        <button onclick="downloadResult()" 
                            style="padding:10px 20px; background:#28a745; color:#fff; font-size:16px; border:none; border-radius:5px; cursor:pointer;">
                            📥 Download Result
                        </button>
                    </div>
                `;

                mainContent.innerHTML = tableHTML;
            });
    }
}



// ✅ PDF Download Function
function downloadResult() {
    const element = document.getElementById("result-section");

    const opt = {
        margin:       [0.5, 0.5, 0.5, 0.5], // top, left, bottom, right
        filename:     'student_result.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, scrollY: 0 }, 
        jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' },
        pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] } // ✅ force full content
    };

    html2pdf().set(opt).from(element).save();
}


