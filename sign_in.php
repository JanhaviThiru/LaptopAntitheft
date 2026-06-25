<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Laptop Antitheft</title>
    <!-- Link to Font Awesome for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0F2027, #203A43, #2C5364);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 400px;
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            color: #fff;
            margin-bottom: 20px;
        }

        .input-group {
            position: relative;
            text-align: left;
            margin-bottom: 15px;
        }

        .input-group label {
            color: #fff;
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
        }

        .input-group input {
            width: 100%;
            padding: 10px 12px;
            border: none;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            transition: 0.3s ease-in-out;
        }

        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .input-group input:focus {
            background: rgba(255, 255, 255, 0.3);
            outline: none;
        }

        .eye-icon {
            position: absolute;
            right: 10px;
            top: 70%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.7);
            transition: 0.3s ease-in-out;
        }

        .eye-icon:hover {
            color: #1CB5E0;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #1CB5E0;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }

        button:hover {
            background: #0099CC;
            transform: scale(1.05);
        }

        .forgot-password p {
            color: #fff;
            margin-top: 15px;
        }

        .forgot-password a {
            color: #1CB5E0;
            text-decoration: none;
            transition: 0.3s ease-in-out;
        }

        .forgot-password a:hover {
            color: #0099CC;
            text-decoration: underline;
        }

        p {
            color: #fff;
            margin-top: 15px;
        }

        p a {
            color: #1CB5E0;
            text-decoration: none;
            transition: 0.3s ease-in-out;
        }

        p a:hover {
            color: #0099CC;
            text-decoration: underline;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 450px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign In</h2>
        
       <?php
        session_start();
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "laptop";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Prepare SQL statement to retrieve user data
            $sql = "SELECT * FROM user WHERE c_mail = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    // Verify hashed password
                    if (password_verify($password, $row['c_pass'])) {
                        // Password is correct, start the session
                        $_SESSION['loggedin'] = true;
                        $_SESSION['c_id'] = $row['c_id'];
                        $_SESSION['c_name'] = $row['c_name'];
                        $_SESSION['c_mail'] = $row['c_mail'];

                        echo "<p class='success'>Sign in successful! Welcome " . htmlspecialchars($row['c_name']) . ".</p>";
                        // Redirect to a protected page or perform other actions upon successful login
                        header("Location: index.php");
                        exit;
                    } else {
                        echo "<p class='error'>Invalid password!</p>";
                    }
                } else {
                    echo "<p class='error'>No account found with this email!</p>";
                }

                $stmt->close();
            } else {
                echo "<p class='error'>Error preparing statement: " . htmlspecialchars($conn->error) . "</p>";
            }

            $conn->close();
        }
        ?>

        <form id="signInForm" action="sign_in.php" method="post">
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <i class="fas fa-eye-slash eye-icon" onclick="togglePasswordVisibility('password')"></i>
            </div>
            <button type="submit">Sign In</button>
        </form>

        <div class="forgot-password">
            <p><a href="forgot_password.php">Forgot Password?</a></p>
        </div>

        <p>Don't have an account? <a href="create_account.php">Create Account</a></p>
    </div>

    <script>
        // Function to toggle password visibility
        function togglePasswordVisibility(id) {
            var passwordField = document.getElementById(id);
            var icon = document.querySelector(`#${id} + .eye-icon`);
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye"); // Change icon to "eye"
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash"); // Change icon back to "eye-slash"
            }
        }
    </script>
</body>
</html>
