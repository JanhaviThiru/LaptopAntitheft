<?php
// Base folder paths for camera images and screenshots
$base_camera_dir = "images/";
$base_screenshot_dir = "screenshots/";

// Ensure c_id and cl_id are provided
if (!isset($_POST['c_id']) || !isset($_POST['cl_id'])) {
    echo "Missing c_id or cl_id!";
    exit();
}

$c_id = $_POST['c_id'];  // Customer ID
$cl_id = $_POST['cl_id']; // Client ID

// Create specific directories for this client
$camera_dir = $base_camera_dir . "$c_id/$cl_id/";
$screenshot_dir = $base_screenshot_dir . "$c_id/$cl_id/";

// Create directories if they don't exist
if (!file_exists($camera_dir)) {
    mkdir($camera_dir, 0777, true);
}
if (!file_exists($screenshot_dir)) {
    mkdir($screenshot_dir, 0777, true);
}

// Ensure file is received
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filename = basename($_FILES["file"]["name"]);
    
    // Check if it's a screenshot or a camera image
    if (strpos($filename, 'screenshot') !== false) {
        $target_file = $screenshot_dir . $filename;  // Save screenshots here
    } else {
        $target_file = $camera_dir . $filename;  // Save camera images here
    }

    // Move uploaded file to the correct directory
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "File uploaded successfully: " . $target_file;
    } else {
        echo "File upload failed!";
    }
} else {
    echo "No file received!";
}
?>
