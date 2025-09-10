<?php
session_start(); // Always start the session

// Remove all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: loginreg/logreg.php");
exit();
?>
