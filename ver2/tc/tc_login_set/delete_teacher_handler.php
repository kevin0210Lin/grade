<?php
require_once("../../set.php");
header('Content-Type: application/json; charset=UTF-8');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode("error-CSRF Token 無效", JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 获取提交的表单数据
    $oldclassNumdelete = isset($_POST['oldclassNumdelete']) ? $_POST['oldclassNumdelete'] : '';
    $oldteachernamedelete = isset($_POST['oldteachernamedelete']) ? $_POST['oldteachernamedelete'] : '';
    $newteachername = isset($_POST['newteachername']) ? $_POST['newteachername'] : '';

    // 防止 SQL 注入
    $oldclassNumdelete = $conn->real_escape_string($oldclassNumdelete);
    $oldteachernamedelete = $conn->real_escape_string($oldteachernamedelete);
    $newteachername = $conn->real_escape_string($newteachername);


    $query1 = "DELETE FROM `junior3_login_tc` WHERE `name`='$oldteachernamedelete'";
    if ($conn->query($query1)) {
        if ($newteachername != "null") {
            $query2 = "UPDATE `junior3_login_tc` SET `classNum`='$oldclassNumdelete',`status`='teacher' WHERE `name`='$newteachername'";
        }
        if ($conn->query($query2)) {
            echo json_encode("資料已刪除", JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode("刪除教師失敗-error2", JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode("刪除教師失敗-error1", JSON_UNESCAPED_UNICODE);
    }

} else {
    // 如果请求不是 POST 方法，返回错误信息
    echo json_encode("error無效請求", JSON_UNESCAPED_UNICODE);
}
?>