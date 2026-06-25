<!DOCTYPE html>
<?php
session_start();

if (isset($_GET['pc_name'])) {
    $pcname = $_GET['pc_name'];
} else {
    exit;
}

if (isset($_GET['Latitude'])) {
    $Latitude = $_GET['Latitude'];
} else {
    exit;
}

if (isset($_GET['Longitude'])) {
    $longitude = $_GET['Longitude'];
} else {
    exit;
}
?>
<html>

   
   <head>
      <script src = "https://maps.googleapis.com/maps/api/js"></script>
      
      <script>
         function loadMap() {
			
            var mapOptions = {
               center:new google.maps.LatLng(<?php echo htmlspecialchars($Latitude); ?>,<?php echo htmlspecialchars($longitude); ?>),
               zoom:7
            }
				
            var map = new google.maps.Map(document.getElementById("sample"),mapOptions);
            
            var marker = new google.maps.Marker({
               position: new google.maps.LatLng(<?php echo htmlspecialchars($Latitude); ?>,<?php echo htmlspecialchars($longitude); ?>),
               map: map,
            });
         }
      </script>
      
   </head>
   
   <body onload = "loadMap()">
      <div id = "sample" style = "width:580px; height:400px;"></div>
   </body>
   
</html>

