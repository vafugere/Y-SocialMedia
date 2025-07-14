<?php
session_start();
require '../connect.php';
require '../classes/user.php';

if (isset($_SESSION['userId'])) {
    if (!isset($_POST['user_id'])) User::redirectFail();
    $fromId = $_SESSION['userId'];
    $toId = $_POST['user_id'];
    if (!User::toggleFollow($con, $fromId, $toId)) User::redirectFail();
    User::redirectSuccess();
} else {
    $msg = 'An unexpected error has occured, please sign in again';
    header('Location: ../login.php?message=' . urlencode($msg));
    exit;
}