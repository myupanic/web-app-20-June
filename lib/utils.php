<?php 
$dbhost  = 'localhost';    
$dbname  = 's265444'; 
$dbuser  = 'root';     
$dbpass  = '';     
$appname = "CheckIn"; 

function redirect($location){
	header('Location: '.$location);
	exit();	
}

function connectDB(){
	global $dbhost, $dbname, $dbuser, $dbpass;	
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
	if (!$conn) {
		die('Connect error ('. mysqli_connect_errno() . ') '. mysqli_connect_error());
	}
	return $conn;
}

function loadMap($rows, $columns){
	$conn = connectDB();
	$query = "SELECT status, username FROM BOOKING where seatid=?";
	if ($stmt = mysqli_prepare($conn, $query)) {
		echo "<table>";
		for ($i = 0; $i < $rows; $i++) {
			echo "<tr>";
			for($j = 0; $j < $columns; $j++){
				$car = chr(65+$j);
				$stringId = $car.($i+1);
				mysqli_stmt_bind_param($stmt, "s", $stringId);
				if(!mysqli_stmt_execute($stmt)){
					return false;
				}
				mysqli_stmt_bind_result($stmt, $status, $user);
				mysqli_stmt_fetch($stmt);
				echo "<td onclick=\"checkSeat($stringId)\" id=\"$stringId\">";
				echo "$stringId";
				echo "</td>";
				if($status == null){
					$color = "lightgreen";
				}
				else if($status == 'P'){
					$color = "red";
				}
				else if($status == 'R' && $user != $_SESSION['user']){
					$color = "orange";
				}
				else{
					$color = "yellow";
				}
				echo "<script>changeColor($stringId, \"$color\")</script>";
			}
			echo "</tr>";
		}
		echo "</table>";
		mysqli_stmt_close($stmt);
	}
	else {
		return false;
	}
	mysqli_close($conn);
}
?>