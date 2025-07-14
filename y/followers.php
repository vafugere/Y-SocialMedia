<?php
session_start();
require 'connect.php';
include_once 'classes/user.php';
if (!isset($_SESSION['userId'])) {
  header('Location: login.php');
  exit;
} else {
  $userId = $_SESSION['userId'];
  if (isset($_GET['user_id'])) {
    $viewedUser = ($_GET['user_id']) ? $_GET['user_id'] : $userId;
  } else {
    header('Location: index.php');
    exit;
  } 
}
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    echo "<script>alert('$message'); window.location.href = 'followers.php';</script>";
}
?>
<!DOCTYPE html>
<html leng="en">
    <head>
      <meta charset="utf-8">
      <title>Y</title>
      <link rel="icon" href="favicon.png" type="image/png">
      <link href="css/style.css" rel="stylesheet">
    </head>
    <body>
      <?php include_once('includes/header.php'); ?>
        <div class="main-container">
            <div class="side">
              <?php User::userInfo($con, $viewedUser); ?>
              <?php User::friends($con, $viewedUser); ?>
            </div>
            <div class="middle">
              <div class="post-scroll">
                <?php User::displayFollowers($con, $viewedUser); ?>
              </div>
            </div>
            <div class="side">
              <?php User::suggestedUsers($con, $userId); ?>
            </div>
        </div>
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/home-icon.js"></script>
    </body>
</html>