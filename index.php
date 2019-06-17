<?php
  include 'lib/utils.php';
  include 'lib/check.php';

  if(isset($_SESSION['265444_user'])){
    redirect("home.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
<script src='js/jquery-3.3.1.min.js'></script>
<script src='js/utils.js'></script>
<title>Pink Airways</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="./css/home.css">
</head>
<body>
<?php loadHeader()?>
<div class="sidenav">
  <div class="navcontent">
  <a class="active" href="index.php">Home</a>
  <a href="login.php">Log in</a>
  <a href="signup.php">Sign up</a>
  </div>
</div>
<div class="main">
<div class="hello-user">
<p class="msg" id="must-log">You must be logged in to reserve a seat</p>
</div>
    <div class="map"> 
        <?php
            loadMapForVisitor();
        ?>
    </div>
</div>
   
</body>
</html> 
