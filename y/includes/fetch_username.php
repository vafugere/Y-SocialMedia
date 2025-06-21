<?php
header('Content-Type: application/json');
require '../connect.php';
include '../classes/user.php';

$response = ['available' => false];

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $data = User::availableUsername($con, $username);
    $response['available'] = $data;
}
echo json_encode($response);


