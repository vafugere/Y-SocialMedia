<?php
session_start();
header('Content-Type: application/json');
require '../connect.php';
include '../classes/user.php';

$res = ['available' => false];

if (isset($_SESSION['userId'])) {
    if (isset($_GET['password'])) {
        $userId = $_SESSION['userId'];
        $password = $_GET['password'];
        $data = User::validateCurrentPassword($con, $userId, $password);
        $res['available'] = $data;
    }
    echo json_encode($res);
} else {
    $msg = 'An unexpected error has occured, please sign in again';
    header('Location: ../login.php?message=' . urlencode($msg));
    exit;
}