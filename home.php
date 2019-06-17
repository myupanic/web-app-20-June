<?php
    include 'lib/check.php';
    include 'lib/utils.php';
    global $timeout;
    session_start();
    if(!isset($_SESSION['265444_user'])){
        redirect("index.php");
    }
    if(isset($_SESSION['265444_time'])){
        $time = $_SESSION['265444_time'];
        if($time < time() - $timeout){
            logout();
            redirect("login.php?msg=timeout");
        }
        else{
            $_SESSION['265444_time'] = time();
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
<script src='js/jquery-3.3.1.min.js'></script>
<script src="js/utils.js"></script>
<title>Home</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="./css/home.css">
</head>
<body>
<?php loadHeader()?>
<div class="sidenav">
<div class="navcontent">
  <a class="active" href="home.php">Home</a>
  <a href="logout.php">Log out</a>
</div>
</div>
<div class="main">
<div class="hello-user">
<?php $user = $_SESSION['265444_user']; echo "<p style=\"margin-bottom:0px\">Hello, $user</p>";?>
<p class="msg" id="buysucc">Purchase ended succesfully!</p>
<p class="msg" id="buyerr">Someone bought your seats</p>
<p class="msg" id="reservesucc">Seat has been reserved</p>
<p class="msg" id="reserveerr">Seat can't be reserved, someone bought it</p>
<p class="msg" id="removed">Reservation has been succesfully removed</p>
</div>
    <div class="map">
        <?php
            if(isset($_GET['msg'])){
                $error_type = $_GET['msg'];
                echo "<script>printMessage($error_type)</script>";
            }
            loadMap();
        ?>
        <input type="submit" id="update" value="Update" class="button" onclick="reload()">	
        <input type="submit" id="buy" value="Buy" class="button" onclick="buy()">			
    </div>
</div>
   
</body>
</html> 
