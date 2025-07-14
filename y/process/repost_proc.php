<?php
session_start();
require '../connect.php';
require '../classes/post.php';

if (isset($_SESSION['userId'])) {
    if (!isset($_POST['post_id'])) Post::redirectFail();
    $userId = $_SESSION['userId'];
    $postId = $_POST['post_id'];
    if (!Post::toggleRepost($con, $userId, $postId)) Post::redirectFail();
    Post::redirectSuccess();
} else {
    $msg = 'An unexpected error has occured, please sign in again';
    header('Location: ../login.php?message=' . urlencode($msg));
    exit;
}
