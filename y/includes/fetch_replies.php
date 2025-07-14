<?php
header('Content-Type: application/json');
session_start();
require '../connect.php';
include '../classes/user.php';
include '../classes/post.php';

$replyInfo = [];

if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];
    $userId = $_SESSION['userId'];
    $replies = Post::getReplies($con, $postId);

    foreach ($replies as $reply) {
        $replyUser = User::getUserById($con, $reply->userId);
        $isLiked = Post::isLiked($con, $reply->postId, $userId);
        $replyInfo[] = [
            'userId' => $replyUser->userId,
            'displayName' => $replyUser->displayName,
            'username' => $replyUser->username,
            'profilePic' => $replyUser->profilePic,
            'postId' => $reply->postId,
            'postText' => $reply->postText,
            'date' => $reply->getTimeString(),
            'liked' => $isLiked,
        ];
    }
    $res = [
        'replyInfo' => $replyInfo
    ];
}
echo json_encode($res);
exit;