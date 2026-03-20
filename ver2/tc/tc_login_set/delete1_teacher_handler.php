<?php
require_once("../../set.php");
header('Content-Type: application/json; charset=UTF-8');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode("error-CSRF Token 無效", JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 获取提交的表单数据
    $oldteachernamedelete = isset($_POST['oldteachernamedelete']) ? $_POST['oldteachernamedelete'] : '';

    // 防止 SQL 注入
    $oldteachernamedelete = $conn->real_escape_string($oldteachernamedelete);


    $query1 = "DELETE FROM `junior3_login_tc` WHERE `name`='$oldteachernamedelete'";
    if ($conn->query($query1)) {
        echo json_encode("資料已刪除", JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode("刪除教師失敗-error1", JSON_UNESCAPED_UNICODE);
    }

} else {
    // 如果请求不是 POST 方法，返回错误信息
    echo json_encode("error無效請求", JSON_UNESCAPED_UNICODE);
}
?>