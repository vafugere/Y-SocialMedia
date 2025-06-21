<?php 
session_start();
require '../connect.php';
require '../classes/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    User::loginUser($con, $username, $password);
    
} else {
    $msg = 'An unexpected error has occured, please try again';
    header('Location: ../login.php?message=' . urlencode($msg));
    exit;
}



