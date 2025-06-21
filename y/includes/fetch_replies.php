<?php
header('Content-Type: application/json');
session_start();
require '../connect.php';
include '../classes/user.php';
include '../classes/tweet.php';

$replyInfo = [];

if (isset($_GET['tweet_id'])) {
    $tweetId = $_GET['tweet_id'];
    $userId = $_SESSION['userId'];
    $replies = Tweet::getReplies($con, $tweetId);

    foreach ($replies as $reply) {
        $replyUser = User::getUserById($con, $reply->userId);
        $replyInfo[] = [
            'userId' => $replyUser->userId,
            'firstName' => $replyUser->firstName,
            'lastName' => $replyUser->lastName,
            'username' => $replyUser->username,
            'profilePic' => $replyUser->profilePic,
            'tweetText' => $reply->tweetText,
            'date' => $reply->getTimeString(),
        ];
    }

    $tweet = Tweet::getTweetById($con, $tweetId);
    $originalUser = User::getUserById($con, $tweet->userId);

    $response = [
        'username' => $originalUser->username,
        'userId' => $originalUser->userId,
        'replyInfo' => $replyInfo
    ];

    echo json_encode($response);
}