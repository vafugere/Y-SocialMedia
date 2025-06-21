<?php
session_start();
if (isset($_SESSION['userId'])) {
	header('Location: index.php');
	exit;
}
# Return message
if (isset($_GET['message'])) {
	$message = $_GET['message'];
	echo "<script>alert('$message'); window.location.href = 'login.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	<meta name="description" content="Y Login - Add friends, like and create posts!">
    	<meta name="author" content="Valerie Fugere vafugere@gmail.com">
    	<link rel="icon" href="favicon.ico">

    	<title>Login - Why use X when you can use Y!</title>

		<?php include_once('includes/stylesheets.php'); ?>
  	</head>
  	<body>
    	<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
			<a class="navbar-brand" href="index.php"><img src="images/y_logo.png" class="logo"></a>
    	</nav>

		<br><br>
		<div class="login-page">
			<div class="column">
				<div class="main-center  mainprofile">
					<h1>Y Login</h1>
					<p class="lead">Y Social Media - Create posts and follow your friends!<br></p>
				</div>
				<div class="main-center  mainprofile">
					<h1>Don't have a Y Account?</h1>
					<p class="lead"><a class="bold" href="signup.php">Click Here</a> to join now!<br></p>
				</div>
			</div>
			<div class="column">
				<div class="main-center login">
					<h2>Login</h2>
					<div class="group-login">
						<form id="login_form" method="post" action="process/login_proc.php">
							<div class="group">
								<input type="text" class="form-input" name="username" id="username" placeholder="Username" required/>
							</div>
							<div class="group">
								<input type="password" class="form-input" name="password" id="password" placeholder="Password" required/>
							</div>
							<div class="group">
								<input type="submit" name="login" id="login" value="Login" class="btn-main"/>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>