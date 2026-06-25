<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: sign_in.php");
    exit;
}

// Validate PC name parameter
if (!isset($_GET['pc_name'])) {
    exit("Invalid request.");
}

$pcname = $_GET['pc_name'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laptop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch `c_id` from session
$c_id = $_SESSION['c_id'];

// Validate `c_id` and `pc_name` match
$sql = "SELECT c_id FROM client WHERE pc_name = ? AND c_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $pcname, $c_id);
$stmt->execute();
$stmt->bind_result($c_id_result);
$stmt->fetch();
$stmt->close();

if ($c_id_result !== $c_id) {
    exit("Invalid client or PC name.");
}

// Function to get captured images
function getCapturedImages($folder_path) {
    if (!is_dir($folder_path)) {
        return [];
    }

    // Retrieve images with valid extensions
    $images = glob($folder_path . "/*.{jpg,jpeg,png}", GLOB_BRACE);

    if ($images) {
        // Convert absolute paths to accessible URLs
        return array_map(function($image) {
            return str_replace("C:/xampp/htdocs/", "http://localhost/laptop_anti", $image);
        }, $images);
    }

    return [];
}

// Handle AJAX POST request for updating camera column
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_camera'])) {
    $sql = "UPDATE client SET camera = 1 WHERE pc_name = ? AND c_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $pcname, $c_id);

    if ($stmt->execute()) {
        // Construct the folder path
        $folder_path = "C:/xampp/htdocs/laptop_anti/captureding_" . $pcname . $c_id;
        $photos = getCapturedImages($folder_path);

        // Return JSON response
        header('Content-Type: application/json');
        if (!empty($photos)) {
            echo json_encode(["status" => "success", "message" => "Photos updated.", "images" => $photos]);
        } else {
            echo json_encode(["status" => "success", "message" => "No photos available.", "images" => []]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update camera status: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
    exit;
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
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
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
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
        }
        .buttons button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
        .buttons button:hover {
            background-color: #0056b3;
        }
        .image-container {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .image-container img {
            width: 150px;
            height: auto;
            margin: 5px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
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
            <button id="triggerBtn">Trigger</button>
        </div>
        <p id="responseMessage"></p>
        <div class="image-container" id="imageContainer"></div>
    </div>
</main>

<script>
document.getElementById("triggerBtn").addEventListener("click", function() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            document.getElementById("responseMessage").innerText = response.message;

            var imageContainer = document.getElementById("imageContainer");
            imageContainer.innerHTML = ""; // Clear previous images

            if (response.images.length > 0) {
                response.images.forEach(function(image) {
                    var imgElement = document.createElement("img");
                    imgElement.src = image; // The image URLs are already correct in PHP
                    imageContainer.appendChild(imgElement);
                });
            }
        }
    };

    xhr.send("update_camera=1");
});
</script>

</body>
</html>
