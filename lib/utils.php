<?php 
$dbhost  = 'localhost';    
$dbname  = 's265444'; 
$dbuser  = 's265444';     
$dbpass  = 'caviston';     
$rows = 10;
$columns = 6;
$timeout = 120;

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


function loadHeader(){
	?>
	<noscript>
    <p class="err-msg" style="display: block">Javascript must be enabled</p>
	</noscript>
	<link href='https://fonts.googleapis.com/css?family=Bad Script' rel='stylesheet'>
	<link href='https://fonts.googleapis.com/css?family=Acme' rel='stylesheet'>
	<div class="header">
	<h1>Pink Airways<img src="images/logo.png"></img></h1>
	</div>
	<script>
	testCookies()
	</script>
	<p class="err-msg" id="cookies-dis">Cookies must be enabled</p>
	<?php
}

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

function loadMap(){
	global $rows, $columns;
	$n_reserved = 0;
	$conn = connectDB();
	$query = "SELECT Status, Username FROM booking where SeatId=?";
	if ($stmt = mysqli_prepare($conn, $query)) {
		echo "<table>";
		for ($i = 0; $i < $rows; $i++) {
			echo "<tr>";
			for($j = 0; $j < $columns; $j++){
				if( ($j) == $columns / 2){
					echo "<td></td><td></td>";
				}
				$car = chr(65+$j);
				$stringId = $car.($i+1);
				mysqli_stmt_bind_param($stmt, "s", $stringId);
				if(!mysqli_stmt_execute($stmt)){
					return false;
				}
				mysqli_stmt_bind_result($stmt, $status, $user);
				mysqli_stmt_fetch($stmt);
				echo "<td class=\"seat\" onclick=\"checkSeat($stringId)\" id=\"$stringId\">";
				echo "$stringId";
				echo "</td>";
				if($status == null){
					$color = "lightgreen";
				}
				else if($status == 'P'){
					$color = "red";
				}
				else if($status == 'R' && $user != $_SESSION['265444_user']){
					$color = "orange";
				}
				else{
					$n_reserved++;
					$color = "yellow";
				}
				echo "<script>window.cellsToBook = $n_reserved; changeColor($stringId, \"$color\");</script>";
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

function loadMapForVisitor(){
	global $rows, $columns;
	$total_seats = $rows * $columns;
	$reserved_seats = 0;
	$purchased_seats = 0;
	$conn = connectDB();
	$query = "SELECT Status FROM booking WHERE SeatId=?";
	if ($stmt = mysqli_prepare($conn, $query)) {
		echo "<table>";
		for ($i = 0; $i < $rows; $i++) {
			echo "<tr>";
			for($j = 0; $j < $columns; $j++){
				if( ($j) == $columns / 2){
					echo "<td></td><td></td>";
				}
				$car = chr(65+$j);
				$stringId = $car.($i+1);
				mysqli_stmt_bind_param($stmt, "s", $stringId);
				if(!mysqli_stmt_execute($stmt)){
					return false;
				}
				mysqli_stmt_bind_result($stmt, $status);
				mysqli_stmt_fetch($stmt);
				$function = "errMessageVisitor()";
				if($status == null){
					$color = "lightgreen";
				}
				else if($status == 'P'){
					$color = "red";
					$function = "null";
					$purchased_seats++;
				}
				else if($status == 'R'){
					$color = "orange";
					$reserved_seats++;
				}
				echo "<td class=\"seat\" onclick=$function id=\"$stringId\">";
				echo "$stringId";
				echo "</td>";
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
	$free_seats = $total_seats - ($purchased_seats + $reserved_seats);
	echo "<table class=\"counter\">";
	echo "<tr>";
	echo "<th>Total seats</th>";
	echo "<th>Reserved seats</th>";
	echo "<th>Purchased seats</th>";
	echo "<th>Free seats</th>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>$total_seats</td>";
	echo "<td>$reserved_seats</td>";
	echo "<td>$purchased_seats</td>";
	echo "<td>$free_seats</td>";
	echo "</tr>";
	echo "</table>";
}

function httpsRedirect(){
	if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS']==='off'){
		$redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	    header("Location: $redirect_url", true, 301);
	    exit();
	}	
}
?>