<?php //login.php
	include 'lib/utils.php';
	include 'lib/check.php';
	session_start();
	
	/*
	// If logged user, redirect to home
	if(isset($_SESSION['user'])){
		redirect("home.php");
	}
	*/
	httpsRedirect();
	
	// If there has been an error
	$errorText="";
	if (isset($_GET['msg'])){
		if($_GET['msg']=='error'){
			$errorText="Wrong username and/or password, try again";
		}else if($_GET['msg']=='timeout'){
			$errorText="The authentication time has expired: login to continue";			
		}
	}	
?>
<!DOCTYPE html>
<html>
<head>
<script src="js/utils.js"></script>
<title>Log In</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="./css/home.css">
</head>
<body onload="enableDisableSubmit()">
<?php loadHeader()?>
<div class="sidenav">
<div class="navcontent">
  <a href="index.php">Home</a>
  <a class="active" href="login.php">Log in</a>
  <a href="signup.php">Sign up</a>
</div>
</div>


<div class="main">
    <form method="POST" action="login_post.php">	
        <p class="errorMsg"><?php echo $errorText;?></p>		
			<label>Username
			<input 
						id="username" 
						type="email" 
						name="username"
						placeholder="something@domain.com" 
						required
						oninput="enableDisableSubmit()"
			></label><br><br>
			<label>Password
			<input 
						id="psw1"
						type="password" 
						name="psw"
						placeholder="***************"
						required
			></label><br>
			<input type="submit" id="submit" value="Login" class="button">			
    </form>
</div>

   
</body>
</html> 