<?php
session_start();

// Destroy all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page or home
header("Location: admin_login.php");
exit();
?>
