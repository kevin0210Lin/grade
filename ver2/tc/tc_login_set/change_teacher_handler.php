<?php
require_once("../../set.php");
header('Content-Type: application/json; charset=UTF-8');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode("error-CSRF Token 無效", JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 获取提交的表单数据
    $oldclassNum = isset($_POST['oldclassNum']) ? $_POST['oldclassNum'] : '';
    $oldteachername = isset($_POST['oldteachername']) ? $_POST['oldteachername'] : '';
    $newteachername = isset($_POST['newteachername']) ? $_POST['newteachername'] : '';

    // 防止 SQL 注入
    $oldclassNum = $conn->real_escape_string($oldclassNum);
    $oldteachername = $conn->real_escape_string($oldteachername);
    $newteachername = $conn->real_escape_string($newteachername);

    // insert
    $query1 = "UPDATE `junior3_login_tc` SET `classNum`='',`status`='subjectteacher' WHERE `name`='$oldteachername'";
    if ($conn->query($query1)) {
        $query2 = "UPDATE `junior3_login_tc` SET `classNum`='$oldclassNum',`status`='teacher' WHERE `name`='$newteachername'";
        if ($conn->query($query2)) {
            echo json_encode("已將'$oldclassNum'班級導師更換為「 $newteachername 」老師", JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode("更動教師失敗-error2", JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode("更動教師失敗-error1", JSON_UNESCAPED_UNICODE);
    }

} else {
    // 如果请求不是 POST 方法，返回错误信息
    echo json_encode("error無效請求", JSON_UNESCAPED_UNICODE);
}
?>