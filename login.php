<?php
include 'config.php';
include 'user.php';
$msg = "";
if(isset($_POST['username'])){ // If POST username is set
	if (login()==true){ // If username and password is correct
		header("Location: index.php");
		exit(); // Redirect and exit
	}
}
?>

<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="css/form.css">
    </head>

    <body>

        <!-- Begin Page Content -->
        <div id="container">
            <form action="login.php" method="post">
                <label for="loginmsg" style="color:hsla(0,100%,50%,0.5); font-family:"Helvetica Neue",Helvetica,sans-serif;"><?php echo $GLOBALS['msg'];?></label><br>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <div id="lower">
                    <input type="submit" value="Login">
                    <!--<a href="no" class="check">Forgot password</a>-->
                </div><!--/ lower-->
            </form>
        </div><!--/ container-->
        <!-- End Page Content -->
    </body>
</html>
