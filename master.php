<?php
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your MySQL password if set
$dbname = "laptop"; // Replace with your database name

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => 0, "message" => "Database connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the values from the POST request
    $name = trim($_POST["fname"] ?? '');
    $pass = trim($_POST["fpass"] ?? '');
    $pc_name = trim($_POST["fpc_name"] ?? '');

    // Validate inputs
    if (empty($name) || empty($pass) || empty($pc_name)) {
        echo json_encode(["status" => 0, "message" => "Gmail ID, Password, or PC Name is empty."]);
        exit;
    }

    // Step 1: Verify Gmail ID and password
    $sql = "SELECT u.c_mail, u.c_pass, c.pc_name, c.cl_id, c.screenshot, c.camera, c.locate, c.scream, c.datawipe, c.p_msg
            FROM user u
            JOIN client c ON u.c_id = c.c_id
            WHERE u.c_mail = ? AND c.pc_name = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["status" => 0, "message" => "Database error: " . $conn->error]);
        exit;
    }
    
    $stmt->bind_param("ss", $name, $pc_name);
    $stmt->execute();
    $result = $stmt->get_result();

    $password_match = false;
    $response_data = [];

    while ($row = $result->fetch_assoc()) {
        if (!$password_match && password_verify($pass, $row['c_pass'])) {
            $password_match = true;

            // Prepare the response data
            $response_data = [
                
				 $row['locate'],
				 $row['scream'],
				 $row['camera'],
                 $row['screenshot'],
				 $row['datawipe'],
				 $row['p_msg']
				 
                 
                 
                
                 
            ];
        }
    }

    $stmt->close();
    $conn->close();

    // Send response if authentication is successful, else send error message
    if ($password_match) {
        echo json_encode($response_data);
    } else {
        echo json_encode(["status" => 0, "message" => "Invalid credentials."]);
    }
}
?>
