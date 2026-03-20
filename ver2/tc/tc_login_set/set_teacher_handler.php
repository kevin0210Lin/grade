<?php
require_once("../../set.php");
header('Content-Type: application/json; charset=UTF-8');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode("error-CSRF Token 無效", JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 获取提交的表单数据
    $oldteachername1 = isset($_POST['oldteachername1']) ? $_POST['oldteachername1'] : '';
    $newteachername1 = isset($_POST['newteachername1']) ? $_POST['newteachername1'] : '';
    $oldteacheraccount1 = isset($_POST['oldteacheraccount1']) ? $_POST['oldteacheraccount1'] : '';
    $newteacheraccount1 = isset($_POST['newteacheraccount1']) ? $_POST['newteacheraccount1'] : '';

    // 防止 SQL 注入
    $oldteachername1 = $conn->real_escape_string($oldteachername1);
    $newteachername1 = $conn->real_escape_string($newteachername1);
    $oldteacheraccount1 = $conn->real_escape_string($oldteacheraccount1);
    $newteacheraccount1 = $conn->real_escape_string($newteacheraccount1);

    $subjects = [];
    for ($i = 1; $i <= 9; $i++) {
        $subjectKey = "setsubject" . $i;
        $subjects[$subjectKey] = isset($_POST[$subjectKey]) && ($_POST[$subjectKey] == 'on' || $_POST[$subjectKey] == 'checked') ? 1 : 0;
    }

    // 建立更新的 SQL 語句
    $sql = "UPDATE `junior3_login_tc` SET ";
    $updateFields = [];
    foreach ($subjects as $key => $value) {
        $actualKey = substr($key, 3); 
        $updateFields[] = "`$actualKey` = $value"; // 動態生成更新的欄位和值
    }
    $sql .= implode(", ", $updateFields); // 拼接所有欄位
    $sql .= " WHERE `name` = '$oldteachername1'"; // 根據條件更新記錄
    //echo json_encode($sql, JSON_UNESCAPED_UNICODE);
     //           exit;

    // 執行更新
    if ($conn->query($sql)) {
        $sql1 = "SELECT * FROM `junior3_login_tc` WHERE `name` = '$newteachername1'";
        $sql2 = "SELECT * FROM `junior3_login_tc` WHERE `id` = '$newteacheraccount1'";

        if ($newteachername1 != $oldteachername1 && $newteacheraccount1 != $oldteacheraccount1) {
            $result1 = $conn->query($sql1);
            $result2 = $conn->query($sql2);
            if ($result1->num_rows > 0 && $result2->num_rows > 0) {
                echo json_encode("教師姓名與登入帳號不可用，請重新更新", JSON_UNESCAPED_UNICODE);
                exit;
            } else if ($result1->num_rows > 0) {
                echo json_encode("教師姓名不可用，請重新更新", JSON_UNESCAPED_UNICODE);
                exit;
            } else if ($result2->num_rows > 0) {
                echo json_encode("登入帳號不可用，請重新更新", JSON_UNESCAPED_UNICODE);
                exit;
            }
        } else if ($newteachername1 != $oldteachername1) {
            $result1 = $conn->query($sql1);
            if ($result1->num_rows > 0) {
                echo json_encode("教師姓名不可用，請重新更新", JSON_UNESCAPED_UNICODE);
                exit;
            }
        } else if ($newteacheraccount1 != $oldteacheraccount1) {
            $result2 = $conn->query($sql2);
            if ($result2->num_rows > 0) {
                echo json_encode("登入帳號不可用，請重新更新", JSON_UNESCAPED_UNICODE);
                exit;
            }
        }


        // insert
        $query1 = "UPDATE `junior3_login_tc` SET `name`='$newteachername1',`id`='$newteacheraccount1' WHERE `name`='$oldteachername1' AND `id`='$oldteacheraccount1'";
        if ($conn->query($query1)) {
            echo json_encode("教師資料更新成功", JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode("教師資料更新失敗error2: ", JSON_UNESCAPED_UNICODE);
        }

    } else {
        echo json_encode("教師資料更新失敗error1", JSON_UNESCAPED_UNICODE);
    }




} else {
    // 如果请求不是 POST 方法，返回错误信息
    echo json_encode("error無效請求", JSON_UNESCAPED_UNICODE);
}
?>