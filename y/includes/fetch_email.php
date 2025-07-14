<?php
header('Content-Type: application/json');
require '../connect.php';
include '../classes/user.php';

$res = ['available' => false];

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $data = User::availableEmail($con, $email);
    $res['available'] = $data;
}
echo json_encode($res);