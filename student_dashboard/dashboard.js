document.addEventListener("DOMContentLoaded", () => {
    const dashboardMenuItem = document.querySelector("#dashboard-menu");
    const profileMenuItem = document.querySelector("#profile-menu");
    const mainContent = document.querySelector("#main-content");

    // Dashboard Menu Click Event
    dashboardMenuItem.addEventListener("click", () => {
        mainContent.innerHTML = `
            <h2 class="dashboard-title">Dashboard Options</h2>
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <i class="fas fa-book"></i>
                    <h3>Courses</h3>
                </div>
                <div class="dashboard-card">
                    <i class="fas fa-bullhorn"></i>
                    <h3>Notice Board</h3>
                </div>
                <div class="dashboard-card">
                    <i class="fas fa-calendar"></i>
                    <h3>Timetable</h3>
                </div>
                <div class="dashboard-card">
                    <i class="fas fa-user"></i>
                    <h3>Profile</h3>
                </div>
            </div>
        `;
    });

    // Profile Menu Click Event
    profileMenuItem.addEventListener("click", () => {
        fetch("profile_details.php")
            .then((response) => response.json())
            .then((data) => {
                if (data.error) {
                    mainContent.innerHTML = `<p>${data.error}</p>`;
                } else {
                    mainContent.innerHTML = `
                        <h2 class="profile-title">Profile Details</h2>
                        <div class="profile-container">
                            <div class="profile-card">
                                <h3>Personal Information</h3>
                                <p><strong>Name:</strong> ${data.students_name}</p>
                                <p><strong>Email:</strong> ${data.students_email}</p>
                                <p><strong>Gender:</strong> ${data.gender}</p>
                            </div>
                            <div class="profile-card">
                                <h3>Academic Information</h3>
                                <p><strong>Class:</strong> ${data.class}</p>
                                <p><strong>Country:</strong> ${data.country}</p>
                                <p><strong>Province:</strong> ${data.province}</p>
                            </div>
                            <div class="profile-card">
                                <h3>Additional Details</h3>
                                <p><strong>Joining Date:</strong> ${data.created_at}</p>
                            </div>
                        </div>
                    `;
                }
            })
            .catch((error) => {
                console.error("Error fetching profile details:", error);
                mainContent.innerHTML = `<p>Failed to load profile details.</p>`;
            });
    });
});
