<?php
session_start();
setcookie('user_email', '', time() - 3600, "/");
setcookie('user_role', '', time() - 3600, "/");
session_destroy();
header("Location: ../user/login.php");
exit();
