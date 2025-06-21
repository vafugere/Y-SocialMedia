<?php
session_start();
require '../connect.php';
require '../classes/tweet.php';

if (isset($_SESSION['userId'])) {
    if (isset($_GET['tweet_id'])) {
        $userId = $_SESSION['userId'];
        $tweetId = $_GET['tweet_id'];
        Tweet::insertRetweet($con, $userId, $tweetId);
        
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
