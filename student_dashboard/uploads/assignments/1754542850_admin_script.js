
document.querySelectorAll('.menu-item, .icon-card').forEach(item => {
    item.addEventListener('click', event => {
        const content = event.target.getAttribute('data-content');
        const mainContent = document.getElementById('main-content');

        
        switch (content) {
            case 'dashboard':
                admin_dashboard();
                break;
            case 'teachers':
                Manage_Teachers();
                break;
            case 'students':
                stufun(); // Redirecting to the Dashboard as default behavior
                break;
            case 'courses':
                viewCourses(); // Function to display courses management
                break;
            case 'classes':
                  
                    mainContent.innerHTML = `
                    <h2>Manage Classes</h2>
                    <p>Manage classes-related tasks here.</p>`;
                    viewclasses();
                    break;
            case 'reports':
                mainContent.innerHTML = `
                    <h2>Reports</h2>
                    <p>View system reports and analytics.</p>`;
                break;
            case 'settings':
                mainContent.innerHTML = `
                    <h2>Settings</h2>
                    <p>Update system settings and configurations.</p>`;
                break;
            default:
                mainContent.innerHTML = `
                    <h2>Dashboard</h2>
                    <p>Select an option from the menu to manage the system.</p>`;
                    
        }
    });
});



// Dashboard //
function admin_dashboard() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <h2 >Dashboard Options</h2>
        <div>
            <button class="action-buttons" onclick="Manage_Teachers()">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>Manage Teachers</h3>
            </button>
            <button class="action-buttons" onclick="addCourses()">
                <i class="fas fa-user-graduate" ></i>
                <h3>Manage Students</h3>
            </button>
            <button class="action-buttons" onclick="viewCourses()">
                <i class="fas fa-book"></i>
                <h3>Manage Courses</h3>
                
            </button>
            <button class="action-buttons" onclick="viewclasses()">
                <i class="fas fa-chalkboard"></i>
                <h3>Manage Classes</h3>
            </button>
            <button class="action-buttons" onclick="addCourses()">
                <i class="fas fa-chart-line"></i>
                <h3>Reports</h3>
            </button>
            <button class="action-buttons" onclick="addCourses()">
                <i class="fas fa-cogs"></i>
                <h3>Settings</h3>
            </button>
            <button class="action-buttons" onclick="addCourses()">
                <i class="fas fa-sign-out-alt"></i>
                <h3>Logout</h3>
            </button>
        </div>
    `;
}


//manage  teacher //

function Manage_Teachers()
{
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
    <button class="action-button" onclick="admin_dashboard()">back</button>
        <h2>Manage Courses</h2>
        <p>Select a task to perform:</p>
        <div class="button-grid">
            <button class="action-button" onclick="Teacher_profile()">Teacher Profile</button>
            <button class="action-button" onclick="Add_Teacher()">Add Teacher</button>
            <button class="action-button" onclick="Remove_Teacher()">Remove Teacher</button>
            <button class="action-button" onclick="Edit_Teacher()">Edit Teacher</button>
            <button class="action-button" onclick="uploadCourseResources()">Upload Course Resources</button>
            <button class="action-button" onclick="manageEnrollment()">Manage Enrollment</button>
            <button class="action-button" onclick="viewCourse()">View courses</button>
        </div>
    `;

}


function Teacher_profile() {
    // Fetch teacher details from the server
    fetch('fetch_teachers.php')
        .then(response => response.json())
        .then(data => {
            const mainContent = document.getElementById('main-content'); // Target the main content area

            if (data.length > 0) {
                // Generate table and search option
                mainContent.innerHTML = `
                <button class="action-button" onclick="Manage_Teachers()">back</button>
                    <h2>Teacher Profiles</h2>
                    <input type="text" id="search-teacher" placeholder="Search by name..." oninput="filterTeachers()">
                    <table id="teacher-table" class="teachers-table">
                        <thead>
                            <tr>
                                <th>Teacher ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Gender</th>
                                <th>Country</th>
                                <th>Province</th>
                                <th>District</th>
                                <th>Subject Expertise</th>
                                <th>Qualification</th>
                                <th>Profile Picture</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data
                                .map(
                                    teacher => `
                                        <tr>
                                            <td>${teacher.teacher_id}</td>
                                            <td>${teacher.first_name}</td>
                                            <td>${teacher.last_name}</td>
                                            <td>${teacher.teachers_email}</td>
                                            <td>${teacher.contact_number}</td>
                                            <td>${teacher.gender}</td>
                                            <td>${teacher.country}</td>
                                            <td>${teacher.province}</td>
                                            <td>${teacher.district}</td>
                                            <td>${teacher.subject_expertise}</td>
                                            <td>${teacher.qualification}</td>
                                            <td><img src="uploads/teacher_profiles/${teacher.profile_picture}" alt="${teacher.first_name}" class="profile-pic"></td>
                                        </tr>
                                    `
                                )
                                .join('')}
                        </tbody>
                    </table>
                `;
            } else {
                mainContent.innerHTML = `
                    <h2>Teacher Profiles</h2>
                    <p>No teachers found.</p>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching teachers:', error);
            const mainContent = document.getElementById('main-content');
            mainContent.innerHTML = `
                <h2>Error</h2>
                <p>Unable to fetch teacher profiles. Please try again later.</p>
            `;
        });
}

// Filter function for search
function filterTeachers() {
    const searchInput = document.getElementById('search-teacher').value.toLowerCase();
    const rows = document.querySelectorAll('#teacher-table tbody tr');

    rows.forEach(row => {
        const teacherName = row.children[1].textContent.toLowerCase() + " " + row.children[2].textContent.toLowerCase();
        if (teacherName.includes(searchInput)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}


function Add_Teacher() {
    const mainContent = document.getElementById('main-content');

    mainContent.innerHTML = `
        <h2>Add New Teacher</h2>
        <form id="add-teacher-form" enctype="multipart/form-data">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
            
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
            
            <label for="teachers_email">Email:</label>
            <input type="email" id="teachers_email" name="teachers_email" required>
            
            <label for="teachers_password">Password:</label>
            <input type="password" id="teachers_password" name="teachers_password" required>
            
            <label for="contact_number">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" required>
            
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            
            <label for="country">Country:</label>
            <input type="text" id="country" name="country" required>
            
            <label for="province">Province:</label>
            <input type="text" id="province" name="province" required>
            
            <label for="district">District:</label>
            <input type="text" id="district" name="district" required>
            
            <label for="subject_expertise">Subject Expertise:</label>
            <input type="text" id="subject_expertise" name="subject_expertise" required>
            
            <label for="qualification">Qualification:</label>
            <input type="text" id="qualification" name="qualification" required>
            
            <label for="profile_picture">Profile Picture:</label>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
            
            <button type="submit" class="action-button">Add Teacher</button>
        </form>
        <div id="add-teacher-message"></div>
    `;

    // Attach submit event listener
    const addTeacherForm = document.getElementById('add-teacher-form');
    addTeacherForm.addEventListener('submit', handleAddTeacher);
}

// Handle the form submission
function handleAddTeacher(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    const formData = new FormData(document.getElementById('add-teacher-form')); // Collect form data

    // Send the data to the backend
    fetch('register_teacher.php', { // Updated endpoint to match backend script
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(result => {
            const messageDiv = document.getElementById('add-teacher-message');
            if (result.success) {
                messageDiv.innerHTML = `<p class="success-message">Teacher added successfully!</p>`;
                document.getElementById('add-teacher-form').reset(); // Reset the form
            } else {
                messageDiv.innerHTML = `<p class="error-message">Error: ${result.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error adding teacher:', error);
            const messageDiv = document.getElementById('add-teacher-message');
            messageDiv.innerHTML = `<p class="error-message">An error occurred. Please try again later.</p>`;
        });
}



function Remove_Teacher() {
    const mainContent = document.getElementById('main-content');

    mainContent.innerHTML = `
        <h2>Remove Teacher</h2>
        <form id="remove-teacher-form">
            <label for="teacher_id">Select Teacher:</label>
            <select id="teacher_id" name="teacher_id" required>
                <option value="" disabled selected>Select a teacher</option>
            </select>
            <button type="submit" class="action-button">Remove Teacher</button>
        </form>
        <div id="remove-teacher-message"></div>
    `;

    // Fetch teacher list and populate the dropdown
    fetch('fetch_teachers.php')
        .then(response => response.json())
        .then(teachers => {
            const teacherSelect = document.getElementById('teacher_id');
            teachers.forEach(teacher => {
                const option = document.createElement('option');
                option.value = teacher.teacher_id;
                option.textContent = `${teacher.first_name} ${teacher.last_name}`;
                teacherSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching teachers:', error);
            const messageDiv = document.getElementById('remove-teacher-message');
            messageDiv.innerHTML = `<p class="error-message">An error occurred while loading teachers. Please try again later.</p>`;
        });

    // Attach submit event listener
    const removeTeacherForm = document.getElementById('remove-teacher-form');
    removeTeacherForm.addEventListener('submit', handleRemoveTeacher);
}

// Handle the form submission for removing a teacher
function handleRemoveTeacher(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    const formData = new FormData(document.getElementById('remove-teacher-form')); // Collect form data

    // Send the data to the backend
    fetch('delete_teacher.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(result => {
            const messageDiv = document.getElementById('remove-teacher-message');
            if (result.success) {
                messageDiv.innerHTML = `<p class="success-message">${result.message}</p>`;
                document.getElementById('remove-teacher-form').reset(); // Reset the form
            } else {
                messageDiv.innerHTML = `<p class="error-message">Error: ${result.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error removing teacher:', error);
            const messageDiv = document.getElementById('remove-teacher-message');
            messageDiv.innerHTML = `<p class="error-message">An error occurred. Please try again later.</p>`;
        });
}



//courses managment //

function viewCourses() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
    <button class="action-button" onclick="admin_dashboard()">back</button>
        <h2>Manage Courses</h2>
        <p>Select a task to perform:</p>
        <div class="button-grid">
            <button class="action-button" onclick="addCourses()">Add Courses</button>
            <button class="action-button" onclick="editCourse()">Edit Course Details</button>
            <button class="action-button" onclick="loadDeleteForm()">Delete Courses</button>
            <button class="action-button" onclick="assignCourses()">Assign Courses</button>
            <button class="action-button" onclick="uploadCourseResources()">Upload Course Resources</button>
            <button class="action-button" onclick="manageEnrollment()">Manage Enrollment</button>
            <button class="action-button" onclick="viewCourse()">View courses</button>
        </div>
    `;
}


function viewCourse() {
    // Fetch courses from the server via AJAX
    fetch('fetch_courses.php')
        .then(response => response.json()) // Parse the JSON response
        .then(data => {
            const mainContent = document.getElementById('main-content'); // Target the main content area

            if (data.length > 0) {
                mainContent.innerHTML = `
                    <h2>Available Courses</h2>
                    <p>Below is a list of all available courses:</p>
                    <table>
                        <thead>
                            <tr>
                                <th>Course Name</th>
                            </tr>
                        </thead>
                        <tbody>
                        <button class="action-button" onclick="viewCourses()">back</button>
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





function addCourses() {
// Get the main content container
const mainContent = document.getElementById('main-content');

// Replace the content with course options
mainContent.innerHTML = `
<button class="action-button" onclick="viewCourses()">back</button>
 <div class="course_box1" style="max-width: 400px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); background-color: #f9f9f9;">
    <form action="action_button.php" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
        <label for="course" style="font-size: 16px; font-weight: bold; color: #333;">Course Name</label>
        <input type="text" id="course" name="course" required style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">

        <button type="submit" style="padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer; transition: background-color 0.3s;">
            Add Course
        </button>
    </form>

 
</div>


`;
}

function goBack() {
    console.log('goBack function triggered'); // Debugging log
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <h2>Dashboard</h2>
        <p>Welcome to the Admin Dashboard! Here you can manage all LMS activities.</p>`;
}

document.getElementById('main-content').addEventListener('click', function (event) {
    if (event.target.classList.contains('back-button')) {
        goBack();
    }
});


function editCourseDetails() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
        <div class="edit-course-container">
            <h2>Edit Course Details</h2>
            <form id="edit-course-form" action="edit_course_action.php" method="POST">
                <label for="course-id">Course ID</label>
                <input type="text" id="course-id" name="course_id" placeholder="Enter Course ID" required>
                
                <label for="course-name">Course Name</label>
                <input type="text" id="course-name" name="course_name" placeholder="Enter New Course Name" required>
                
                <button type="submit" class="btn save-btn">Save Changes</button>
                <button type="button" class="btn cancel-btn" onclick="viewCourses()">Cancel</button>
            </form>
        </div>
        <button class="action-button" onclick="viewCourses()">back</button>
    `;
}





function loadDeleteForm() {
    // Fetch courses from the server via AJAX
    fetch('fetch_courses.php')
        .then(response => response.json()) // Parse the JSON response
        .then(data => {
            const mainContent = document.getElementById('main-content'); // Target the main content area

            if (data.length > 0) {
                mainContent.innerHTML = `
                <button class="action-button" onclick="viewCourses()">back</button>
                    <h2>Delete a Course</h2>
                    <form id="delete-course-form">
                        <label for="course-select">Select a course to delete:</label>
                        <select id="course-select" name="course_id" required>
                            ${data
                                .map(
                                    course => `
                                        <option value="${course.course_id}">${course.course_name}</option>
                                    `
                                )
                                .join('')}
                        </select>
                        <button type="submit" class="action-button">Delete Course</button>
                    </form>
                    <div id="delete-message"></div>
                `;

                // Attach event listener to the form
                const deleteForm = document.getElementById('delete-course-form');
                deleteForm.addEventListener('submit', handleDeleteCourse);
            } else {
                mainContent.innerHTML = `
                    <h2>Delete a Course</h2>
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

function handleDeleteCourse(event) {
    event.preventDefault(); // Prevent form submission

    const courseSelect = document.getElementById('course-select'); 
    const courseName = courseSelect.options[courseSelect.selectedIndex].text; // Get selected course name

    // Send DELETE request to the server
    fetch('delete_course.php', {
        method: 'POST', // Use POST as DELETE may require extra configuration on some servers
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ course_name: courseName }), // Pass course_name
    })
        .then(response => response.json())
        .then(result => {
            const messageDiv = document.getElementById('delete-message');
            if (result.success) {
                messageDiv.innerHTML = `<p class="success-message">Course deleted successfully!</p>`;
                loadDeleteForm(); // Reload form to update the courses list
            } else {
                messageDiv.innerHTML = `<p class="error-message">Error deleting course: ${result.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error deleting course:', error);
            const messageDiv = document.getElementById('delete-message');
            messageDiv.innerHTML = `<p class="error-message">An error occurred. Please try again later.</p>`;
        });
}



function editCourse() {
    // Fetch courses to populate the dropdown
    fetch('fetch_courses.php')
        .then(response => response.json())
        .then(data => {
            const mainContent = document.getElementById('main-content'); // Target main content area

            if (data.length > 0) {
                // Create a form with a dropdown for courses
                mainContent.innerHTML = `
                <button class="action-button" onclick="viewCourses()">back</button>
                    <h2>Edit Course</h2>
                    <form id="edit-course-form">
                        <div>
                            <label for="course-select">Select Course:</label>
                            <select id="course-select" required>
                                <option value="" disabled selected>Select a course</option>
                                ${data
                                    .map(
                                        course => `
                                            <option value="${course.course_id}" data-description="${course.course_des}">
                                                ${course.course_name}
                                            </option>
                                        `
                                    )
                                    .join('')}
                            </select>
                        </div>
                        <div>
                            <label for="new-course-name">New Course Name:</label>
                            <input type="text" id="new-course-name" placeholder="Enter new course name" required />
                        </div>
                        <div>
                            <label for="new-course-description">New Course Description:</label>
                            <textarea id="new-course-description" placeholder="Enter new course description"></textarea>
                        </div>
                        <button type="submit">Update Course</button>
                        <button type="button" onclick="viewCourse()">Cancel</button>
                    </form>
                `;

                // Listen for changes in the dropdown to populate current course details
                const courseSelect = document.getElementById('course-select');
                courseSelect.addEventListener('change', function () {
                    const selectedOption = courseSelect.options[courseSelect.selectedIndex];
                    const currentDescription = selectedOption.getAttribute('data-description');
                    document.getElementById('new-course-name').value = selectedOption.text;
                    document.getElementById('new-course-description').value = currentDescription || '';
                });

                // Handle form submission
                const form = document.getElementById('edit-course-form');
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const selectedCourseId = courseSelect.value;
                    const newCourseName = document.getElementById('new-course-name').value;
                    const newCourseDescription = document.getElementById('new-course-description').value;

                    // Send updated data to the server
                    fetch('edit_course.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            course_id: selectedCourseId,
                            new_course_name: newCourseName,
                            new_course_description: newCourseDescription,
                        }),
                    })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                alert(result.message);
                                viewCourse(); // Reload the course list
                            } else {
                                alert('Error: ' + result.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error updating course:', error);
                            alert('An error occurred. Please try again later.');
                        });
                });
            } else {
                mainContent.innerHTML = `
                    <h2>Edit Course</h2>
                    <p>No courses found. Add a course first to edit it.</p>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching courses:', error);
            document.getElementById('main-content').innerHTML = `
                <h2>Error</h2>
                <p>Unable to fetch courses. Please try again later.</p>
            `;
        });
}



// for manage classes //

function viewclasses() {
    const mainContent = document.getElementById('main-content');
    mainContent.innerHTML = `
     <button class="action-button" onclick="admin_dashboard()">back</button>
        <h2>Manage Classes</h2>
        <p>Select a task to perform:</p>
        <div class="button-grid">
            <button class="action-button" onclick="loadAddClassForm()">add class</button>
            <button class="action-button" onclick="view_total_classes()">Classes list</button>
            <button class="action-button" onclick="edit_classes()">Edit Classes</button>
            <button class="action-button" onclick="loadDeleteClassForm()">Delete Class</button>
            <button class="action-button" onclick="assignCourseToClass()">Assign Courses to class</button>
            <button class="action-button" onclick="uploadCourseResources()">Upload Course Resources</button>
            <button class="action-button" onclick="manageEnrollment()">Manage Enrollment</button>
            
        </div>
        
    `;
}

function loadAddClassForm() {
    const mainContent = document.getElementById('main-content'); // Target the main content area

    mainContent.innerHTML = `
        <button class="action-button" onclick="viewclasses()">Back</button>
        <h2>Add a New Class</h2>
        <form id="add-class-form">
            <label for="class-name">Class Name:</label>
            <input type="text" id="class-name" name="class_name" required placeholder="Enter class name">
            
            <button type="submit" class="action-button">Add Class</button>
        </form>
        <div id="add-class-message"></div>
    `;

    // Attach event listener to the form
    const addClassForm = document.getElementById('add-class-form');
    addClassForm.addEventListener('submit', handleAddClass);
}

function handleAddClass(event) {
    event.preventDefault(); // Prevent form submission

    const className = document.getElementById('class-name').value.trim(); // Get the class name

    // Send POST request to the server
    fetch('add_class.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ class_name: className }),
    })
        .then(response => response.json())
        .then(result => {
            const messageDiv = document.getElementById('add-class-message');
            if (result.success) {
                messageDiv.innerHTML = `<p class="success-message">Class added successfully!</p>`;
                document.getElementById('add-class-form').reset(); // Reset the form
            } else {
                messageDiv.innerHTML = `<p class="error-message">Error adding class: ${result.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error adding class:', error);
            const messageDiv = document.getElementById('add-class-message');
            messageDiv.innerHTML = `<p class="error-message">An error occurred. Please try again later.</p>`;
        });
}






function view_total_classes() {
    // Fetch classes from the server via AJAX
    fetch('fetch_classes.php')
        .then(response => response.json()) // Parse the JSON response
        .then(data => {
            const mainContent = document.getElementById('main-content'); // Target the main content area

            if (data.length > 0) {
                mainContent.innerHTML = `
                <button class="action-button" onclick="viewclasses()">back</button>
                    <h2>Available Classes</h2>
                    <p>Below is a list of all available classes:</p>
                    <table>
                        <thead>
                            <tr>
                                <th>Class Name</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                            ${data
                                .map(
                                    classItem => `
                                        <tr>
                                            <td>${classItem.class_name}</td>
                                        </tr>
                                    `
                                )
                                .join('')}
                        </tbody>
                    </table>
                `;
            } else {
                mainContent.innerHTML = `
                <button class="action-button" onclick="viewclasses()">Back</button>
                    <h2>Available Classes</h2>
                    <p>No classes found. Please check back later.</p>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching classes:', error);
            const mainContent = document.getElementById('main-content');
            mainContent.innerHTML = `
                <h2>Error</h2>
                <p>Unable to fetch classes. Please try again later.</p>
            `;
        });
}



function edit_classes() {
    // Fetch classes to populate the dropdown
    fetch('fetch_classes.php')
        .then(response => response.json())
        .then(data => {
            const mainContent = document.getElementById('main-content'); // Target main content area

            if (data.length > 0) {
                // Create a form with a dropdown for classes
                mainContent.innerHTML = `
                <button class="action-button" onclick="viewclasses()">Back</button>
                    <h2>Edit Class</h2>
                    <form id="edit-class-form">
                        <div>
                            <label for="class-select">Select Class:</label>
                            <select id="class-select" required>
                                <option value="" disabled selected>Select a class</option>
                                ${data
                                    .map(
                                        classItem => `
                                            <option value="${classItem.class_id}">
                                                ${classItem.class_name}
                                            </option>
                                        `
                                    )
                                    .join('')}
                            </select>
                        </div>
                        <div>
                            <label for="new-class-name">New Class Name:</label>
                            <input type="text" id="new-class-name" placeholder="Enter new class name" required />
                        </div>
                        <button type="submit">Update Class</button>
                        <button type="button" onclick="viewclasses()">Cancel</button>
                    </form>
                `;

                // Listen for changes in the dropdown to populate current class details
                const classSelect = document.getElementById('class-select');
                classSelect.addEventListener('change', function () {
                    const selectedClassId = classSelect.value;
                    const selectedClassName = classSelect.options[classSelect.selectedIndex].text;
                    document.getElementById('new-class-name').value = selectedClassName;
                });

                // Handle form submission
                const form = document.getElementById('edit-class-form');
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const selectedClassId = classSelect.value;
                    const newClassName = document.getElementById('new-class-name').value;

                    // Send updated data to the server
                    fetch('edit_class.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            class_id: selectedClassId,
                            new_class_name: newClassName
                        }),
                    })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                alert(result.message);
                                viewclasses(); // Reload the class list
                            } else {
                                alert('Error: ' + result.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error updating class:', error);
                            alert('An error occurred. Please try again later.');
                        });
                });
            } else {
                mainContent.innerHTML = `
                <button class="action-button" onclick="viewclasses()">Back</button>
                    <h2>Edit Class</h2>
                    <p>No classes found. Add a class first to edit it.</p>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching classes:', error);
            document.getElementById('main-content').innerHTML = `
                <h2>Error</h2>
                <p>Unable to fetch classes. Please try again later.</p>
            `;
        });
}
function loadDeleteClassForm() {
    // Fetch classes from the server via AJAX
    fetch('fetch_classes.php')
        .then(response => response.json()) // Parse the JSON response
        .then(data => {
            const mainContent = document.getElementById('main-content'); // Target the main content area

            if (data.length > 0) {
                mainContent.innerHTML = `
               
                <button class="action-button" onclick="viewclasses()">Back</button>
                    <h2>Delete a Class</h2>
                    <form id="delete-class-form">
                        <label for="class-select">Select a class to delete:</label>
                        <select id="class-select" name="class_id" required>
                            ${data
                                .map(
                                    classItem => `
                                        <option value="${classItem.class_id}">${classItem.class_name}</option>
                                    `
                                )
                                .join('')}
                        </select>
                        <button type="submit" class="action-button">Delete Class</button>
                    </form>
                    <div id="delete-message"></div>
                `;

                // Attach event listener to the form
                const deleteForm = document.getElementById('delete-class-form');
                deleteForm.addEventListener('submit', handleDeleteClass);
            } else {
                mainContent.innerHTML = `
                <button class="action-button" onclick="viewclasses()">Back</button>
                    <h2>Delete a Class</h2>
                    <p>No classes found. Please check back later.</p>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching classes:', error);
            const mainContent = document.getElementById('main-content');
            mainContent.innerHTML = `
                <h2>Error</h2>
                <p>Unable to fetch classes. Please try again later.</p>
            `;
        });
}
function handleDeleteClass(event) {
    event.preventDefault(); // Prevent form submission

    const classSelect = document.getElementById('class-select');
    const classId = classSelect.value; // Get selected class ID
    const className = classSelect.options[classSelect.selectedIndex].text; // Get selected class name

    // Send DELETE request to the server
    fetch('delete_class.php', {
        method: 'POST', // Use POST as DELETE may require extra configuration on some servers
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ class_id: classId, class_name: className }), // Pass class_id and class_name
    })
        .then(response => response.json())
        .then(result => {
            const messageDiv = document.getElementById('delete-message');
            if (result.success) {
                messageDiv.innerHTML = `<p class="success-message">Class deleted successfully!</p>`;
                loadDeleteClassForm(); // Reload form to update the class list
            } else {
                messageDiv.innerHTML = `<p class="error-message">Error deleting class: ${result.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Error deleting class:', error);
            const messageDiv = document.getElementById('delete-message');
            messageDiv.innerHTML = `<p class="error-message">An error occurred. Please try again later.</p>`;
        });
}



function assignCourseToClass() {
    // Fetch classes and courses from the server via AJAX
    Promise.all([
        fetch('fetch_classes.php').then(response => response.json()),
        fetch('fetch_courses.php').then(response => response.json())
    ])
    .then(([classes, courses]) => {
        const mainContent = document.getElementById('main-content'); // Target the main content area

        if (classes.length > 0 && courses.length > 0) {
            mainContent.innerHTML = `
               <button class="action-button" onclick="viewclasses()">Back</button>
                <h2>Assign Course to Class</h2>
                <form id="assign-course-form">
                    <div>
                        <label for="class-select">Select Class:</label>
                        <select id="class-select" name="class_id" required>
                            <option value="" disabled selected>Select a class</option>
                            ${classes
                                .map(
                                    classItem => `
                                        <option value="${classItem.class_id}">${classItem.class_name}</option>
                                    `
                                )
                                .join('')}
                        </select>
                    </div>
                    <div>
                        <label for="course-select">Select Course:</label>
                        <select id="course-select" name="course_id" required>
                            <option value="" disabled selected>Select a course</option>
                            ${courses
                                .map(
                                    course => `
                                        <option value="${course.course_id}">${course.course_name}</option>
                                    `
                                )
                                .join('')}
                        </select>
                    </div>
                    <button class="action-button" onclick=" showAssignedCourses()">show assign courses</button>
                    <button type="submit" class="action-button">Assign Course</button>
                </form>
                <div id="assign-message"></div>
            `;

            // Attach event listener to handle form submission
            const assignForm = document.getElementById('assign-course-form');
            assignForm.addEventListener('submit', handleAssignCourse);
        } else {
            mainContent.innerHTML = `
                <h2>Assign Course to Class</h2>
                <p>No classes or courses found. Please add them before assigning.</p>
            `;
        }
    })
    .catch(error => {
        console.error('Error fetching classes or courses:', error);
        const mainContent = document.getElementById('main-content');
        mainContent.innerHTML = `
            <h2>Error</h2>
            <p>Unable to fetch classes or courses. Please try again later.</p>
        `;
    });
}

function handleAssignCourse(event) {
    event.preventDefault(); // Prevent form submission

    const classSelect = document.getElementById('class-select');
    const courseSelect = document.getElementById('course-select');
    const classId = classSelect.value;
    const courseId = courseSelect.value;

    // Send the assignment data to the server
    fetch('assign_course.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ class_id: classId, course_id: courseId })
    })
    .then(response => response.json())
    .then(result => {
        const messageDiv = document.getElementById('assign-message');
        if (result.success) {
            messageDiv.innerHTML = `<p class="success-message">Course assigned successfully!</p>`;
            assignCourseToClass(); // Reload the form to update the list
        } else {
            messageDiv.innerHTML = `<p class="error-message">Error assigning course: ${result.message}</p>`;
        }
    })
    .catch(error => {
        console.error('Error assigning course:', error);
        const messageDiv = document.getElementById('assign-message');
        messageDiv.innerHTML = `<p class="error-message">An error occurred. Please try again later.</p>`;
    });
}




function showAssignedCourses() {
    // Fetch assigned courses from the server
    fetch('fetch_assigned_courses.php')
        .then(response => response.json())
        .then(data => {
            const mainContent = document.getElementById('main-content'); // Target the main content area

            if (data.length > 0) {
                // Generate table for displaying assigned courses
                mainContent.innerHTML = `
                    <button class="action-button" onclick="assignCourseToClass()">Back</button>
                    <h2>Assigned Courses</h2>
                    <table class="assigned-courses-table">
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Course Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data
                                .map(
                                    row => `
                                        <tr>
                                            <td>${row.class_name}</td>
                                            <td>${row.course_name}</td>
                                        </tr>
                                    `
                                )
                                .join('')}
                        </tbody>
                    </table>
                `;
            } else {
                // Show message if no assigned courses exist
                mainContent.innerHTML = `
                    <h2>Assigned Courses</h2>
                    <p>No assigned courses found. Please assign a course to a class.</p>
                    <button class="action-button" onclick="assignCourseToClass()">Assign Course</button>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching assigned courses:', error);
            const mainContent = document.getElementById('main-content');
            mainContent.innerHTML = `
                <h2>Error</h2>
                <p>Unable to fetch assigned courses. Please try again later.</p>
            `;
        });
}

//end.........//

function deleteCourses() {
    alert('Delete Courses function triggered.');
}

function assignCourses() {
    alert('Assign Courses function triggered.');
}

function uploadCourseResources() {
    alert('Upload Course Resources function triggered.');
}

function manageEnrollment() {
    alert('Manage Enrollment function triggered.');
}

function trackCoursePerformance() {
    alert('Track Course Performance function triggered.');
}




