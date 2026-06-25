<?php
header('Content-Type: application/json'); // Ensure response is always JSON

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laptop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Only accept POST requests
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate input
    $name = trim($_POST["fname"] ?? '');
    $pass = trim($_POST["fpass"] ?? '');
    $pc_name = trim($_POST["fpc_name"] ?? '');
    $ip_add = trim($_POST["fip_address"] ?? '');
    $mac = trim($_POST["fmac_address"] ?? '');
    $system = trim($_POST["fsystem"] ?? '');
    $node_name = trim($_POST["fnode_name"] ?? '');
    $release_version = trim($_POST["frelease_version"] ?? '');
    $machine = trim($_POST["fmachine"] ?? '');
    $processor = trim($_POST["fprocessor"] ?? '');
    $cpu_cores_physical = trim($_POST["fcpu_cores_physical"] ?? '');
    $cpu_cores_logical = trim($_POST["fcpu_cores_logical"] ?? '');
    $cpu_frequency = trim($_POST["fcpu_frequency"] ?? '');
    $total_memory = trim($_POST["ftotal_memory"] ?? '');
    $available_memory = trim($_POST["favailable_memory"] ?? '');
    $used_memory = trim($_POST["fused_memory"] ?? '');
    $memory_usage = trim($_POST["fmemory_usage"] ?? '');
    $total_disk = trim($_POST["ftotal_disk"] ?? '');
    $used_disk = trim($_POST["fused_disk"] ?? '');
    $free_disk = trim($_POST["ffree_disk"] ?? '');
    $disk_usage = trim($_POST["fdisk_usage"] ?? '');
    $last_updated = trim($_POST["flast_updated"] ?? '');

    // Check if essential fields are empty
    if (empty($name) || empty($pass) || empty($pc_name)) {
        echo json_encode(["error" => "Missing Gmail ID, Password, or PC Name"]);
        exit;
    }

    // Query to fetch user details
    $sql = "SELECT c_mail, c_pass, c_id FROM user WHERE c_mail = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            $client_id = $user_data['c_id'];

            // Verify password
            if (password_verify($pass, $user_data['c_pass'])) {

                // Check if the PC is already registered
                $check_pc_sql = "SELECT cl_id FROM client WHERE pc_name = ? AND c_id = ?";
                if ($check_stmt = $conn->prepare($check_pc_sql)) {
                    $check_stmt->bind_param("si", $pc_name, $client_id);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();

                    if ($check_result->num_rows > 0) {
                        // If PC exists, update details
                        $row = $check_result->fetch_assoc();
                        $cl_id = $row['cl_id'];

                        $update_sql = "UPDATE client SET ip_add = ?, mac = ?, system = ?, node_name = ?, release_version = ?, machine = ?, processor = ?, cpu_cores_physical = ?, cpu_cores_logical = ?, cpu_frequency = ?, total_memory = ?, available_memory = ?, used_memory = ?, memory_usage = ?, total_disk = ?, used_disk = ?, free_disk = ?, disk_usage = ?, last_updated = ? WHERE cl_id = ?";
                        if ($update_stmt = $conn->prepare($update_sql)) {
                            $update_stmt->bind_param("sssssssssssssssssssi", $ip_add, $mac, $system, $node_name, $release_version, $machine, $processor, $cpu_cores_physical, $cpu_cores_logical, $cpu_frequency, $total_memory, $available_memory, $used_memory, $memory_usage, $total_disk, $used_disk, $free_disk, $disk_usage, $last_updated, $cl_id);
                            if ($update_stmt->execute()) {
                                echo json_encode([
                                    "message" => "Details updated successfully",
                                    "status" => 1,
                                    "c_id" => $client_id,
                                    "cl_id" => $cl_id
                                ]);
                            } else {
                                echo json_encode(["error" => "Error updating PC details"]);
                            }
                        }
                    } else {
                        // Insert a new PC record
                        $insert_sql = "INSERT INTO client (pc_name, ip_add, mac, system, node_name, release_version, machine, processor, cpu_cores_physical, cpu_cores_logical, cpu_frequency, total_memory, available_memory, used_memory, memory_usage, total_disk, used_disk, free_disk, disk_usage, last_updated, c_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        if ($insert_stmt = $conn->prepare($insert_sql)) {
                            $insert_stmt->bind_param("ssssssssssssssssssssi", $pc_name, $ip_add, $mac, $system, $node_name, $release_version, $machine, $processor, $cpu_cores_physical, $cpu_cores_logical, $cpu_frequency, $total_memory, $available_memory, $used_memory, $memory_usage, $total_disk, $used_disk, $free_disk, $disk_usage, $last_updated, $client_id);
                            if ($insert_stmt->execute()) {
                                $cl_id = $conn->insert_id;
                                echo json_encode([
                                    "message" => "New PC registered and Details updated successfully",
                                    "status" => 1,
                                    "c_id" => $client_id,
                                    "cl_id" => $cl_id
                                ]);
                            } else {
                                echo json_encode(["error" => "Error inserting PC details"]);
                            }
                        }
                    }
                }
            } else {
                echo json_encode(["error" => "Invalid password"]);
            }
        } else {
            echo json_encode(["error" => "No account found for this Gmail ID"]);
        }
        $stmt->close();
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}

$conn->close();
?>
