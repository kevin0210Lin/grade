<?php
require_once("../set.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $password = $_POST["password"];
    $_SESSION["login_check"] = "T";
} else {
    echo "<script>window.location.href = 'index.php';</script>";
}

if ($id == 'manager' && $password == 'liohojunior3') {
    $_SESSION["name"] = '系統管理員2';
    $_SESSION["manager"] = 'super';
    header('Location: manager.php');
    exit();
}


$sql_login = "SELECT * FROM `junior3_login_tc` WHERE `id` = '$id' AND `password` = '$password'";
$result_login = $conn->query($sql_login);
if ($result_login->num_rows > 0) {
    $row = $result_login->fetch_assoc();

    $_SESSION["classNum"] = $row["classNum"];
    //$classNum = $_SESSION["classNum"];
    $_SESSION["name"] = $row["name"];
    //$name = $_SESSION["name"];
    $_SESSION["status"] = $row["status"];
    //$status = $_SESSION["status"];
    //echo $classNum.$name;

    if ($password == "123456") {
        $_SESSION["old_id"] = $id;
        $_SESSION["old_pass"] = $password;
        echo "<script>alert('第一次登入，請修改密碼');</script>";
        echo "<script>window.location.href = 'tc_login.php';</script>";
    }

    //echo "<script>alert('本網頁將於2024/9/13 16:30 至 2024/9/15 23:59 進行停機維護作業，造成不便請見諒');</script>";
    echo "<script>window.location.href = 'result.php';</script>";
} else {
    echo "<script>alert('帳號密碼錯誤，請重新登入');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}
?>