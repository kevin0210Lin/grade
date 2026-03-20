<?php
require_once("../../set.php");

if (isset($_SESSION['login_check'])) {
    if ($_SESSION['login_check'] != "T") {
        echo "<script>alert('您尚未登入');</script>";
        echo "<script>window.location.href = '../index.php';</script>";
    }
} else {
    echo "<script>alert('您尚未登入');</script>";
    echo "<script>window.location.href = '../index.php';</script>";
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_week_name = $_POST["new_week_name"];
    $week_ID = $_POST["week_ID"];
    $now_time = date('Y/m/d H:i:s');

    $sql="UPDATE `junior3_week_set` SET `week_name` = '$new_week_name' ,`last_set_time` = '$now_time' WHERE `week_ID` = '$week_ID'";
    if($conn->query($sql) === TRUE){
        $_SESSION["week_set_choose"] = "";
        //echo "<script>alert('名稱更改成功');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }else{
        echo "<script>alert('更改錯誤');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }


} else {
    echo "<script>alert('執行錯誤');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}




?>