<?php
header('Content-Type: application/json');
require '../connect.php';
include '../classes/user.php';

$response = ['available' => false];

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $data = User::availableEmail($con, $email);
    $response['available'] = $data;
}
echo json_encode($response);