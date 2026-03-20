<?php
require_once("../../set.php");
header('Content-Type: application/json; charset=UTF-8');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode("error-CSRF Token 無效", JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 获取提交的表单数据
    $name = isset($_POST['newname']) ? $_POST['newname'] : '';
    $id = isset($_POST['newid']) ? $_POST['newid'] : '';
    $position = isset($_POST['position']) ? $_POST['position'] : '';
    $email = isset($_POST['newemail']) ? $_POST['newemail'] : 'null';

    // 防止 SQL 注入
    $name = $conn->real_escape_string($name);
    $id = $conn->real_escape_string($id);
    $position = $conn->real_escape_string($position);
    $email = $conn->real_escape_string($email);

    $sql1 = "SELECT * FROM `junior3_login_tc` WHERE `name` = '$name'";
    $sql2 = "SELECT * FROM `junior3_login_tc` WHERE `id` = '$id'";
    $sql3 = "SELECT * FROM `junior3_login_tc` WHERE `email` = '$email'";
    //echo json_encode("$sql3", JSON_UNESCAPED_UNICODE);

    $result1 = $conn->query($sql1);
    $result2 = $conn->query($sql2);
    $result3 = $conn->query($sql3);

    if ($result1->num_rows > 0 && $result2->num_rows > 0) {
        //echo json_encode("教師姓名與登入帳號不可用，請重新新增", JSON_UNESCAPED_UNICODE);
        echo json_encode("教師姓名不可用，請重新新增", JSON_UNESCAPED_UNICODE);
        exit;
    } else if ($result2->num_rows > 0 && $result3->num_rows > 0) {
        //echo json_encode("登入帳號與電子郵件不可用，請重新新增", JSON_UNESCAPED_UNICODE);
        echo json_encode("登入帳號不可用，請重新新增", JSON_UNESCAPED_UNICODE);
        exit;
    } else if ($result1->num_rows > 0) {
        echo json_encode("教師姓名不可用，請重新新增", JSON_UNESCAPED_UNICODE);
        exit;
    } else if ($result2->num_rows > 0) {
        echo json_encode("登入帳號不可用，請重新新增", JSON_UNESCAPED_UNICODE);
        exit;
    } else if ($result3->num_rows > 0) {
        //echo json_encode("電子郵件不可用，請重新新增", JSON_UNESCAPED_UNICODE);
        //exit;
    }

    $subjects = [];
    for ($i = 1; $i <= 9; $i++) {
        $subjectKey = "subject" . $i;
        // 確保只接受 1 或 0，並將其轉換為整數
        $subjects[$subjectKey] = isset($_POST[$subjectKey]) && ($_POST[$subjectKey] == 'on' || $_POST[$subjectKey] == 'checked') ? 1 : 0;
    }

    if ($position != "subjectteacher") {
        $classNum = $position;
        $position = "teacher";
    } else {
        $classNum = "";
    }

    // insert
    $query = "INSERT INTO `junior3_login_tc`(`num_ID`, `name`, `id`, `password`, `classNum`, `status`, `email`, `subject1`, `subject2`, `subject3`, `subject4`, `subject5`, `subject6`, `subject7`, `subject8`, `subject9`) 
              VALUES ('','$name','$id','123456','$classNum','$position','$email', " . implode(",", $subjects) . ")";
    if ($conn->query($query)) {
        echo json_encode("$name  教師帳號新增成功", JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode("新增教師失敗", JSON_UNESCAPED_UNICODE);
    }

} else {
    // 如果请求不是 POST 方法，返回错误信息
    echo json_encode("error無效請求", JSON_UNESCAPED_UNICODE);
}
?>