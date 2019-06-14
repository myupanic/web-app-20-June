<?php
  include 'lib/utils.php';
  include 'lib/check.php';
?>
<!DOCTYPE html>
<html>
<head>
<script src='js/utils.js'></script>
<title>Check In</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="./css/home.css">
</head>
<body>
<div class="header">
  <h2>Plane - Check in</h2>
</div>
<div class="sidenav">
  <navcontent>
  <a class="active" href="index.php">Home</a>
  <a href="login.php">Log in</a>
  <a href="signup.php">Sign up</a>
  </navcontent>
</div>

<div class="main">
    <div class="map"> 
        <?php
            loadMapForVisitor();
        ?>
    </div>
</div>
   
</body>
</html> 
