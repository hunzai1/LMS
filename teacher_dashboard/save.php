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
                showCourses();
                break;
            case 'profile':
                await showProfile();
                break;
            case 'notice':
                renderStaticContent('Notice Board', 'View important updates and announcements.');
                break;
            case 'students':
                renderStaticContent('Student Management', 'Manage your students, track attendance, and monitor progress.');
                break;
            case 'about':
                renderStaticContent('About Us', 'Learn more about the LMS and our mission.');
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
        <p>${message}</p>`;
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
                <i class="fas fa-users"></i>
                <p>Students</p>
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
    mainContent.innerHTML = `<h2>Loading courses...</h2>`; // Show loading state

    try {
        // Fetch the courses assigned to the teacher
        const response = await fetch('fetch_teacher_courses.php');
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const data = await response.json();

        if (data.error) {
            console.error(data.error);
            mainContent.innerHTML = `<p>Error: ${data.error}</p>`;
        } else {
            if (data.courses.length === 0) {
                mainContent.innerHTML = `
                    <h2>Courses</h2>
                    <p>No courses assigned yet.</p>`;
            } else {
                mainContent.innerHTML = `
                <h2>Courses</h2>
                <p>Explore your assigned courses below:</p>
                <div id="courses-list">
                    ${data.courses.map(course => `
                        <div class="course-item">
                            <div class="course-header">
                                <h3>${course.course_name}</h3>
                                <p>Class: ${course.class_name}</p>
                            </div>
                            <div class="options" style="display: none;">
                                <div onclick="handleOption('${course.course_name}', 'quizzes')">Quizzes</div>
                                <div onclick="handleOption('${course.course_name}', 'assignments')">Assignments</div>
                                <div onclick="handleOption('${course.course_name}', 'presentations')">Presentations</div>
                                <div onclick="handleOption('${course.course_name}', 'messages')">Messages</div>
                                <div onclick="handleOption('${course.course_name}', 'attendance')">Attendance</div>
                                <div onclick="handleOption('${course.course_name}', 'results')">Results</div>
                            </div>
                        </div>`).join('')}
                </div>`;
            

                // Add event listeners to toggle options
                document.querySelectorAll('.course-item').forEach(courseDiv => {
                    courseDiv.addEventListener('click', event => {
                        const options = courseDiv.querySelector('.options');
                        options.style.display = options.style.display === 'none' ? 'block' : 'none';
                    });
                });
            }
        }
    } catch (error) {
        console.error('Error fetching courses:', error);
        mainContent.innerHTML = `<p>An error occurred while loading the courses. Please try again later.</p>`;
    }
}

// Function to handle option clicks
function handleOption(courseName, option) {
    alert(`You selected ${option} for course: ${courseName}`);
    // Add logic to load specific content for the selected option
}



// Function to fetch and display the teacher's profile
async function showProfile() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `<h2>Loading profile...</h2>`; // Show loading state

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
                    <img id="profile-picture" src="uploads/teacher_profiles/${data.profile_picture}" alt="Profile Picture" 
                         style="width: 150px; height: 150px; border-radius: 50%;">
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
