<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="form-container">
        <h1>Teacher Registration</h1>
        <form action="teacher_registration_form.php" method="POST" enctype="multipart/form-data" class="registration-form">
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
                <label for="teachers_email">Email</label>
                <input type="email" id="teachers_email" name="teachers_email" required>
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
                <label for="province">Province</label>
                <select id="province" name="province" required>
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

            <!-- Subject Expertise -->
            <div class="form-group">
                <label for="subject_expertise">Subject Expertise</label>
                <input type="text" id="subject_expertise" name="subject_expertise" required>
            </div>

            <!-- Qualification -->
            <div class="form-group">
                <label for="qualification">Qualification</label>
                <input type="text" id="qualification" name="qualification" required>
            </div>

            <!-- Profile Picture -->
            <div class="form-group">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="teachers_password">Password</label>
                <input type="password" id="teachers_password" name="teachers_password" required>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="submit-btn">Register</button>
            </div>
        </form>
    </div>
</body>
</html>
