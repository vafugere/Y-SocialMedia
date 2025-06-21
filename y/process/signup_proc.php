<?php 
session_start();
require '../connect.php';
require '../classes/user.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['first_name']);
    $lname = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $user = new User(null, $fname, $lname, $username, $password, $email, null, null);
    User::createUser($con, $user);
    
} else {
    $msg = 'An unexpected error has occured, please try again';
    header('Location: ../signup.php?message=' . urlencode($msg));
    exit;
}
