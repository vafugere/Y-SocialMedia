<?php
session_start();
require '../connect.php';
require '../classes/user.php';

if (isset($_SESSION['userId'])) {
    if (isset($_POST['user_id'])) {
        $fromId = $_SESSION['userId'];
        $toId = $_POST['user_id'];
        User::toggleFollow($con, $fromId, $toId);
        
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