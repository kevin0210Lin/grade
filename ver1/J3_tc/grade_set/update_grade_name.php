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
    $new_test_name = $_POST["new_test_name"];
    $week_ID = $_POST["week_ID"];
    $test_ID = $_POST["test_ID"];
    $now_time = date('Y/m/d H:i:s');

    $sql1 = "UPDATE `junior3_grade_set` SET `test_name` = '$new_test_name' ,`last_set_time` = '$now_time' WHERE `week_ID` = '$week_ID' AND `test_ID` = '$test_ID'";
    $sql2 = "UPDATE `junior3_week_set` SET `last_set_time` = '$now_time' WHERE `week_ID` = '$week_ID'";

    if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
        //echo "<script>alert('名稱更改成功');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('更改錯誤');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }


} else {
    echo "<script>alert('執行錯誤');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}




?>

<head>
    <?php
    require_once("../ad1.php");
    ?>
</head>

<body>
    <?php
    require_once("../ad2.php");
    ?>
</body>