<?php 
session_start();
require '../connect.php';
include '../classes/user.php';

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    $file = $_FILES['profile_pic']['name'];
    $fileTmp = $_FILES['profile_pic']['tmp_name'];
    $dest = '../images/profilepics/' . $file;
    
    if (move_uploaded_file($fileTmp, $dest)) {
        User::uploadProfilePic($con, $file, $userId);
    }
    
} else {
    $msg = 'An unexpected error has occured, please sign in again';
    header('Location: ../login.php?message=' . urlencode($msg));
    exit;
}

