<?php
session_start();

// Handle logout request
if (isset($_GET['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy session
    header("Location: sign_in.php"); // Redirect to login page
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laptop";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['c_id'])) {
    header("Location: index.php");
    exit();
}

$c_id = $_SESSION['c_id']; // Get customer ID from session

// Check if 'cl_id' is passed in the URL
if (!isset($_GET['cl_id'])) {
    header("Location: index.php");
    exit();
}

$cl_id = $_GET['cl_id'];

// Fetch laptop details
$sql = "SELECT pc_name, locate, scream, p_msg, camera, screenshot, datawipe FROM client WHERE cl_id=? AND c_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $cl_id, $c_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Laptop not found or does not belong to you.");
}

$laptop = $result->fetch_assoc();
$pc_name = htmlspecialchars($laptop['pc_name']);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptop Anti-Theft - Control Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Arial', sans-serif;
            background-color: #e9ecef;
            color: #333;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: #343a40;
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
            font-size: 24px;
        }

        nav {
            margin-left: auto;
        }

        nav ul {
            list-style: none;
            display: flex;
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

        .welcome-message {
            font-size: 18px;
            font-weight: bold;
            margin: 0 auto;
            text-align: center;
        }

        .logout-form button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .logout-form button:hover {
            background-color: #c82333;
        }

        main {
            padding: 40px 20px;
            flex-grow: 1;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        .function-cards {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 28%;
        }

        .card img {
            max-width: 50px;
            margin-bottom: 10px;
        }

        .card h3 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 14px;
            color: #666;
        }

        .card a {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .card a:hover {
            text-decoration: underline;
        }
		footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px 20px; /* Reduced padding for a smaller footer */
            position: fixed; /* Fixed at the bottom of the page */
            bottom: 0;
            width: 100%;
            font-size: 12px; /* Smaller text */
        }

        footer .footer-container {
            display: flex;
            justify-content: center; /* Centered content */
            align-items: center;
        }

        footer .footer-links a {
            color: white;
            text-decoration: none;
            font-size: 12px; /* Smaller font size for links */
            margin: 0 10px;
        }

        footer .footer-links a:hover {
            text-decoration: underline;
        }

        footer p {
            margin: 0; /* Removed margin for a tighter footer */
            font-size: 12px;
        }

       
        /* Hover Effects */
        .card:hover {
            transform: scale(1.05);
            transition: all 0.3s ease-in-out;
        }


    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Laptop Anti-Theft System</h1>
            <p class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['c_name']); ?>.</p>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </nav>
            <form action="web.php" method="get" class="logout-form">
    <button type="submit" name="logout" class="logout-btn">Logout</button>
</form>

        </div>
    </header>

    <main>
        <div class="container">
            <h2>Selected Laptop: <?php echo $pc_name; ?></h2>
            <div class="function-cards">
                <div class="card">
                    <img src="https://img.freepik.com/premium-vector/my-pc-icon-icon_1076610-45182.jpg" alt="PC" />
                    <h3>Computer Name</h3>
                    <a href="sys.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>"
                           target="popup" 
                           onclick="window.open('sys.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>','popup','width=600,height=600'); return false;">
                           <?php echo htmlspecialchars($laptop['pc_name']); ?>
                        </a>
                    </div>
                    <div class="card">
                        <img src="https://www.shutterstock.com/image-vector/pin-point-logo-can-be-600nw-1679653036.jpg" alt="Location" />
                        <h3>Location</h3>
                        <p>Status: <?php if ($laptop['locate'] == 1): ?>
                                            Pending
                                    <?php elseif ($laptop['locate'] == 2): ?>
                                            Has been updated
                                    <?php else: ?>
                                            Not yet tried
                                    <?php endif; ?></p>
                        <a href="test1.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>" target="popup" 
                           onclick="window.open('test1.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>','popup','width=600,height=600'); return false;">
                          View Location</a>
                    </div>
                    <div class="card">
                        <img src="https://cdn-icons-png.flaticon.com/512/2014/2014952.png" alt="Alarm" />
                        <h3>Alarm</h3>
                        <p>Status: <?php echo ($laptop['scream'] == 1) ? 'Triggered' : 'Not playing'; ?></p>
                        <a href="scream.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>" 
                           target="popup"
                           onclick="window.open('scream.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>','popup','width=600,height=600'); return false;">
                           <?php echo $laptop['scream'] == 1 ? "Triggered" : "Not playing"; ?>
                        </a>
                    </div>
					<div class="card">
                        <img src="https://img.lovepik.com/element/45012/6089.png_860.png" alt="Popup" />
                        <h3>Pop-Up</h3>
						<p>Send Pop-Up Messages</p>
                        <a href="msg.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>" target="popup" 
                           onclick="window.open('msg.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>','popup','width=600,height=600'); return false;">
                          Pop-Up</a>
                    </div>
                    <div class="card">
                        <img src="https://cdn-icons-png.flaticon.com/512/5140/5140039.png" alt="Data Wipe" />
                        <h3>Data Wipe</h3>
                        <p>Status: <?php echo ($laptop['datawipe'] == 1) ? 'Triggered' : 'Not triggered'; ?></p>
                        <a href="dw.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>" 
                           target="popup"
                           onclick="window.open('dw.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>','popup','width=600,height=600'); return false;">
                           <?php echo $laptop['datawipe'] == 1 ? "Triggered" : "Not triggered"; ?>
                        </a>
                    </div>
                    <div class="card">
                        <img src="https://static.vecteezy.com/system/resources/thumbnails/002/261/132/small_2x/camera-icon-symbol-sign-isolate-on-white-background-illustration-eps-10-free-vector.jpg" alt="Camera" />
                        <h3>Camera</h3>
                        <p>Status: <?php echo ($laptop['camera'] == 1) ? 'Activated' : 'Not activated'; ?></p>
                        <a href="nor.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>" 
                           target="popup"
                           onclick="window.open('nor.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>','popup','width=600,height=600'); return false;">
                           <?php echo $laptop['camera'] == 1 ? "Activated" : "Not activated"; ?>
                        </a>
                    </div>
                    <div class="card">
                        <img src="https://cdn-icons-png.flaticon.com/512/4538/4538560.png" alt="Screenshot" />
                        <h3>Screenshot</h3>
                        <p>Status: <?php echo ($laptop['screenshot'] == 1) ? 'Captured' : 'Not Captured'; ?></p>
                        <a href="nors.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>" 
                           target="popup"
                           onclick="window.open('nors.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>','popup','width=600,height=600'); return false;">
                           <?php echo $laptop['screenshot'] == 1 ? "Captured" : "Not Captured"; ?>
                        </a>
                    </div>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-links">
                <a href="privacy.php">Privacy Policy</a>
                <a href="terms.php">Terms of Service</a>
            </div>
            <p>&copy; 2024 Laptop Anti-Theft System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
