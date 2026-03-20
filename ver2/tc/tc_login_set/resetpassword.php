<?php
require_once("../../set.php");
header('Content-Type: application/json; charset=UTF-8');


if (isset($_GET['tcaccount'], $_GET['tcname'])) {

    $tcaccount = $_GET['tcaccount'];
    $tcname = $_GET['tcname'];
    $sql = "UPDATE `junior3_login_tc` SET `password`='123456' WHERE `id`= '$tcaccount'";

    if ($conn->query($sql)) {
    echo json_encode("已將'$tcname'的密碼重新設定為「123456」", JSON_UNESCAPED_UNICODE);
    }else{
    echo json_encode("setting error", JSON_UNESCAPED_UNICODE);

    }


} else {
    echo json_encode("error無效請求", JSON_UNESCAPED_UNICODE);
}
?>