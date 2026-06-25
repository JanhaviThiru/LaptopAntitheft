<?php
// Simple About page content, no need for session checks here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Laptop Anti-Theft System</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        header h1 {
            margin: 0;
            font-size: 26px;
        }

        nav ul {
            list-style: none;
            display: flex;
            margin: 0;
        }

        nav ul li {
            margin: 0 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        .content {
            padding: 50px 20px;
            text-align: center;
        }

        .about-container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        h2 {
            color: #2c3e50;
            font-size: 2em;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        h3 {
            color: #2980b9;
            font-size: 1.6em;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        ul {
            list-style: none;
            padding-left: 0;
            font-size: 1.1em;
        }

        ul li {
            margin-bottom: 10px;
            padding-left: 20px;
            position: relative;
        }

        ul li::before {
            content: "•";
            color: #2980b9;
            font-size: 2em;
            position: absolute;
            left: 0;
            top: 0;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px 0;
            font-size: 1.1em;
        }

        .btn {
            background-color: #2980b9;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            font-size: 1.1em;
            border-radius: 5px;
            text-align: center;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #3498db;
        }
    </style>
</head>
<body>

<header>
    <div class="header-container">
        <h1>Laptop Anti-Theft System</h1>
        <nav>
            <ul>
                <li><a href="web.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="content">
    <div class="about-container">
        <h2>About the Laptop Anti-Theft System</h2>
        <p>Our Laptop Anti-Theft System is a robust, innovative solution designed to protect your laptop from theft. Whether you're at home, in the office, or traveling, our system provides the peace of mind you need, ensuring that your device and sensitive data remain secure, even in the event of loss or theft.</p>

        <p>With advanced features like GPS tracking, camera activation, and remote data wipe, the Laptop Anti-Theft System ensures that you're always in control, no matter where your laptop ends up. It’s more than just theft prevention—it’s a comprehensive security tool for your device.</p>

        <h3>Key Features</h3>
        <ul>
            <li>Real-time laptop location tracking with GPS</li>
            <li>Activate laptop camera remotely to capture images of the thief</li>
            <li>Take remote screenshots to monitor the device's activity</li>
            <li>Trigger loud alarms to notify you or others of unauthorized access</li>
            <li>Remotely erase sensitive data to protect your privacy</li>
            <li>Lock your laptop remotely to prevent further access</li>
        </ul>

        <h3>Our Mission</h3>
        <p>We aim to provide users with a reliable and effective tool to safeguard their valuable devices and personal information. Theft prevention should be accessible, easy-to-use, and powerful enough to ensure that you’re never left powerless if your laptop is lost or stolen.</p>

        <h3>How It Works</h3>
        <p>Once you install the Laptop Anti-Theft application on your device, it works in the background to provide continuous monitoring. If your laptop goes missing, you can log into your account via the web interface to activate various security features:</p>
        <ul>
            <li>Track the current location of your laptop</li>
            <li>Activate the camera to capture photos of the thief</li>
            <li>Wipe important data remotely</li>
            <li>Lock your laptop to prevent further access</li>
        </ul>
        
        <p>Our system works in real-time, providing instant alerts and control over your device’s security, ensuring that you're always a step ahead in protecting your personal data.</p>

        <h3>Contact Us</h3>
        <p>If you have any questions or would like to learn more about our product, feel free to contact us. We are always here to help you secure your devices.</p>

        <a href="contact.php" class="btn">Contact Us</a>
    </div>
</div>

<footer>
    <p>&copy; 2024 Laptop Anti-Theft System. All rights reserved.</p>
</footer>

</body>
</html>
