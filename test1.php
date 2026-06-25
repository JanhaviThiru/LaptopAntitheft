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

$sql = "SELECT latitude, longitude,timestamp1 FROM client WHERE c_id='$c_id' AND pc_name='$pcname'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $latitude = $row['latitude'];
    $longitude = $row['longitude'];
    $timestamp1 = $row['timestamp1'];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['p_msg'])) {
    $p_msg = $_POST['p_msg'];

    // Insert the message into the client table
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
            background-color: #e9ecef; /* Light grey background */
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
       
 header {
    background: linear-gradient(to right, #000, #333); /* Black to dark grey */
    color: white;
    padding: 15px;
    text-align: center;
    font-size: 18px;
}

main {
    flex: 1;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.container {
    background-color: white;
    padding: 25px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    width: 90%;
    max-width: 800px;
    transition: transform 0.3s ease;
}

.container:hover {
    transform: translateY(-5px);
}

.buttons button {
    background-color: #444; /* Dark grey */
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    margin: 8px;
    transition: all 0.3s ease;
}

.buttons button:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

.image-container {
    margin-top: 20px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 10px;
    justify-content: center;
    align-items: center;
}

.image-container img {
    width: 100%;
    max-width: 180px;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.image-container img:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
}

#responseMessage {
    margin-top: 10px;
    color: green;
    font-weight: bold;
}
    </style>
</head>
<body>

<header>
    <h1>Laptop Anti-Theft System</h1>
    <p>Hello, <?php echo htmlspecialchars($_SESSION['c_name']); ?>. Welcome to your account.</p>
</header>

<main>
    <div class="container">
        <h2>Details for <?php echo htmlspecialchars($pcname); ?></h2>
        <p>Latitude: <?php echo htmlspecialchars($latitude); ?></p>
        <p>Longitude: <?php echo htmlspecialchars($longitude); ?></p>
        <p>Timestamp: <?php echo htmlspecialchars($timestamp1); ?></p>

        <div class="buttons">
    <a href="test2.php?pc_name=<?php echo htmlspecialchars($pcname); ?>"><button>Location</button></a>
    <a href="map.php?pc_name=<?php echo htmlspecialchars($pcname); ?>&Latitude=<?php echo htmlspecialchars($latitude); ?>&Longitude=<?php echo htmlspecialchars($longitude); ?>"><button>Show Map</button></a>
    
	
	
</div>
	
</main>


<script>
// Get the modal and elements
var modal = document.getElementById("popupModal");
var popupBtn = document.getElementById("popupBtn");
var closeBtn = document.getElementsByClassName("close")[0];

// Open the modal when the button is clicked
popupBtn.onclick = function() {
    modal.style.display = "flex";
}

// Close the modal when the user clicks on <span> (x)
closeBtn.onclick = function() {
    modal.style.display = "none";
}

// Close the modal if user clicks anywhere outside the modal
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

</body>
</html>
	