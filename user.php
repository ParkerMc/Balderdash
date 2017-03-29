<?php
include 'utils.php';

function getUsername(){
	// Returns username as string //
	include 'config.php';
	$link = mysqli_connect($db["hostname"], $db["username"], $db["password"], $db["database"])
  	or die("<script>alert(\"Error connecting to database.\");</script>"); // Connect to database or show error
	$query = "SELECT username FROM users where token='" . htmlspecialchars($_COOKIE["token"]) . "'";
    $result = mysqli_query($link, $query); // Query database for user by token
    mysqli_close($link);
    return mysqli_fetch_assoc($result)["username"]; // Return the username
}

function checkLogin(){
	// If user is not logedin redirect to login page //
	include 'config.php';
	$link = mysqli_connect($db["hostname"], $db["username"], $db["password"], $db["database"])
  	or die("<script>alert(\"Error connecting to database.\");</script>"); // Connect to database or show error
	$query = "SELECT username FROM users where token='" . htmlspecialchars($_COOKIE["token"]) . "'";
    $result = mysqli_query($link, $query); // Query database for user by token
    if(mysqli_num_rows($result) > 0){ // If the token is valid and is linked to a username then return
		return;
    }
    mysqli_close($link);
    header("Location: login.php");
 	exit(); // Redirect to login page and exit
    
}

function login(){
	// Check username and password and if correct set token //
  	include 'config.php';
  	$link = mysqli_connect($db["hostname"], $db["username"], $db["password"], $db["database"])
  	or die("<script>alert(\"Error connecting to database.\");</script>"); // Connect to database or show error
  	$query = "SELECT uid, username, password FROM users where username='" . htmlspecialchars(strtolower($_POST["username"])) ."'";
  	$result = mysqli_query($link, $query); // Query database for user by username
  	if(mysqli_num_rows($result) > 0){ // If there is a row with the username
    	$user =  mysqli_fetch_assoc($result);
    	if(password_verify(htmlspecialchars($_POST["password"]), $user['password'])){ // If the passwords match
      		$token = randomString(40);
      		$query = "SELECT username FROM users where token='" . $token . "'";
      		$result = mysqli_query($link, $query); // Generate token and query database for any users with that token
      		while(mysqli_num_rows($result) > 0){ // While there is a user with the genrated token
        		$token = randomString(40);
        		$query = "SELECT username FROM users where token='" . $token . "'";
        		$result = mysqli_query($link, $query); // Generate a new token and query database for any users with that token
    		}
      		$query = "UPDATE users SET token = '" . $token . "', tokenExp = '" . date('m/d/Y h:i:s a', strtotime("+1 week")) . "',
      		ip = '" . htmlspecialchars(getRealIpAddr()) . "' WHERE users.uid = ". $user["uid"];
      		$result = mysqli_query($link, $query)
      		or die("<script>alert(\"Error loging in.\");</script>"); // Set token, token expiration, and ip in database or error
      		setcookie ("token", $token); // Set token cookie
      		mysqli_close($link);
      		return true;
    	}else{ // If password is incorrect
      		mysqli_close($link);
      		$GLOBALS['msg'] = "Incorrect Password."; // Return error message
    	}
  	}else{ // If user dose not exist
    	mysqli_close($link);
		$GLOBALS['msg'] = "User does not exist."; // Return error message
  	}
	return false;
}

function createUser(){
	// Create user then login as said user //
	include 'config.php';
	$GLOBALS['msg'] = "";
	$link = mysqli_connect($db["hostname"], $db["username"], $db["password"], $db["database"])
  	or die("<script>alert(\"Error connecting to database.\");</script>"); // Connect to database or show error
  	$query = "SELECT username from users where username='" . htmlspecialchars(strtolower($_POST["username"])) . "'";
  	$result = mysqli_query($link, $query); // Query database for user by username
  	if(mysqli_num_rows($result) < 1){ // If no user exist with username
  		if($_POST['password']==$_POST['Cpassword']){ // Is passwords match
	  		$query = "INSERT INTO `users` (`uid`, `username`, `password`, `ip`)
      		VALUES (NULL, '" . htmlspecialchars(strtolower($_POST["username"])) . "', '" . password_hash(htmlspecialchars($_POST["password"]), 
      		PASSWORD_DEFAULT) . "', '" . htmlspecialchars(getRealIpAddr()) . "')";
      		$result = mysqli_query($link, $query)
      		or die("<script>alert(\"Error createing user.\");</script>"); // Add user to database or error
      		return login(); // Try to login
   		}else{ // If passwords do not match
     		$GLOBALS['msg'] = "Passwords do not match."; // Set error message
     		mysqli_close($link);
   		}
  	}else{ // If username is already in database
  		$GLOBALS['msg'] = "Username already exists."; // Set error message
  		mysqli_close($link);
  	}
}
?>