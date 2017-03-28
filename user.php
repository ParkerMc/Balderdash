<?php
include 'utils.php';

function getUsername(){
	include 'config.php';
	$link = mysqli_connect($db["hostname"], $db["username"], $db["password"], $db["database"])
  	or die("<script>alert(\"Error connecting to database.\");</script>");
	$query = "SELECT username FROM users where token='" . $_COOKIE["token"] . "'";
    $result = mysqli_query($link, $query);
    mysqli_close($link);
    return mysqli_fetch_assoc($result)["username"];
}

function checkLogin(){
	include 'config.php';
	$link = mysqli_connect($db["hostname"], $db["username"], $db["password"], $db["database"])
  	or die("<script>alert(\"Error connecting to database.\");</script>");
	$query = "SELECT username FROM users where token='" . $_COOKIE["token"] . "'";
    $result = mysqli_query($link, $query);
    if(mysqli_num_rows($result) > 0){
		return;
    }
    mysqli_close($link);
    header("Location: login.php");
 	exit();
    
}

function login(){
  include 'config.php';
  //connect to db
  $link = mysqli_connect($db["hostname"], $db["username"], $db["password"], $db["database"])
  or die("<script>alert(\"Error connecting to database.\");</script>");
  $query = "SELECT uid, username, password FROM users where username='" . htmlspecialchars(strtolower($_POST["username"])) ."'";
  $result = mysqli_query($link, $query);
  if(mysqli_num_rows($result) > 0){
    $user =  mysqli_fetch_assoc($result);
    if(password_verify(htmlspecialchars($_POST["password"]), $user['password'])){
      $token = randomString(40);
      $query = "SELECT username FROM users where token='" . $token . "'";
      $result = mysqli_query($link, $query);
      while(mysqli_num_rows($result) > 0){
        $token = randomString(40);
        $query = "SELECT username FROM users where token='" . $token . "'";
        $result = mysqli_query($link, $query);
      }
      $query = "UPDATE users SET token = '" . $token . "', tokenExp = '" . date('m/d/Y h:i:s a', strtotime("+1 week")) . "',
      ip = '" . htmlspecialchars(getRealIpAddr()) . "' WHERE users.uid = ". $user["uid"];
      $result = mysqli_query($link, $query) or die("<script>alert(\"Error loging in.\");</script>");
      setcookie ("token", $token);
      mysqli_close($link);
      return true;
    }else{
      echo "<script>alert(\"Incorrect Password.\");</script>";
      mysqli_close($link);
    }
  }else{
    echo "<script>alert(\"Username does not exists.\");</script>";
    mysqli_close($link);
  }
  return false;
}
function createUser(){
	include 'config.php';
		$link = mysqli_connect($db["hostname"], $db["username"], $db["password"], $db["database"])
  or die("<script>alert(\"Error connecting to database.\");</script>");
   //check if user exists
  $query = "SELECT username from users where username='" . htmlspecialchars($_POST["username"]) . "'";
  $result = mysqli_query($link, $query);
  if(mysqli_num_rows($result) < 1){
  if($_POST['password']==$_POST['Cpassword']){
	  $query = "INSERT INTO `users` (`uid`, `username`, `password`, `ip`)
      VALUES (NULL, '" . htmlspecialchars(strtolower($_POST["username"])) . "', '" . password_hash(htmlspecialchars($_POST["password"]), 
      PASSWORD_DEFAULT) . "', '" . htmlspecialchars(getRealIpAddr()) . "')";
      $result = mysqli_query($link, $query) or die("<script>alert(\"Error createing user.\");</script>");
      $query = "SELECT uid from users where username='" . $_POST["username"] . "'";
      $result = mysqli_query($link, $query) or die("<script>alert(\"Error createing user.\");</script>");
      $user =  mysqli_fetch_assoc($result);
      mysqli_close($link);
      return login();
   }else{
     echo "<script>alert(\"passwords do not match.\");;</script>";
     mysqli_close($link);
   }
  }else{
  	echo "<script>alert(\"Username already exists.\");</script>";
  	mysqli_close($link);
  }
}
?>