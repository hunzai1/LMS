<?php
// Database connection details
$host = "localhost";
$username = "root"; // Default XAMPP username
$password = "";     // Default XAMPP password
$dbname = "FYP"; // Your database name

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $first_name = $conn->real_escape_string($_POST["first_name"]);
    $last_name = $conn->real_escape_string($_POST["last_name"]);
    $teachers_email = $conn->real_escape_string($_POST["teachers_email"]);
    $contact_number = $conn->real_escape_string($_POST["contact_number"]);
    $gender = $conn->real_escape_string($_POST["gender"]);
    $country = $conn->real_escape_string($_POST["country"]);
    $province = $conn->real_escape_string($_POST["province"]);
    $district = $conn->real_escape_string($_POST["district"]);
    $subject_expertise = $conn->real_escape_string($_POST["subject_expertise"]);
    $qualification = $conn->real_escape_string($_POST["qualification"]);
    $teachers_password = password_hash($_POST["teachers_password"], PASSWORD_DEFAULT); // Hash the password

    // Handle profile picture upload
    $profile_picture = $_FILES["profile_picture"];
    $target_dir = "uploads/teacher_profiles/";
    $target_file = $target_dir . basename($profile_picture["name"]);
    $upload_ok = 1;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an image
    $check = getimagesize($profile_picture["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $upload_ok = 0;
    }

    // Check file size (limit: 2MB)
    if ($profile_picture["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $upload_ok = 0;
    }

    // Allow certain file formats
    if (!in_array($image_file_type, ["jpg", "png", "jpeg", "gif"])) {
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        $upload_ok = 0;
    }

    // Try to upload the file
    if ($upload_ok && move_uploaded_file($profile_picture["tmp_name"], $target_file)) {
        // Insert data into the database
        $sql = "INSERT INTO teachers (
                    first_name, last_name, teachers_email, contact_number, gender, country, province, district,
                    subject_expertise, qualification, profile_picture, teachers_password
                ) VALUES (
                    '$first_name', '$last_name', '$teachers_email', '$contact_number', '$gender', '$country',
                    '$province', '$district', '$subject_expertise', '$qualification', '$target_file', '$teachers_password'
                )";

        if ($conn->query($sql) === TRUE) {
            echo "Teacher registered successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Sorry, your profile picture could not be uploaded.";
    }
}

// Close the connection
$conn->close();
?>
