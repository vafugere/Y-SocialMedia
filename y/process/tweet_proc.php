<?php 
session_start();
require '../connect.php';
require '../classes/tweet.php';

if (isset($_SESSION['userId'])) {
    if (isset($_POST['my_tweet'])) {
        $userId = $_SESSION['userId'];
        $tweetText = $_POST['my_tweet'];
        Tweet::insertTweet($con, $tweetText, $userId);

    } else {
        $msg = 'An unexpected error has occured, please try again';
        header('Location: ../index.php?message=' . urlencode($msg));
        exit;
    }
    
} else {
    $msg = 'An unexpected error has occured, please sign in again';
    header('Location: ../login.php?message=' . urlencode($msg));
    exit;
}


