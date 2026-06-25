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
    <title>Terms of Service</title>

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
            background-color: #2980b9;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        h1 {
            font-size: 2.5em;
            margin: 0;
        }

        h2 {
            color: #2980b9;
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
            background-color: #2980b9;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-size: 1.1em;
            transition: background-color 0.3s;
        }

        .go-back-btn:hover {
            background-color: #3498db;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #2980b9;
            color: white;
            font-size: 1em;
        }
    </style>
</head>
<body>

<header>
    <h1>Terms of Service</h1>
</header>

<div class="container">
    <h2>Introduction</h2>
    <p>By using our Laptop Anti-Theft system, you agree to abide by the following Terms of Service. Please read them carefully before using our platform.</p>

    <h2>Acceptance of Terms</h2>
    <p>By accessing or using the services, you agree to be bound by these terms. If you do not agree with any part of these terms, you should not use our services.</p>

    <h2>Account Registration</h2>
    <p>You must provide accurate and complete information when registering for an account. You are responsible for keeping your login credentials secure.</p>

    <h2>Use of Service</h2>
    <p>You agree to use the service for lawful purposes only. You may not misuse the service or use it in a way that could damage or disrupt the system.</p>

    <h2>Termination of Account</h2>
    <p>We reserve the right to suspend or terminate your account if you violate these terms. You may also terminate your account at any time by contacting support.</p>

    <h2>Limitations of Liability</h2>
    <p>Our liability for any damages resulting from the use of our services is limited to the extent permitted by law.</p>

    <h2>Changes to Terms</h2>
    <p>We may update these terms at any time. Any changes will be reflected on this page, and we will notify users when significant updates occur.</p>

    <a href="javascript:history.back()" class="go-back-btn">Go Back</a>
</div>

<footer>
    <p>&copy; 2024 Laptop Anti-Theft System. All rights reserved.</p>
</footer>

</body>
</html>
