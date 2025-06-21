<?php
session_start();
require 'connect.php';
include_once 'classes/user.php';
include_once 'classes/tweet.php';
if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit;
} else {
    $userId = $_SESSION['userId'];
    $tweets = Tweet::getTweets($con, $userId);
}
# Return message
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    echo "<script>alert('$message'); window.location.href = 'index.php';</script>";
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
                        <?php User::displayUserInfo($con, $userId); ?>
                    </div>
                    <div class="follow">
                        <div class="label">Following</div>
                        <div class="format-follow">
                            <?php User::usersYouFollow($con, $userId); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <form id="tweet_form" action="process/tweet_proc.php" method="post">
                        <div class="group">
                            <textarea id="my_tweet" name="my_tweet" class="form-control" rows="2" placeholder="What's on your mind?"></textarea>
                        </div>
                        <div class="group">
                            <input type="submit" id="tweet_button" value="Send" class="btn-main">
                        </div>
                    </form>
                    <?php Tweet::displayTweets($con, $tweets) ?>
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


