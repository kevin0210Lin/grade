<?php
require_once("../../set.php");
header('Content-Type: application/json; charset=UTF-8');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode("error-CSRF Token 無效", JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 获取提交的表单数据
    $classNum = isset($_POST['classNum']) ? $_POST['classNum'] : '';
    $oldteachername = isset($_POST['oldteachername']) ? $_POST['oldteachername'] : '';

    // 防止 SQL 注入
    $classNum = $conn->real_escape_string($classNum);
    $oldteachername = $conn->real_escape_string($oldteachername);

    // insert
    $query1 = "UPDATE `junior3_login_tc` SET `classNum`='',`status`='subjectteacher' WHERE `classNum`='$classNum'AND`status`='teacher'";
    if ($conn->query($query1)) {
        $query2 = "UPDATE `junior3_login_tc` SET `classNum`='$classNum',`status`='teacher' WHERE `name`='$oldteachername'";
        if ($conn->query($query2)) {
            echo json_encode("已將'$classNum'班級導師更換為「 $oldteachername 」老師", JSON_UNESCAPED_UNICODE);
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