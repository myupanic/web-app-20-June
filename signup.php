<?php //signUp.php
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
	if (isset($_GET['msg']) && $_GET['msg']=='error'){
		$errorText="Error during signing up, try again";
	}else{
		$errorText="";
    }
    
    echo $errorText;

?>
<!DOCTYPE html>
<html>
<head>
<script src="js/utils.js"></script>
<title>Sign up</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="./css/home.css">
</head>
<body onload="signUpCheckPswConstraints()">
<?php loadHeader()?>
<div class="sidenav">
  <navcontent>
  <a href="index.php">Home</a>
  <a href="login.php">Log in</a>
  <a class="active" href="signup.php">Sign up</a>
  </navcontent>
</div>

<div class="main">
    <form method="POST" action="signup_post.php">			
			<label>Insert email     <input 
									id="username" 
									type="email" 
									name="username"
									placeholder="something@domain.com" 
									required
									oninput="signUpCheckPswConstraints()"></label><br><br>
			<label>Insert a password     <input 
									id="psw1"
									type="password" 
									name="psw1"
									placeholder="***************"
									required
									oninput="signUpCheckPswConstraints()"></label><br><br>
      <label>Repeat the password     <input 
									id="psw2"
									type="password" 
									name="psw2"
									placeholder="***************"
									required
									oninput="signUpCheckPswConstraints()"></label><br>
			<div class="message">
			<p id="message">Password must contain at least one lower-case alphabetic character,<br> and at least one
							other character that is either alphabetical uppercase or numeric. </p>
			</div>
			<input type="submit" id="submit" value="Sign up" class="button">			
    </form>
</div>

   
</body>
</html> 