<?php
require_once("set.php");
header("Content-Type: application/json");

$classNum = $_SESSION["classNum"];
$seatNum = $_SESSION["seatNum"];
$week = $_SESSION["week_ID_choose"];

// FTP 伺服器設定
$ftp_server = "linonlinedata.com";
$ftp_user = "114savesign+linonlinedata.com";
$ftp_pass = "Savesign+_)(*&^%$#@!";
$ftp_path = "/";

// 從資料庫獲取簽名檔案名稱
$sql = "SELECT `sign` FROM `$week` WHERE `classNum`='$classNum' AND `seatNum`='$seatNum';";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    http_response_code(404);
    die(json_encode(["status" => "error", "message" => "簽名資料不存在"]));
}

$row = $result->fetch_assoc();
$sign_filename = $row['sign'];

if (empty($sign_filename)) {
    http_response_code(404);
    die(json_encode(["status" => "error", "message" => "簽名檔案不存在"]));
}

// 連接到 FTP 伺服器
$conn_id = ftp_connect($ftp_server);
if (!$conn_id) {
    http_response_code(500);
    die(json_encode(["status" => "error", "message" => "無法連接到 FTP 伺服器"]));
}

// 登入 FTP
if (!ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    ftp_close($conn_id);
    http_response_code(401);
    die(json_encode(["status" => "error", "message" => "FTP 登入失敗"]));
}

// 切換到目標目錄
if (!@ftp_chdir($conn_id, $ftp_path)) {
    ftp_close($conn_id);
    http_response_code(500);
    die(json_encode(["status" => "error", "message" => "無法切換到目標目錄"]));
}

// 下載檔案到本地暫存
$temp_file = 'temp_signature_' . time() . '.png';
if (!ftp_get($conn_id, $temp_file, $sign_filename, FTP_BINARY)) {
    ftp_close($conn_id);
    http_response_code(500);
    die(json_encode(["status" => "error", "message" => "無法下載簽名檔案"]));
}

ftp_close($conn_id);

// 讀取檔案並轉換為 Base64
if (!file_exists($temp_file)) {
    http_response_code(500);
    die(json_encode(["status" => "error", "message" => "無法讀取簽名檔案"]));
}

$image_data = file_get_contents($temp_file);
$base64_image = 'data:image/png;base64,' . base64_encode($image_data);

// 刪除本地暫存檔案
unlink($temp_file);

// 回傳簽名資料
echo json_encode([
    "status" => "success",
    "image" => $base64_image
]);
?>
