<?php
session_start();
require 'connect.php';
include_once 'classes/user.php';
include_once 'classes/post.php';
if (!isset($_SESSION['userId'])) {
  header('Location: login.php');
  exit;
} else {
  $userId = $_SESSION['userId'];
  $posts = Post::getPosts($con, $userId);
}
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    echo "<script>alert('$message'); window.location.href = 'index.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
      <meta charset="utf-8">
      <title>Y</title>
      <link rel="icon" href="favicon.png" type="image/png">
      <link href="css/quill.snow.css" rel="stylesheet">
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

                <form id="post_form" method="POST" action="process/post_proc.php">
                  <div id="toolbar">
                    <span class="ql-formats">
                      <button class="ql-bold"></button>
                      <button class="ql-italic"></button>
                      <button class="ql-underline"></button>
                    </span>

                    <span class="ql-formats">
                      <button class="ql-list" value="ordered"></button>
                      <button class="ql-list" value="bullet"></button>
                    </span>

                    <span class="ql-formats">
                      <select class="ql-align">
                        <option selected></option>
                        <option value="center"></option>
                        <option value="right"></option>
                        <option value="justify"></option>
                      </select>
                    </span>

                    <span class="ql-formats">
                      <select class="ql-color"></select>
                    </span>

                    <span class="ql-formats">
                      <select class="ql-font">
                        <option selected>Sans Serif</option>
                        <option value="serif">Serif</option>
                        <option value="monospace">Monospace</option>
                        <option value="comic">Comic</option>
                        <option value="impact">Impact</option>
                        <option value="brush">Brush Script</option>
                      </select>
                    </span>

                    <span class="ql-formats">
                      <select class="ql-size">
                        <option value="small">A</option>
                        <option selected>A</option>
                        <option value="large">A</option>
                        <option value="huge">A</option>
                      </select>
                    </span>

                  </div>
                  <div id="editor"></div>
                  <input type="hidden" name="message" id="hiddenMessage">
                  <button type="submit" class="btn-post">Post</button>
                </form>

                <?php Post::displayPosts($con, $posts); ?>
              </div>
            </div>
            <div class="side">
              <?php User::suggestedUsers($con, $userId); ?>
            </div>
        </div>
        <script src="js/quill.min.js"></script>
        <script src="js/quill_editor.js"></script>
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/posts.js"></script>
        <script src="js/home-icon.js"></script>
    </body>
</html>