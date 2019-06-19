<?php
	include 'lib/utils.php';
    include 'lib/check.php';
	session_start();
	
	// If logged user rediret
	if(isset($_SESSION['265444_user'])){
		redirect("home.php");
	}
		
	$username=$_POST['username'];
	$psw=$_POST['psw'];
	
	// Check if the password matches the username
	if(login($username, $psw)){
		$_SESSION=array();
		$_SESSION['265444_user']=$username;	
		$_SESSION['265444_time']=time();
    	redirect("home.php");
	}
	// Error
	redirect("login.php?msg=error");
?>