<?php
session_start();
if (isset($_SESSION['userId'])) {
    header('Location: index.php');
    exit;
}
# Return message
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    echo "<script>alert('$message'); window.location.href = 'signup.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Y Sign Up - Start sharing with friends!">
        <meta name="author" content="Valerie Fugere vafugere@gmail.com">
        <link rel="icon" href="favicon.ico">

        <title>Signup - Why use X when you can use Y!</title>

	    <?php include_once('includes/stylesheets.php'); ?>
    </head>
    <body>
        <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
			<a class="navbar-brand" href="index.php"><img src="images/y_logo.png" class="logo"></a>
    	</nav>
        <div class="container">
            <div class="row">
                <div class="main-login main-center signup-page">
                    <h2>Create Account</h2>
                    <form id="signup_form" method="post" action="process/signup_proc.php">
                        <div class="group">
                            <input type="text" id="first_name" name="first_name" class="form-input" placeholder="First Name"/>
                            <span id="error_first_name"></span>
                        </div>
                        <div class="group">
                            <input type="text" id="last_name" name="last_name" class="form-input" placeholder="Last Name"/>
                            <span id="error_last_name"></span>
                        </div>
                        <div class="group">
                            <input type="text" id="email" name="email" class="form-input" placeholder="Email"/>
                            <span id="error_email"></span>
                        </div>
                        <div class="group">
                            <input type="text" id="confirm_email" name="confirm_email" class="form-input" placeholder="Confirm Email"/>
                            <span id="error_confirm_email"></span>
                        </div>
                        <div class="group">
                            <input type="text" id="username" name="username" class="form-input" placeholder="Username"/>
                            <span id="error_username"></span>
                        </div>
                        <div class="group">
                            <input type="password" id="password" name="password" class="form-input" placeholder="Password"/>
                            <span id="error_password"></span>
                        </div>
                        <div class="group">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-input" placeholder="Confirm Password"/>
                            <span id="error_confirm_password"></span>
                        </div>
                        <div class="group">
                            <input type="submit" id="create" name="create" value="Create" class="btn-main"/>
                        </div>
                        <div class="group">
                            <a href="login.php" class="btn-accent">Already have an account?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="includes/jquery-3.3.1.min.js"></script>
        <script src="js/signup_validation.js"></script>
    </body>
</html>