<?php
require_once("../set.php");

$password = $_POST["password"];

$sql_login = "SELECT * FROM `junior3_login` WHERE `loginID` = '$password'";
//echo $sql_login;
$result_login = $conn->query($sql_login);
if ($result_login->num_rows > 0) {

    $_SESSION['login_check'] = "T";

    $row = $result_login->fetch_assoc();
    $_SESSION["status"] = $row["status"];
    $status = $_SESSION["status"];

    $_SESSION["classNum"] = $row["classNum"];
    $classNum = $_SESSION["classNum"];
    $_SESSION["seatNum"] = $row["seatNum"];
    $seatNum = $_SESSION["seatNum"];
    $_SESSION["name"] = $row["name"];
    $name = $_SESSION["name"];

    //echo $classNum . $seatNum . $name;

    echo "<script>window.location.href = 'result.php';</script>";
} else {
    echo "<script>alert('帳號密碼錯誤，請重新登入');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}
?>