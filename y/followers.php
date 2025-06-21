<?php
session_start();
require 'connect.php';
include_once 'classes/user.php';
if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit;
} else {
    $userId = $_SESSION['userId'];
    $viewedUser = $_GET['user_id'];
}
# Return message
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    echo "<script>alert('$message'); window.location.href = 'followers.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Y - Share, like, and create posts with your friends!">
        <meta name="author" content="Valerie Fugere vafugere@gmail.com">
        <link rel="icon" href="favicon.ico">
        <title>Y - Why use X when you can use Y!</title>
	    <?php include_once('includes/stylesheets.php'); ?>
    </head>
    <body>
        <?php include_once('includes/header.php'); ?>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="mainprofile">
                        <?php User::displayUserInfo($con, $viewedUser); ?>
                    </div>
                    <div class="follow">
                        <div class="label">Following</div>
                        <div class="format-follow">
                            <?php User::usersYouFollow($con, $viewedUser); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h3>Followers</h3>
                    <div class="userpage">
                        <?php User::displayFollowers($con, $viewedUser); ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="suggested">
                        <div class="label">Suggested</div>
                        <?php User::suggestedUsers($con, $userId); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('includes/scripts.php'); ?>
    </body>
</html>