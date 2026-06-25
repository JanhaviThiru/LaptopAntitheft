<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: sign_in.php");
    exit;
}

// Fetch `c_id` from session
$c_id = $_SESSION['c_id'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laptop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch `pc_name` and `cl_id` from the database using `c_id`
$sql = "SELECT pc_name, cl_id FROM client WHERE c_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $c_id);
$stmt->execute();
$stmt->bind_result($pcname, $cl_id);
$stmt->fetch();
$stmt->close();

if (!$pcname || !$cl_id) {
    exit("Invalid client or PC name.");
}

// Handle AJAX requests to update the camera status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['camera_status'])) {
    $cameraStatus = intval($_POST['camera_status']);
    $updateSql = "UPDATE client SET camera = ? WHERE c_id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ii", $cameraStatus, $c_id);
    if ($updateStmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit;
}

// Fetch only logged-in client's images
$directoryPath = "images/$c_id/$cl_id/";  

$images = [];
if (is_dir($directoryPath) && file_exists($directoryPath)) {
    $files = scandir($directoryPath);
    foreach ($files as $file) {
        if ($file != "." && $file != ".." && is_file($directoryPath . $file)) {
            $images[] = $directoryPath . $file;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptop Anti-Theft System</title>
	<style>
    body {
    font-family: 'Poppins', sans-serif;
    background-color: #eef2f3;
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
        <div class="buttons">
            <button id="enableCameraBtn">Enable Camera</button>
            <button id="disableCameraBtn">Disable Camera</button>
        </div>
        <p id="responseMessage"></p>

        <!-- Display images dynamically -->
        <div class="image-container">
            <?php if (!empty($images)): ?>
                <?php foreach ($images as $image): ?>
                   <a href="<?php echo htmlspecialchars($image); ?>" target="_blank">
    <img src="<?php echo htmlspecialchars($image); ?>" onclick="openImage('<?php echo htmlspecialchars($image); ?>')">
</a>

                <?php endforeach; ?>
            <?php else: ?>
                <p>No images found.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
document.getElementById("enableCameraBtn").addEventListener("click", function() {
    updateCameraStatus(1); // Enable camera
});

document.getElementById("disableCameraBtn").addEventListener("click", function() {
    updateCameraStatus(0); // Disable camera
});

function updateCameraStatus(status) {
    const formData = new FormData();
    formData.append('camera_status', status);

    fetch("", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const message = status === 1 ? "Camera enabled successfully!" : "Camera disabled successfully!";
            document.getElementById("responseMessage").innerText = message;
        } else {
            document.getElementById("responseMessage").innerText = "Error updating camera status!";
        }
    })
    .catch(error => {
        document.getElementById("responseMessage").innerText = "Error: " + error;
    });
}
</script>

<!-- Modal for Full-Size Image -->
<div id="imageModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); justify-content:center; align-items:center;">
    <img id="modalImage" style="max-width:90%; max-height:90%; border-radius:10px;">
</div>

<script>
function openImage(src) {
    document.getElementById("modalImage").src = src;
    document.getElementById("imageModal").style.display = "flex";
}

// Close modal when clicked outside the image
document.getElementById("imageModal").addEventListener("click", function() {
    this.style.display = "none";
});
</script>


</body>
</html>
