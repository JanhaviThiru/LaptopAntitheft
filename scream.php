<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: sign_in.php");
    exit;
}

// Fetch c_id from session
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

// Fetch pc_name and cl_id from the database using c_id
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

// Handle AJAX requests to update the scream status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['scream_status'])) {
    $scream_status = intval($_POST['scream_status']);
    $updateSql = "UPDATE client SET scream = ? WHERE c_id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ii", $scream_status, $c_id);
    if ($updateStmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit; // Ensure no further code is executed
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
        <h2>Scream Control for <?php echo htmlspecialchars($pcname); ?></h2>
        <div class="buttons">
            <button id="enableScreamBtn">Enable Scream</button>
            <button id="disableScreamBtn">Disable Scream</button>
        </div>
        <p id="responseMessage"></p>
    </div>
</main>

<script>
document.getElementById("enableScreamBtn").addEventListener("click", function() {
    updateScreamStatus(1); // Enable Scream
});

document.getElementById("disableScreamBtn").addEventListener("click", function() {
    updateScreamStatus(0); // Disable Scream
});

function updateScreamStatus(status) {
    const formData = new FormData();
    formData.append('scream_status', status);

    fetch("", {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            const message = status === 1 ? "Scream enabled successfully!" : "Scream disabled successfully!";
            document.getElementById("responseMessage").innerText = message;
        } else {
            document.getElementById("responseMessage").innerText = "Error updating scream status!";
        }
    })
    .catch(error => {
        document.getElementById("responseMessage").innerText = "Error: " + error.message;
    });
}
</script>

</body>
</html>
