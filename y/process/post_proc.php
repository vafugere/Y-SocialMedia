<?php 
session_start();
require '../connect.php';
require '../classes/post.php';

if (isset($_SESSION['userId'])) {
    if (!isset($_POST['message'])) Post::redirectFail();
    $userId = $_SESSION['userId'];
    $message = $_POST['message'];
    if (!Post::insertPost($con, $message, $userId)) Post::redirectFail();
    Post::redirectSuccess();
} else {
    $msg = 'An unexpected error has occured, please sign in again';
    header('Location: ../login.php?message=' . urlencode($msg));
    exit;
}

