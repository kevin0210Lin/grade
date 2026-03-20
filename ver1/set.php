<?php
ini_set('display_errors', 1);
session_start();
$servername = "localhost";
$username = "linonlin_113j3";
$password = "+_)(*&^%$#@!";
$dbname = "linonlin_113j3";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
date_default_timezone_set('Asia/Taipei');
?>
