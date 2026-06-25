<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); // Ensure JSON response
ob_clean(); // Clear any unexpected output

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laptop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Fetch `c_id` from session
$c_id = isset($_SESSION['c_id']) ? intval($_SESSION['c_id']) : 0;
if ($c_id <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid session data"]);
    exit;
}

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['camera_status'])) {
    $cameraStatus = intval($_POST['camera_status']);

    // Check if the user has a valid `cl_id`
    $checkClient = $conn->prepare("SELECT cl_id FROM client WHERE c_id = ?");
    $checkClient->bind_param("i", $c_id);
    $checkClient->execute();
    $checkClient->store_result();
    
    if ($checkClient->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Client not found"]);
        exit;
    }
    
    // Update camera status
    $updateSql = "UPDATE client SET camera_enabled = ? WHERE c_id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ii", $cameraStatus, $c_id);

    if ($updateStmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed: " . $conn->error]);
    }
    exit;
}

echo json_encode(["status" => "error", "message" => "Invalid request"]);
exit;
?>
