<?php
session_start();
require 'connect.php';
include_once 'classes/user.php';
if (!isset($_SESSION['userId'])) {
  header('Location: login.php');
  exit;
} else {
  $userId = $_SESSION['userId'];
}
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    echo "<script>alert('$message'); window.location.href = 'profile.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
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
              <?php User::userInfo($con, $userId); ?>
              <?php User::friends($con, $userId); ?>
            </div>
            <div class="middle">
                <div class="post-scroll">
                  <form id="edit_form" name="edit_form" method="POST" enctype="multipart/form-data" action="process/edit_proc.php">
                    <div class="background-padding">
                      <?php User::editProfilePreview($con, $userId); ?>
                      <input type="file" id="profile_pic" name="profile_pic" class="invisible">
                      <?php User::emailForm($con, $userId); ?>
                    </div>
                    <div class="background-padding">
                      <h2>Change Password</h2>
                      <input type="password" id="current_password" name="current_password" class="input-edit" placeholder="Current Password">
                      <span id="error_current_password"></span>
                      <input type="password" id="password" name="password" class="input-edit" placeholder="New Password">
                      <input type="password" id="confirm_password" name="confirm_password" class="input-edit" placeholder="Confirm New Password">
                      <span id="error_password"></span>
                    </div>
                    <input type="submit" class="btn-submit" value="Save">
                  </form>
                </div>
            </div>
            <div class="side">
              <?php User::suggestedUsers($con, $userId); ?>
            </div>
        </div>
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/edit_profile.js"></script>
        <script src="js/home-icon.js"></script>
    </body>
</html>