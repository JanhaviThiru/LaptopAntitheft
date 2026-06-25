<?php 
session_start();

// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: sign_in.php");
    exit;
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

$c_id = $_SESSION['c_id'];
$sql = "SELECT cl_id, pc_name FROM client WHERE c_id='$c_id'";
$result = $conn->query($sql);
$laptops = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $laptops[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Laptops</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: linear-gradient(to right, #1e3c72, #2a5298); color: white; text-align: center; }

        /* Header */
        .header { background: rgba(0, 0, 0, 0.7); padding: 15px; display: flex; justify-content: space-between; align-items: center; }
        .header h2 { margin: 0; color: #f8f9fa; }
        .logout-btn { background: #ff4b5c; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; }
        .logout-btn:hover { background: #e03e50; }

        /* Laptop Cards */
        .laptop-container { display: flex; flex-wrap: wrap; justify-content: center; margin-top: 30px; }
        .laptop-card { background: white; padding: 20px; margin: 15px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); width: 220px; text-align: center; transition: 0.3s; color: black; }
        .laptop-card:hover { transform: translateY(-5px); box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3); }
        .laptop-card img { width: 100px; }
        .laptop-card a { display: block; margin-top: 10px; font-size: 18px; text-decoration: none; color: #007bff; font-weight: bold; }
        .laptop-card a:hover { text-decoration: underline; }

        /* Features Section */
        .features-section { margin-top: 50px; padding: 30px; background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        .features { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; }
        .feature-box { background: white; color: black; padding: 15px; width: 300px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); text-align: center; }
        .feature-box img { width: 100px; }

        /* Reviews Section */
        .reviews-section { margin-top: 50px; padding: 30px; }
        .review-box { background: white; color: black; padding: 15px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); width: 300px; margin: 10px auto; text-align: center; }
        .review-box img { width: 50px; border-radius: 50%; }

        /* Video Section */
        .video-section { margin-top: 50px; }
        iframe { width: 80%; height: 300px; border-radius: 10px; margin-top: 20px; }

        /* Footer */
        .footer { margin-top: 50px; padding: 20px; background: rgba(0, 0, 0, 0.9); }
        .footer p { margin: 0; color: white; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['c_name']); ?></h2>
        <form action="web.php" method="get">
            <button type="submit" name="logout" class="logout-btn">Logout</button>
        </form>
    </div>

    <!-- Laptop Selection -->
    <h2><br>Choose Your Laptop</h2>
    <div class="laptop-container">
        <?php foreach ($laptops as $laptop): ?>
            <div class="laptop-card">
                <img src="https://img.freepik.com/premium-vector/my-pc-icon-icon_1076610-45182.jpg" alt="Laptop">
                <a href="web.php?cl_id=<?php echo urlencode($laptop['cl_id']); ?>">
                    <?php echo htmlspecialchars($laptop['pc_name']); ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Features Section -->
    <div class="features-section">
        <h2>Why Choose Laptop Antitheft?</h2><br><br>
        <div class="features">
            <div class="feature-box">
                <img src="https://cdn-icons-png.flaticon.com/512/2958/2958783.png" alt="GPS">
                <p>Track your lost laptop with real-time GPS location.</p>
            </div>
            <div class="feature-box">
                <img src="https://cdn-icons-png.flaticon.com/512/747/747376.png" alt="Camera">
                <p>Activate the camera remotely and capture intruder images.</p>
            </div>
            <div class="feature-box">
                <img src="https://cdn-icons-png.flaticon.com/512/3106/3106795.png" alt="Alarm">
                <p>Trigger a loud alarm to scare off thieves.</p>
            </div>
        </div>
    </div>

    <!-- Customer Reviews -->
    <div class="reviews-section">
        <h2>What Our Customers Say</h2>
        <div class="review-box">
            <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="User">
            <p>"Amazing! I recovered my stolen laptop using this system!"</p>
        </div>
        <div class="review-box">
            <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="User">
            <p>"A must-have for anyone worried about laptop security!"</p>
        </div>
    </div>

    <!-- Video Section -->
    <div class="video-section">
        <h2>Watch How It Works</h2><br>
       <video width="80%" height="300px" controls>
    <source src="/videos/la.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Laptop Antitheft System. All Rights Reserved.</p>
    </div>

</body>
</html>
