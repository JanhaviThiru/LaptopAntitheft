<?php
session_start();

if (isset($_GET['pc_name'])) {
    $pcname = $_GET['pc_name'];
} else {
    exit;
}

// Database connection
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

$c_id = $_SESSION['c_id'];

$sql = "SELECT latitude, longitude, timestamp1, p_msg FROM client WHERE c_id='$c_id' AND pc_name='$pcname'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $latitude = $row['latitude'];
    $longitude = $row['longitude'];
    $timestamp1 = $row['timestamp1'];
    $p_msg = $row['p_msg'];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['p_msg'])) {
    $p_msg = $_POST['p_msg'];

    // Update the message in the client table
    $sql = "UPDATE client SET p_msg='$p_msg' WHERE c_id='$c_id' AND pc_name='$pcname'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Message sent successfully!');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptop Anti-Theft System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 28px;
        }

        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .card img {
            width: 80px;
            margin-bottom: 20px;
        }

        .card h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
        }

        .card p {
            font-size: 18px;
            color: #555;
            margin: 10px 0;
        }

        .buttons button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        .buttons button:hover {
            background-color: #0056b3;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .modal-content h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .modal textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
        }

        .modal button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .modal button:hover {
            background-color: #218838;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 0;
            right: 15px;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<header>
    <h1>Laptop Anti-Theft System</h1>
    <p>Hello, <?php echo htmlspecialchars($_SESSION['c_name']); ?>. Welcome to your account.</p>
</header>

<main>
    <div class="card">
        <img src="https://img.lovepik.com/element/45012/6089.png_860.png" alt="Popup" />
        <h3>Current Pop-Up Message:</h3>
        <p><?php echo htmlspecialchars($p_msg); ?></p>
        <button id="popupBtn">Send a New Pop-Up Message</button>
    </div>
</main>

<!-- Modal for sending pop-up message -->
<div id="popupModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Enter a New Message</h2>
        <form method="post" action="">
            <textarea name="p_msg" rows="4" placeholder="Enter your message" required></textarea><br><br>
            <button type="submit">Send Message</button>
        </form>
    </div>
</div>

<script>
// Modal handling
var modal = document.getElementById("popupModal");
var popupBtn = document.getElementById("popupBtn");
var closeBtn = document.getElementsByClassName("close")[0];

popupBtn.onclick = function() {
    modal.style.display = "flex";
}

closeBtn.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

</body>
</html>
