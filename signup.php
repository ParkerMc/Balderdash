<?php
include 'config.php';
include 'user.php';
if($_GET['t']=="bwb"){ // If token is correct
	if(isset($_POST['username'])){ // If POST username is set
		if (createUser()==true){ // If user is created and successfully logged in
			header("Location: index.php");
			exit(); // Redirect and exit
		}
	}
	?>
	<html>
  		<head>
	    	<title>Register</title>
    		<link rel="stylesheet" type="text/css" href="css/form.css">
  		</head>
  		<body>
    		<div id="container" style="height:380px;margin-top:-190px">
      			<form action="signup.php?t=<?php echo $_GET['t'];?>" method="post">
        			<p class="slotName">Register</p>
        			<br/>
        			<label for="loginmsg" style="color:hsla(0,100%,50%,0.5); font-family:"Helvetica Neue",Helvetica,sans-serif;"><?php  echo $GLOBALS['msg'];?></label><br>
        			<label form="username">Username:<span class="required">*</span></label>
        			<input type="text" id="username" name="username" required>
        			<label for="password">Password:<span class="required">*</span></label>
        			<input type="password" id="password" name="password" required>
        			<label for="password">Confirm Password:<span class="required">*</span></label>
        			<input type="password" id="Cpassword" name="Cpassword" required>
        			<div id="lower">
       					<input type="submit" value="Next">
        			</div>
      			</form>
    		</div>
  		</body>
	</html>
	<?php
}else{ // If token is wrong
    echo "Wrong token.";
	}?>