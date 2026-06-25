<?php
session_start();

if (isset($_GET['pc_name'])) {
    $pcname = $_GET['pc_name'];
} else {
    exit("PC name not provided!");
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laptop";

// Retrieve the client ID from the session
$c_id = isset($_SESSION['c_id']) ? $_SESSION['c_id'] : null;

if (!$c_id) {
    exit("Client ID (c_id) is missing.");
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1: Trigger screenshot capture when requested
if (isset($_GET['trigger_screenshot']) && $_GET['trigger_screenshot'] == 'true') {
    // Update the screenshot status in the database
    $sql = "UPDATE client SET screenshot = 1 WHERE c_id = ? AND pc_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $c_id, $pcname);
    if ($stmt->execute()) {
        echo "Screenshot triggered successfully.<br>";

        // Pass cl_id dynamically to the Python script
        $command = "python C:/xampp/htdocs/laptop_anti/screenshot_capture.py " . escapeshellarg($c_id);
        $output = shell_exec($command);
        if ($output === null) {
            echo "Error executing Python script.";
        } else {
            echo "Python script executed successfully: " . htmlspecialchars($output) . "<br>";
        }
    } else {
        echo "Error triggering screenshot: " . $stmt->error;
    }
    $stmt->close();
}

// Step 2: Update screenshot status after images are viewed
if (isset($_GET['update_screenshot']) && $_GET['update_screenshot'] == 'true') {
    $sql = "UPDATE client SET screenshot = 2 WHERE c_id = ? AND pc_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $c_id, $pcname);
    if ($stmt->execute()) {
        echo "Screenshot status updated to 2.<br>";
    } else {
        echo "Error updating screenshot status: " . $stmt->error;
    }
    $stmt->close();
}

// Fetching and sorting screenshots dynamically
$screenshots = [];
$folder_path = "C:/xampp/htdocs/laptop_anti/screenshots/$c_id"; // Directory of screenshots for the user
if (is_dir($folder_path)) {
    foreach (scandir($folder_path) as $file) {
        if ($file !== '.' && $file !== '..') {
            $screenshots[filemtime("$folder_path/$file")] = $file; // Use file modification time as key
        }
    }
    ksort($screenshots); // Sort by modification time (oldest first)
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

        .screenshot-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .screenshot-grid img {
            width: 100%;
            border: 2px solid #ddd;
            border-radius: 8px;
            box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .screenshot-grid img:hover {
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
<h1>Captured Screenshots for PC: <?php echo htmlspecialchars($pcname); ?></h1>

<div>
    <?php if (empty($screenshots)): ?>
        <p>No screenshots available.</p>
    <?php else: ?>
        <div class="screenshot-grid">
            <?php foreach ($screenshots as $timestamp => $screenshot): ?>
                <a href="/laptop_anti/screenshots/<?php echo $c_id . '/' . $screenshot; ?>" target="_blank">
                    <img src="/laptop_anti/screenshots/<?php echo $c_id . '/' . $screenshot; ?>" 
                         alt="Captured Screenshot" title="Taken on <?php echo date('Y-m-d H:i:s', $timestamp); ?>" loading="lazy" />
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="buttons">
<button onclick="triggerScreenshot()">📸 Trigger Screenshot</button>

        <button onclick="updateScreenshotStatus()">✅ Mark as Viewed</button>
    </div>

    <div class="back-button">
    <button onclick="window.history.back()">← Back</button>
</div>


<script>
    function triggerScreenshot() {
        window.location.href = 'test5.php?pc_name=<?php echo urlencode($pcname); ?>&trigger_screenshot=true';
    }

    function updateScreenshotStatus() {
        window.location.href = 'test5.php?pc_name=<?php echo urlencode($pcname); ?>&update_screenshot=true';
    }
</script>
</body>
</html>
