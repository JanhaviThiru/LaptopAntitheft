<?php
session_start();

if (!isset($_GET['pc_name'])) {
    exit("PC name not provided!");
}

$pcname = $_GET['pc_name'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laptop";

$c_id = $_SESSION['c_id']; // Retrieve the client ID from the session

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update camera status to 1 when triggered
if (isset($_GET['trigger_camera']) && $_GET['trigger_camera'] == 'true') {
    $sql = "UPDATE client SET camera = 1 WHERE c_id = ? AND pc_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $c_id, $pcname);
    if ($stmt->execute()) {
        echo "Camera triggered successfully.";
    } else {
        echo "Error triggering camera: " . $stmt->error;
    }
    $stmt->close();
}

// Update camera status to 2 after images are viewed
if (isset($_GET['update_camera']) && $_GET['update_camera'] == 'true') {
    $sql = "UPDATE client SET camera = 2 WHERE c_id = ? AND pc_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $c_id, $pcname);
    if ($stmt->execute()) {
        echo "Camera status updated to 2.";
    } else {
        echo "Error updating camera status: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch and sort images by date to merge previously taken images first
$images = [];
$folder_path = "C:/xampp/htdocs/laptop_anti/capturedimg/$c_id";
if (is_dir($folder_path)) {
    $image_files = array_diff(scandir($folder_path, SCANDIR_SORT_ASCENDING), array('.', '..')); // Sort by ascending order
    
    // Prepare images with timestamps
    foreach ($image_files as $image) {
        $image_path = $folder_path . '/' . $image;
        $timestamp = filemtime($image_path); // Get the last modified timestamp of the image
        $images[$timestamp] = $image; // Store images by timestamp
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
            font-family: Arial, sans-serif;
            background-color: #f9fafc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: #333;
            font-size: 24px;
        }

        .container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .image-card {
            width: 100%;
            border: 2px solid #ddd;
            border-radius: 10px; /* Slightly curved corners */
            box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .image-card img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 8px; /* Curved corners for the image itself */
        }

        .image-card:hover {
            transform: scale(1.05);
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
        }

        .buttons {
            text-align: center;
            margin: 20px 0;
        }

        .buttons button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            margin: 10px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .buttons button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .back-button {
            text-align: center;
            margin: 30px auto;
        }

        .back-button a {
            display: inline-block;
            text-decoration: none;
            color: #007bff;
            font-size: 18px;
            padding: 10px 20px;
            border: 2px solid #007bff;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .back-button a:hover {
            background-color: #007bff;
            color: white;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 20px;
            }

            .buttons button {
                font-size: 14px;
                padding: 10px 15px;
            }

            .back-button a {
                font-size: 16px;
            }
        }

    </style>
</head>
<body>
    <h1>Captured Images for PC: <?php echo htmlspecialchars($pcname); ?></h1>

    <div class="container">
        <?php if (empty($images)): ?>
            <p>No images available.</p>
        <?php else: ?>
            <?php foreach ($images as $timestamp => $image): ?>
                <div class="image-card">
                    <a href="/laptop_anti/capturedimg/<?php echo $c_id . '/' . $image; ?>" target="_blank">
                        <img src="/laptop_anti/capturedimg/<?php echo $c_id . '/' . $image; ?>" alt="Captured Image" 
                             title="Taken on <?php echo date('Y-m-d H:i:s', $timestamp); ?>" loading="lazy" />
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="buttons">
        <button onclick="triggerCamera()">📸 Trigger Camera</button>
        <button onclick="updateCameraStatus()">✅ Mark as Viewed</button>
    </div>

    <div class="back-button">
    <button onclick="window.history.back()">← Back</button>
</div>


    <script>
        function triggerCamera() {
            window.location.href = 'test4.php?pc_name=<?php echo urlencode($pcname); ?>&trigger_camera=true';
        }

        function updateCameraStatus() {
            window.location.href = 'test4.php?pc_name=<?php echo urlencode($pcname); ?>&update_camera=true';
        }
    </script>
</body>
</html>
