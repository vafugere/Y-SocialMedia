<?php
session_start();
unset($_SERVER['PHP_AUTH_USER']);
session_unset();
session_destroy();
header('Location: login.php');
exit;
