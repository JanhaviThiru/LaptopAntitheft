<?php
$conn = new mysqli("localhost", "root", "", "laptop");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50));
    $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

    $sql = "UPDATE user SET reset_token=?, reset_expiry=? WHERE c_mail=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $token, $expiry, $email);
    
    if ($stmt->execute()) {
        echo "Reset link sent!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0F2027, #203A43, #2C5364);
            margin: 0;
            padding: 0;
            color: #fff;
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

        .success, .error {
            font-size: 16px;
            text-align: center;
            margin-top: 20px;
        }

        .success {
            color: #27ae60;
        }

        .error {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <form method="post">
            <div class="input-group">
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <button type="submit">Send Reset Link</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($stmt->affected_rows > 0) {
                echo "<p class='success'>Reset link sent! Please check your email.</p>";
            } else {
                echo "<p class='error'>Error occurred, please try again!</p>";
            }
        }
        ?>

        <p><a href="sign_in.php">Back to Sign In</a></p>
    </div>
</body>
</html>
