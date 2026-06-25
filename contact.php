<?php
// Simple Contact page content, no need for session checks here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Laptop Anti-Theft System</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
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
            margin: 0 15px;
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

        .contact-container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        h2 {
            font-size: 2em;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .contact-info p {
            font-size: 1.1em;
            margin-bottom: 15px;
        }

        .contact-form {
            margin-top: 40px;
        }

        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 15px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1.1em;
            box-sizing: border-box;
        }

        .contact-form input[type="submit"] {
            background-color: #2980b9;
            color: white;
            font-size: 1.2em;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }

        .contact-form input[type="submit"]:hover {
            background-color: #3498db;
        }

        .contact-form textarea {
            height: 150px;
            resize: vertical;
        }

        h3 {
            font-size: 1.5em;
            color: #2980b9;
            margin-top: 30px;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px 0;
            font-size: 1.1em;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 10px;
        }

        ul li a {
            color: #2980b9;
            text-decoration: none;
        }

        ul li a:hover {
            text-decoration: underline;
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
    <div class="contact-container">
        <h2>Contact Us</h2>
        <div class="contact-info">
            <p>If you have any questions, need assistance, or want to provide feedback, feel free to get in touch with us!</p>
            <p>Email: <a href="mailto:support@laptopantitheft.com">support@laptopantitheft.com</a></p>
            <p>Phone: <a href="tel:+919819738721">+91 9819738721</a></p>
            <p>Address: Markendya, Mumbai</p>
        </div>

        <h3>Contact Form</h3>
        <form class="contact-form" action="submit_form.php" method="post">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Your Message:</label>
            <textarea id="message" name="message" required></textarea>

            <input type="submit" value="Send Message">
        </form>

        <h3>Follow Us</h3>
        <p>Stay connected with us on social media for updates and new features:</p>
        <ul>
            <li><a href="https://twitter.com/laptopantitheft">Twitter</a></li>
            <li><a href="https://facebook.com/laptopantitheft">Facebook</a></li>
            <li><a href="https://instagram.com/laptopantitheft">Instagram</a></li>
        </ul>
    </div>
</div>

<footer>
    <p>&copy; 2024 Laptop Anti-Theft System. All rights reserved.</p>
</footer>

</body>
</html>
