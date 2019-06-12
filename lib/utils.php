<?php 
$dbhost  = 'localhost';    
$dbname  = 's265444'; 
$dbuser  = 'root';     
$dbpass  = '';     
$appname = "CheckIn"; 
$capacity = 4;

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
?>