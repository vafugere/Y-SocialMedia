<?php
header('Content-Type: application/json');
require '../connect.php';
include '../classes/user.php';

$res = ['available' => false];

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $data = User::availableUsername($con, $username);
    $res['available'] = $data;
}
echo json_encode($res);


