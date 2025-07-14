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
        <title>Y</title>
        <link rel="icon" href="favicon.png" type="image/png">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>
        <div class="header-container">
            <div>
                <a href="index.php"><img src="images/logo.png" width="50" height="50"></a>
            </div>
        </div>
        <div class="form-container flex-column">
            <h1>Create Account</h1>
            <form id="signup_form" class="flex-column" method="POST" action="process/signup_proc.php">
                <div class="input-group">
                    <input type="text" id="first_name" name="first_name" class="input" placeholder="First Name">
                    <span id="error_first_name"></span>
                </div>
                <div class="input-group">
                    <input type="text" id="last_name" name="last_name" class="input" placeholder="Last Name">
                    <span id="error_last_name"></span>
                </div>
                <div class="input-group">
                    <input type="text" id="email" name="email" class="input" placeholder="Email">
                    <span id="error_email"></span>
                </div>
                <div class="input-group">
                    <input type="text" id="confirm_email" name="confirm_email" class="input" placeholder="Confirm Email">
                    <span id="error_confirm_email"></span>
                </div>
                <div class="input-group">
                    <input type="text" id="username" name="username" class="input" placeholder="Username">
                    <span id="error_username"></span>
                </div>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="input" placeholder="Password">
                    <span id="error_password"></span>
                </div>
                <div class="input-group">
                    <input type="password" id="confirm_password" name="confirm_password" class="input" placeholder="Confirm Password">
                    <span id="error_confirm_password"></span>
                </div>
                <input type="submit" class="btn-submit" value="Create">
            </form>
            <a href="login.php" class="btn-submit">Already have an account?</a>
        </div>
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/signup_validation.js"></script>
    </body>
</html>