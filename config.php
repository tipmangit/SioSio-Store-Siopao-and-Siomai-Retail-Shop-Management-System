<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$con = mysqli_connect("localhost","root","","siosio_store") or die("Couldn't connect");
?>