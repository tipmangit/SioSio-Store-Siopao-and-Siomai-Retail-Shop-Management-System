<?php
session_start();
session_destroy();
header("Location: homepage/index.php"); // or wherever you want to redirect
exit();
?>