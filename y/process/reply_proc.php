<?php
session_start();
require '../connect.php';
require '../classes/tweet.php';

if (isset($_SESSION['userId'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = $_SESSION['userId'];
        $replyId = $_POST['tweet_id'];
        $tweetText = $_POST['reply_text'];
        Tweet::insertReply($con, $tweetText, $userId, $replyId);

    } else {
    $msg = 'An unexpected error has occured, please try again';
    header('Location: ../login.php?message=' . urlencode($msg));
    exit;
    }

} else {
    $msg = 'An unexpected error has occured, please sign in again';
    header('Location: ../login.php?message=' . urlencode($msg));
    exit;
}