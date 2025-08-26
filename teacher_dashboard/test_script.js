// Add event listeners to menu items
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', async event => {
        event.preventDefault(); // Prevent default link behavior
        const content = event.target.getAttribute('data-content');
        const mainContent = document.getElementById('main-content');

        switch (content) {
            case 'students':
                manageStudents();
                break;
            default:
                renderStaticContent('Welcome!', 'Select an option from the menu to display content.');
        }
    });
});

// Function to manage students
function manageStudents() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <h2>Manage Students</h2>
        <p>Student management functionality coming soon!</p>`;
    showClass();
}

// Fetch and display courses
async function showClass() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `<h2>Loading courses...</h2>`;

fetch('fetch_teacher_courses.php')
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
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
            <h2>Student Management</h2>
            <p>Manage students:</p>
            <div id="courses-list">
                ${data.courses.map(course => createCourseItems(course)).join('')}
            </div>`;
             
        document.querySelectorAll('.course-item').forEach(courseDiv => {
            courseDiv.addEventListener('click', () => toggleOptions(courseDiv));
        });
    })
    .catch(error => {
        console.error('Error fetching courses:', error);
        mainContent.innerHTML = `<p>An error occurred while loading the courses. Please try again later.</p>`;
    });

}

// Render static content
function renderStaticContent(title, message) {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <h2>${title}</h2>
        <p>${message}</p>`;
}

// Toggle visibility of course options
function toggleOptions(courseDiv) {
    const options = courseDiv.querySelector('.options');
    options.style.display = options.style.display === 'none' ? 'block' : 'none';
}

// Create HTML for a course item
function createCourseItems(course) {
    return `
        <div class="course-item">
            <div class="course-header">
                <h3>${course.class_name}</h3>
                <p>Class ID: ${course.class_id}</p>
                <p>Course Name: ${course.course_name}</p>
            </div>
            <div class="options" style="display: none;">
                <button class="option-button" onclick="Option1(${course.class_id}, 'View Students')">View Students</button>
                <button class="option-button" onclick="Option('${course.course_name}', 'View Quizzes')">View Quizzes</button>
                <button class="option-button" onclick="Option('${course.course_name}', 'View Assignments')">View Assignments</button>
                <button class="option-button" onclick="Option('${course.course_name}', 'View Presentations')">View Presentations</button>
                <button class="option-button" onclick="Option('${course.course_name}', 'View Results')">View Results</button>
            </div>
        </div>`;
}

// Fetch and display students by class
function Option1(classId, option) {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `<h2>Loading students for class ${classId}...</h2>`;

    fetch(`fetch_students_by_class.php?class_id=${classId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(students => {
            if (students.length === 0) {
                mainContent.innerHTML = `<h2>No students found for class ${classId}.</h2>`;
                return;
            }

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
                            </tr>`).join('')}
                    </tbody>
                </table>`;
        })
        .catch(error => {
            console.error('Error fetching students:', error);
            mainContent.innerHTML = `<p>An error occurred while loading the students. Please try again later.</p>`;
        });
}
