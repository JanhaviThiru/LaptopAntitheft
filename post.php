<?php
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your MySQL password if set
$dbname = "laptop"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the POST request
    $name = trim(htmlspecialchars($_POST["fname"] ?? ''));
    $pass = trim(htmlspecialchars($_POST["fpass"] ?? ''));
    $pc_name = trim(htmlspecialchars($_POST["fpc_name"] ?? ''));
    $latitude = trim(htmlspecialchars($_POST["latitude"] ?? ''));
    $longitude = trim(htmlspecialchars($_POST["longitude"] ?? ''));

    // Check if the necessary fields are provided
    if (empty($pc_name) || empty($latitude) || empty($longitude)) {
        echo "PC Name, Latitude, or Longitude is empty";
    } else {
        // Step 1: Check if the PC Name exists in the database
        $sql = "SELECT c.cl_id, c.locate FROM client c WHERE c.pc_name = '$pc_name'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Step 2: Fetch the client ID and locate status
            $row = $result->fetch_assoc();
            $cl_id = $row['cl_id'];
            $locate_status = $row['locate'];

            // Step 3: Update the latitude and longitude if locate status is 1
            if (!empty($latitude) && !empty($longitude) && $locate_status == 1) {
                $update_sql = "UPDATE client SET latitude = '$latitude', longitude = '$longitude', locate = 2 WHERE cl_id = '$cl_id'";
                if ($conn->query($update_sql) === TRUE) {
                    echo "Location updated successfully!";
                    
                    // Store location update message
                    $message_sql = "INSERT INTO client (c_id) VALUES ('$cl_id')";
                    if ($conn->query($message_sql) !== TRUE) {
                        echo "Error storing message: " . $conn->error;
                    }
                } else {
                    echo "Error updating location: " . $conn->error;
                }
            } else {
                echo "Location cannot be updated because the 'locate' status is not 1.";
            }
        } else {
            echo "No account found with the provided PC Name.";
        }
    }
}

// Close the connection
$conn->close();
?>
