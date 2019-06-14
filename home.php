<?php
    include 'lib/utils.php';
    session_start();
    /*
    if(!isset($_SESSION['user'])){
        redirect("index.php");
    }
    */
    if(isset($_GET['msg'])){
		$errorText="An error occurred while purchasing seats, try again";
    }

?>
<!DOCTYPE html>
<html>
<head>
<script src="js/utils.js"></script>
<title>Check In</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="./css/home.css">
</head>
<body>
<div class="header">
  <h2>PinkPlane</h2>
</div>
<div class="sidenav">
  <navcontent>
  <a class="active" href="home.php">Home</a>
  <a href="logout.php">Log out</a>
  </navcontent>
</div>

<div class="main">
    <div class="map">
        <?php
            echo $errorText;
            loadMap();
        ?>
        <input type="submit" id="update" value="Update" class="button" onclick="reload()">			
        <input type="submit" id="buy" value="Buy" class="button" onclick="buy()">			
    </div>
</div>
   
</body>
</html> 
