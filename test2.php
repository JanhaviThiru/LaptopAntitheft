<?php
session_start();


if (isset($_GET['pc_name'])) {
    $pcname = $_GET['pc_name'];
} else {
    exit;
	
}


// Query to get laptops and their locate values for the current client
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laptop";

$c_id = $_SESSION['c_id']; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$c_id = $_SESSION['c_id'];

$sql = "UPDATE client SET locate = '1' WHERE c_id = $c_id AND pc_name = '$pcname';";
//echo $sql ;
$result = $conn->query($sql);



$conn->close();

?>
<html>

<?php echo htmlspecialchars($pcname); ?>
<?php $Redir= "test1.php?pc_name=$pcname" ?>
<?php echo ($Redir)?>

 <script type="text/javascript">
            
			window.location.href ="<?php echo $Redir; ?>"
			
        </script>
</html>