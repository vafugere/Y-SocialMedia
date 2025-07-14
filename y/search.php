<?php
session_start();
require 'connect.php';
include_once 'classes/user.php';
include_once 'classes/post.php';
include_once 'classes/search.php';
if (!isset($_SESSION['userId'])) {
  header('Location: login.php');
  exit;
} else {
  $userId = $_SESSION['userId'];
  if (isset($_GET['search_text'])) {
    $searchText = htmlspecialchars($_GET['search_text'], ENT_QUOTES);
  } else {
    $msg = 'An unexpected error has occured please try again';
    header('Location: index.php?message=' . urlencode($msg));
    exit;
  }
}
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    echo "<script>alert('$message'); window.location.href = 'search.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
      <meta charset="utf-8">
      <title>Y</title>
      <link rel="icon" href="favicon.png" type="image/png">
      <link href="css/style.css" rel="stylesheet">
      <link href="css/quill.css" rel="stylesheet">
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
                    <?php Search::displaySearchedUsers($con, $searchText); ?>
                    <?php Search::displaySearchedPosts($con, $searchText); ?>
                </div>
            </div>
            <div class="side">
                <?php User::suggestedUsers($con, $userId); ?>
            </div>
        </div>
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/posts.js"></script>
        <script src="js/home-icon.js"></script>
    </body>
</html>