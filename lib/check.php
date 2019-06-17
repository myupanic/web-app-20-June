<?php

// SIGNING UP FUNCTIONS
// Check the correctness of the password in the registration form
function checkPsw($psw1, $psw2){
	return strlen($psw1)>=2 && strlen($psw1)<=255 && checkPswContent($psw1) && $psw1==$psw2;	
}

// Checks that the password content is correct (at least one lower case char and an upper case char or a digit)
function checkPswContent($psw){
	$pattern='/.*[a-z].*[A-Z0-9].*|.*[A-Z0-9].*[a-z].*/';
	return preg_match($pattern, $psw);	
}

// Check correctness of the username (a valid email address)
function checkEmail($username){
	return filter_var($username, FILTER_VALIDATE_EMAIL) && strlen($username)<=255 && htmlentities($username)==$username;
}

// Checks if the username is already in the db
function checkUsername($username){ 
	$conn=connectDB();	
	$res=null;
	$query = "SELECT * FROM user WHERE Username=? FOR UPDATE";
	if ($stmt = mysqli_prepare($conn, $query)) {
		mysqli_stmt_bind_param($stmt, "s", $username);
		if(!mysqli_stmt_execute($stmt)){
			return $res;
		}
		mysqli_stmt_store_result($stmt);
		$res=mysqli_stmt_num_rows($stmt);
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
	}
	else {
		return $res;
	}
	mysqli_close($conn);
	return $res;
	
}

// Inserts a new user
function insertUser($username, $psw){
    $conn=connectDB();
	$query="INSERT user(Username, Password) VALUES (?,?)";
	if($stmt = mysqli_prepare($conn, $query)){
		if(!$hash=password_hash($psw, PASSWORD_DEFAULT)){
			mysqli_close($conn);
			return false;
		}
		mysqli_stmt_bind_param($stmt, "ss", $username, $hash);
		if(!mysqli_stmt_execute($stmt)){
			mysqli_close($conn);
			return false;
		}
		mysqli_stmt_store_result($stmt);
		$res=mysqli_stmt_affected_rows($stmt)==1;
		mysqli_stmt_free_result($stmt);
		mysqli_close($conn);
		return $res;
	}else{
		mysqli_close($conn);
		return false;			
	}
}

function login($username, $psw){
	$conn=connectDB();
    $query = "SELECT Password FROM user WHERE Username=? FOR UPDATE";
	if ($stmt = mysqli_prepare($conn, $query)) {
		mysqli_stmt_bind_param($stmt, "s", $username);
		if(!mysqli_stmt_execute($stmt)){
			return false;
		}
		mysqli_stmt_bind_result($stmt, $stored);
		mysqli_stmt_fetch($stmt);	
		$res=password_verify($psw, $stored);
		if($res != 1){
			return false;
		}
		mysqli_stmt_close($stmt);
	}else {
		return false;
	}
	$_SESSION['265444_time'] = time();
	mysqli_close($conn);
	return true;
}

// Does the logout, destroying cookies and session
function logout(){
	if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600*24,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
	session_destroy();
}

function httpsRedirect(){
	if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS']==='off'){
		$redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	    header("Location: $redirect_url", true, 301);
	    exit();
	}	
}

?>