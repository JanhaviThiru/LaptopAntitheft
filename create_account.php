<?php
session_start(); // Start session

// Enable output buffering to prevent "headers already sent" issue
ob_start(); 

// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your MySQL password if set
$dbname = "laptop"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve form data
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $mobile = $_POST['mobile'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // SQL query to insert data into users table
    $sql = "INSERT INTO user (c_name, c_mail, c_dob, c_mob, c_pass)
            VALUES ('$fullName', '$email', '$dob', '$mobile', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Redirect before any HTML is output
        header("Location: sign_in.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
}

ob_end_flush(); // Flush output buffer
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Laptop Antitheft</title>
    
    <!-- Link to Font Awesome for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <style>
        /* Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; justify-content: center; align-items: center; height: 100vh; background: linear-gradient(135deg, #0F2027, #203A43, #2C5364); }
        .container { background: rgba(255, 255, 255, 0.1); padding: 30px; border-radius: 10px; backdrop-filter: blur(10px); box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3); text-align: center; width: 400px; }
        h2 { color: #fff; margin-bottom: 20px; }
        .input-group { position: relative; text-align: left; margin-bottom: 15px; }
        .input-group label { color: #fff; font-size: 14px; margin-bottom: 5px; display: block; }
        .input-group input { width: 100%; padding: 10px 12px; border: none; border-radius: 5px; background: rgba(255, 255, 255, 0.2); color: #fff; }
        .eye-icon { position: absolute; right: 10px; top: 70%; transform: translateY(-50%); cursor: pointer; font-size: 18px; color: rgba(255, 255, 255, 0.7); }
        .eye-icon:hover { color: #1CB5E0; }
        button { width: 100%; padding: 12px; background: #1CB5E0; border: none; color: white; font-size: 16px; font-weight: bold; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0099CC; transform: scale(1.05); }
        p { color: #fff; margin-top: 15px; }
        p a { color: #1CB5E0; text-decoration: none; }
        p a:hover { color: #0099CC; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Account</h2>
        <form id="createAccountForm" action="create_account.php" method="post">
            <div class="input-group">
                <label for="fullName">Full Name:</label>
                <input type="text" id="fullName" name="fullName" required>
            </div>
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            <div class="input-group">
                <label for="mobile">Mobile Number:</label>
                <input type="tel" id="mobile" name="mobile" pattern="\d{10}" required placeholder="Enter 10-digit mobile number">
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <i class="fas fa-eye-slash eye-icon" onclick="togglePasswordVisibility('password')"></i>
            </div>
            <div class="input-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
                <i class="fas fa-eye-slash eye-icon" onclick="togglePasswordVisibility('confirmPassword')"></i>
            </div>
            <button type="submit">Create Account</button>
        </form>
        <p>Already have an account? <a href="sign_in.php">Sign In</a></p>
    </div>

    <script>
        function togglePasswordVisibility(id) {
            var passwordField = document.getElementById(id);
            var icon = document.querySelector(`#${id} + .eye-icon`);
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    </script>
</body>
</html>
