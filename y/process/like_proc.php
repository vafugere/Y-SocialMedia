<?php
session_start();
header('Content-Type: application/json');
require '../connect.php';
include '../classes/post.php';

$res = ['success' => false];

if (isset($_SESSION['userId'])) {
    if (isset($_POST['post_id'])) {
        $userId = $_SESSION['userId'];
        $postId = $_POST['post_id'];
        Post::toggleLike($con, $postId, $userId);
        $liked = Post::isLiked($con, $postId, $userId);

        $res = [
            'success' => true,
            'liked' => $liked
        ];
    }
}
echo json_encode($res);
exit;
