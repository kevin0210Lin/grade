<?php
require_once("../set.php");

if (isset($_SESSION['login_check'])) {
    if ($_SESSION['login_check'] != "T") {
        $conn->close();
        echo "<script>alert('您尚未登入');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    }
} else {
    $conn->close();
    echo "<script>alert('您尚未登入');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}

if (!isset($_GET['week_ID']) || !isset($_SESSION['manager'])) {
    $conn->close();
    echo "<script>alert('操作錯誤');</script>";
    echo "<script>window.location.href = 'manager.php';</script>";
    exit;
}



$weekID = $_GET['week_ID'];

if (isset($_GET['test_ID'])) {
    $testID = $_GET['test_ID'];
    $sql1 = "DELETE FROM `junior3_grade_set` WHERE `week_ID` = '$weekID' AND `test_ID` = '$testID';";
    $sql2 = "ALTER TABLE `$weekID`  DROP `$testID`,  DROP `per_$testID`;";

    if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
        echo "<script>alert('成績刪除成功');</script>";
        echo "<script>window.location.href = 'manager.php';</script>";
    } else {
        echo "<script>alert('刪除失敗: " . $conn->error . "');</script>";
        echo "<script>window.location.href = 'manager.php';</script>";
    }
} else {
    $sql1 = "DELETE FROM `junior3_grade_set` WHERE `week_ID` = '$weekID';";
    $sql2 = "DELETE FROM `junior3_week_set` WHERE `week_ID` = '$weekID';";
    $sql3 = "DROP TABLE `$weekID`;";

    if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE && $conn->query($sql3) === TRUE) {
        echo "<script>alert('刪除成功');</script>";
        echo "<script>window.location.href = 'manager.php';</script>";
    } else {
        echo "<script>alert('刪除失敗: " . $conn->error . "');</script>";
        echo "<script>window.location.href = 'manager.php';</script>";
    }
}








