<?php
$conn = new mysqli("localhost", "root", "", "laptop");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "UPDATE user SET c_pass=?, reset_token=NULL, reset_expiry=NULL WHERE reset_token=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_password, $token);
    
    if ($stmt->execute()) {
        echo "Password reset successfully!";
    } else {
        echo "Invalid or expired token!";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form method="post">
            <input type="hidden" name="token" value="<?= $_GET['token'] ?>">
            <input type="password" name="password" placeholder="New Password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
