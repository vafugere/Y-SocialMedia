<?php
session_start();
if (isset($_SESSION['userId'])) {
	header('Location: index.php');
	exit;
}
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    echo "<script>alert('$message'); window.location.href = 'login.php';</script>";
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
            <h1>Login</h1>
            <form name="login_form" class="flex-column" method="POST" action="process/login_proc.php">
                <div class="input-group">
                    <input type="text" name="username" class="input" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" class="input" placeholder="Password" required>
                </div>
                <input type="submit" class="btn-submit" value="Login">
            </form>
            <a href="signup.php" class="btn-submit">Create a new account</a>
        </div>
    </body>
</html>