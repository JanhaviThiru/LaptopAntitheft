<?php
session_start(); // Start the session to get user details

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laptop";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection failed
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in user's client ID
if (!isset($_SESSION['c_id'])) {
    die("Error: No client ID found. Please log in.");
}

$c_id = $_SESSION['c_id']; // Fetch client ID from session

// Fetch laptop details for the logged-in user
$sql = "SELECT * FROM client WHERE c_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $c_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user has any registered laptops
if ($result->num_rows == 0) {
    die("No laptop details found.");
}

$row = $result->fetch_assoc(); // Fetch laptop details

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Laptop Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .container {
            width: 80%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>My Laptop Details</h2>
    <table>
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>
        <tr>
            <td><b>PC Name</b></td>
            <td><?php echo htmlspecialchars($row['pc_name']); ?></td>
        </tr>
        <tr>
            <td><b>IP Address</b></td>
            <td><?php echo htmlspecialchars($row['ip_add']); ?></td>
        </tr>
        <tr>
            <td><b>MAC Address</b></td>
            <td><?php echo htmlspecialchars($row['mac']); ?></td>
        </tr>
        <tr>
            <td><b>Operating System</b></td>
            <td><?php echo htmlspecialchars($row['system']); ?></td>
        </tr>
        <tr>
            <td><b>Processor</b></td>
            <td><?php echo htmlspecialchars($row['processor']); ?></td>
        </tr>
        <tr>
            <td><b>Physical CPU Cores</b></td>
            <td><?php echo htmlspecialchars($row['cpu_cores_physical']); ?></td>
        </tr>
        <tr>
            <td><b>Logical CPU Cores</b></td>
            <td><?php echo htmlspecialchars($row['cpu_cores_logical']); ?></td>
        </tr>
        <tr>
            <td><b>CPU Frequency</b></td>
            <td><?php echo htmlspecialchars($row['cpu_frequency']) . ' MHz'; ?></td>
        </tr>
        <tr>
            <td><b>Total RAM</b></td>
            <td><?php echo htmlspecialchars($row['total_memory']) . ' GB'; ?></td>
        </tr>
        <tr>
            <td><b>Used RAM</b></td>
            <td><?php echo htmlspecialchars($row['used_memory']) . ' GB'; ?></td>
        </tr>
        <tr>
            <td><b>Available RAM</b></td>
            <td><?php echo htmlspecialchars($row['available_memory']) . ' GB'; ?></td>
        </tr>
        <tr>
            <td><b>Storage (Total)</b></td>
            <td><?php echo htmlspecialchars($row['total_disk']) . ' GB'; ?></td>
        </tr>
        <tr>
            <td><b>Storage (Used)</b></td>
            <td><?php echo htmlspecialchars($row['used_disk']) . ' GB'; ?></td>
        </tr>
        <tr>
            <td><b>Storage (Free)</b></td>
            <td><?php echo htmlspecialchars($row['free_disk']) . ' GB'; ?></td>
        </tr>
        <tr>
            <td><b>Last Updated</b></td>
            <td><?php echo htmlspecialchars($row['last_updated']); ?></td>
        </tr>
    </table>
</div>

</body>
</html>
