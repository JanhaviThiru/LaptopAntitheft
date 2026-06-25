<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: sign_in.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>

    <!-- CSS -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        h1 {
            font-size: 2.5em;
            margin: 0;
        }

        h2 {
            color: #2c3e50;
            font-size: 1.8em;
            margin-top: 30px;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 25px;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            line-height: 1.8;
        }

        p {
            font-size: 1.1em;
            margin-bottom: 20px;
        }

        ul {
            font-size: 1.1em;
            margin-bottom: 20px;
            padding-left: 20px;
        }

        .go-back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-size: 1.1em;
            transition: background-color 0.3s;
        }

        .go-back-btn:hover {
            background-color: #34495e;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #2c3e50;
            color: white;
            font-size: 1em;
        }
    </style>
</head>
<body>

<header>
    <h1>Privacy Policy</h1>
</header>

<div class="container">
    <h2>Introduction</h2>
    <p>We value your privacy and are committed to safeguarding your personal data. This Privacy Policy outlines how we collect, use, and protect your information when using our Laptop Anti-Theft system.</p>

    <h2>Information We Collect</h2>
    <p>We collect information such as your name, email, device location, and other account details when you register with our system. This data is necessary to provide you with personalized services and enhance the functionality of our system.</p>

    <h2>How We Use Your Information</h2>
    <p>Your information is used for the following purposes:</p>
    <ul>
        <li>Account management</li>
        <li>Laptop tracking and protection</li>
        <li>Sending notifications or alerts related to security</li>
    </ul>

    <h2>Data Protection</h2>
    <p>We implement strong security measures to ensure your data is protected. Your data is stored in secure servers, and we use encryption to safeguard sensitive information.</p>

    <h2>Your Rights</h2>
    <p>You have the right to access, update, and delete your personal information at any time. Please contact us if you wish to exercise these rights.</p>

    <h2>Changes to This Policy</h2>
    <p>We may update this Privacy Policy from time to time. Any changes will be communicated via this page, and the updated date will be reflected at the top.</p>

    <a href="javascript:history.back()" class="go-back-btn">Go Back</a>
</div>

<footer>
    <p>&copy; 2024 Laptop Anti-Theft System. All rights reserved.</p>
</footer>

</body>
</html>
