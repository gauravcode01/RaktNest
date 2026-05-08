<?php
session_start();
$_SESSION = [];
session_destroy();
setcookie(session_name(), '', time() - 3600, '/'); // cookie bhi delete
header("Location: login.php");
exit();
?>
