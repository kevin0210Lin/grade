<?php
require_once("set.php");
header("Content-Type: application/json");

$classNum = $_SESSION["classNum"];
$seatNum = $_SESSION["seatNum"];
$name = $_SESSION["name"];
$week = $_SESSION["week_ID_choose"];

error_reporting(E_ALL);
ini_set('display_errors', 1);

// FTP 伺服器設定
$ftp_server = "linonlinedata.com";
$ftp_user = "114savesign+linonlinedata.com";
$ftp_pass = "Savesign+_)(*&^%$#@!";
$ftp_path = "/";

// 從前端接收資料
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['image'])) {
    http_response_code(400);
    die(json_encode(["status" => "error", "message" => "未收到簽名圖片資料"]));
}

$image_data = $data['image'];

// 移除 Base64 的前綴
$image_data = preg_replace('#^data:image/\w+;base64,#i', '', $image_data);
$image_data = base64_decode($image_data);

if (!$image_data) {
    http_response_code(400);
    die(json_encode(["status" => "error", "message" => "Base64 資料解碼失敗"]));
}

// 本地暫存檔案名稱
$time = date('YmdHis');
$temp_file = $week.'_'.$classNum.$seatNum.'_'.$time.'.png';
if (!file_put_contents($temp_file, $image_data)) {
    http_response_code(500);
    die(json_encode(["status" => "error", "message" => "無法儲存本地暫存檔案"]));
}

// 連接到 FTP 伺服器
$conn_id = ftp_connect($ftp_server);
if (!$conn_id) {
    http_response_code(500);
    unlink($temp_file);
    die(json_encode(["status" => "error", "message" => "無法連接到 FTP 伺服器"]));
}

// 登入 FTP
if (!ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    ftp_close($conn_id);
    unlink($temp_file);
    http_response_code(401);
    die(json_encode(["status" => "error", "message" => "FTP 登入失敗"]));
}

// 切換到目標目錄，若不存在則建立
if (!@ftp_chdir($conn_id, $ftp_path)) {
    if (!ftp_mkdir($conn_id, $ftp_path)) {
        ftp_close($conn_id);
        unlink($temp_file);
        http_response_code(500);
        die(json_encode(["status" => "error", "message" => "無法切換到目標目錄，且無法建立"]));
    }
    ftp_chdir($conn_id, $ftp_path);
}

// 上傳檔案到 FTP
if (!ftp_put($conn_id, $temp_file, $temp_file, FTP_BINARY)) {
    ftp_close($conn_id);
    unlink($temp_file);
    http_response_code(500);
    die(json_encode(["status" => "error", "message" => "簽名上傳失敗"]));
}

// 清理本地暫存檔案
unlink($temp_file);

// 關閉 FTP 連線
ftp_close($conn_id);


$sql = "UPDATE `$week` SET `sign`='$temp_file' WHERE `classNum`='$classNum'AND `seatNum`='$seatNum';";
$conn->query($sql);


// 成功回應
echo json_encode(["status" => "success", "message" => "簽名成功"]);
?>
