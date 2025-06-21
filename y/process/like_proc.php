<?php
session_start();
require '../connect.php';
include '../classes/tweet.php';

if (isset($_SESSION['userId'])) {
    if (isset($_GET['tweet_id'])) {
        $tweetId = $_GET['tweet_id'];
        $userId = $_SESSION['userId'];
        Tweet::toggleLike($con, $tweetId, $userId);
        
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