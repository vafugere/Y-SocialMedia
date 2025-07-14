<?php
session_start();
require '../connect.php';
include '../classes/user.php';

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['tmp_name'] !== '') {
        $file = $_FILES['profile_pic']['name'];
        $fileTmp = $_FILES['profile_pic']['tmp_name'];
        $dest = '../images/profilepics/' . $file;
    
        if (move_uploaded_file($fileTmp, $dest)) {
            if(!User::updateProfilePic($con, $userId, $file)) {
                User::redirectFail();
            }
        }
    }
    if (isset($_POST['name']) && trim($_POST['name']) !== '') {
        $name = trim($_POST['name']);
        if (!User::updateDisplayName($con, $userId, $name)) {
            User::redirectFail();
        }
    }
    if (isset($_POST['email']) && trim($_POST['email']) !== '') {
        $email = trim($_POST['email']);
        if (!User::updateEmail($con, $userId, $email)) {
            User::redirectFail();
        }
    }
    if (isset($_POST['password']) && trim($_POST['password']) !== '') {
        $password = trim($_POST['password']);
        if (!User::updatePassword($con, $userId, $password)) {
            User::redirectFail();
        }
    }
    $msg = 'Changes were saved!';
    header('Location: ../profile.php?message=' . urlencode($msg));
    exit;
} else {
    $msg = 'An unexpected error has occured, please sign in again';
    header('Location: ../login.php?message=' . urlencode($msg));
    exit;
}