<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "fyp";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch class names from the `classes` table
$classQuery = "SELECT class_id, class_name FROM classes";
$classResult = $conn->query($classQuery);

if ($classResult === false) {
    die("Error fetching class names: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="form-container">
        <h1>Student Registration</h1>
        <form action="student_registration_form.php" method="POST" enctype="multipart/form-data" class="registration-form">
            <!-- First Name -->
            <div class="form-group">
                <label for="first_name">First name</label>
                <input type="text" id="first_name" name="first_name" required>
            </div>

            <!-- Last Name -->
            <div class="form-group">
                <label for="last_name">Last name</label>
                <input type="text" id="last_name" name="last_name" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="students_email">Email</label>
                <input type="email" id="students_email" name="students_email" required>
            </div>

            <!-- Contact Number -->
            <div class="form-group">
                <label for="contact_number">Contact number</label>
                <input type="number" id="contact_number" name="contact_number" required>
            </div>

            <!-- Gender -->
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <!-- Country -->
            <div class="form-group">
                <label for="country">Country</label>
                <select id="country" name="country" required>
                    <option value="pakistan">Pakistan</option>
                    <option value="china">China</option>
                    <option value="USA">USA</option>
                </select>
            </div>

            <!-- Province -->
            <div class="form-group">
                <label for="provience">Province</label>
                <select id="provience" name="provience" required>
                    <option value="Gilgit_Baltistan">Gilgit Baltistan</option>
                    <option value="sindh">Sindh</option>
                    <option value="punjab">Punjab</option>
                </select>
            </div>

            <!-- District -->
            <div class="form-group">
                <label for="district">District</label>
                <select id="district" name="district" required>
                    <option value="Hunza">Hunza</option>
                    <option value="Ghizer">Ghizer</option>
                    <option value="skardu">Skardu</option>
                    <option value="gilgit">Gilgit</option>
                </select>
            </div>

            <!-- Class -->
            <div class="form-group">
                <label for="class">Class</label>
                <select id="class" name="class_id" required>
                    <?php
                    if ($classResult->num_rows > 0) {
                        // Fetch and display each class as an option
                        while ($row = $classResult->fetch_assoc()) {
                            echo "<option value='{$row['class_id']}'>{$row['class_name']}</option>";
                        }
                    } else {
                        echo "<option value=''>No classes available</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Profile Picture -->
            <div class="form-group">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="students_password">Password</label>
                <input type="password" id="students_password" name="students_password" required>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="submit-btn">Register</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
