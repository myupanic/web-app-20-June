<?php //signUp.php
	include 'lib/utils.php';
	include 'lib/check.php';
	session_start();
	
	// If logged user, redirect to home
	if(isset($_SESSION['265444_user'])){
		redirect("home.php");
	}
	httpsRedirect();
	
	$errorTxt="";
	if (isset($_GET['msg']) && $_GET['msg']=='error'){
		$errorTxt="Error during signing up, try again";
	}
    
?>
<!DOCTYPE html>
<html>
<head>
<script src='js/jquery-3.3.1.min.js'></script>
<script src="js/utils.js"></script>
<title>Sign up</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="./css/home.css">
</head>
<body onload="signUpCheckPswConstraints()">
<?php loadHeader()?>
<div class="sidenav">
<div class="navcontent">
 	<a href="index.php">Home</a>
  	<a href="login.php">Log in</a>
	<a class="active" href="signup.php">Sign up</a>
</div>
</div>
<div class="main">
    <form method="POST" action="signup_post.php">	
	<p><?php echo $errorTxt;?></p>				
			<label>Insert email</label>
			<input id="username" type="email" name="username" required oninput="signUpCheckPswConstraints()">
			<p id="notvalid" class="message">Not valid email address</p>
			<br>
			<label>Insert a password</label>
			<input id="psw1" type="password" name="psw1" required oninput="signUpCheckPswConstraints()"><br><br>
      		<label>Repeat the password</label>
			<input id="psw2" type="password" name="psw2" required oninput="signUpCheckPswConstraints()"><br>
			<p id="constr" class="message">Password must contain at least one lower-case alphabetic character,<br> and at least one
					other character that is either alphabetical uppercase or numeric. </p>
			<p id="pswmatch" class="message">The two passwords do not match.</p>
			<input type="submit" id="submit" value="Sign up" class="button">			
    </form>
</div>

   
</body>
</html> 