<?php
    include 'lib/utils.php';
    if(!isset($_SESSION['user'])){
        redirect("index.html");
    }
?>
<!DOCTYPE html>
<html>
<head>
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
  <a href="login.php">Log in</a>
  <a href="signup.php">Sign up</a>
  </navcontent>
</div>

<div class="main">
    <div class="map">
        <?php
            $rows = 10;
            $columns = 6;
            echo "<table>";
            for ($i = 0; $i < $rows; $i++) {
                echo "<tr>";
                for($j = 0; $j < $columns; $j++){
                    $car = chr(65+$j);
                    $stringId = $car.($i+1);
                    echo "<td>";
                    //echo "<input type=\"checkbox\" id=\"$stringId\" />";
                    echo "<span>$stringId</span>";
                    echo "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        ?>
    </div>
</div>
   
</body>
</html> 
