<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../tc-shared-styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <title>更新處理中...</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Microsoft JhengHei', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loading-container {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3b82f6;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h2 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        p {
            color: #4a5568;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="loading-container">
        <div class="spinner"></div>
        <h2><i class="fas fa-sync-alt"></i> 正在更新資料...</h2>
        <p>請稍候，系統正在處理您的請求</p>
    </div>
</body>
</html>

<?php
require_once("../../set.php");
require_once("lang.php");

if (isset($_SESSION['login_check'])) {
    if ($_SESSION['login_check'] != "T") {
        echo "<script>alert('" . _lang('not_logged_in') . "');</script>";
        echo "<script>window.location.href = '../index.php';</script>";
    }
} else {
    echo "<script>alert('" . _lang('not_logged_in') . "');</script>";
    echo "<script>window.location.href = '../index.php';</script>";
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $classNum = $_POST["classnum"];

    for ($i = 1; $i <= 9; $i++) {
        $name = $_POST["name_0" . $i];
        $id = $_POST["id_0" . $i];
        $sql = "UPDATE `junior3_login` SET `name`='$name',`loginID`='$id' WHERE `classNum` = '$classNum' AND `seatNum` = '" . "0" . "$i'";
        $conn->query($sql);
    }
    for ($i = 10; $i <= 47; $i++) {
        $name = $_POST["name_" . $i];
        $id = $_POST["id_" . $i];
        $sql = "UPDATE `junior3_login` SET `name`='$name',`loginID`='$id' WHERE `classNum` = '$classNum' AND `seatNum` = '$i'";
        $conn->query($sql);
    }

    $sql = "SELECT * FROM `junior3_week_set`";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $week_id = $row["week_ID"];
            for ($i = 1; $i <= 9; $i++) {
                $name = $_POST["name_0" . $i];
                $id = $_POST["id_0" . $i];
                $sql = "UPDATE `$week_id` SET `name`='$name' WHERE `classNum` = '$classNum' AND `seatNum` = '" . "0" . "$i'";
                $conn->query($sql);
            }
            for ($i = 10; $i <= 47; $i++) {
                $name = $_POST["name_" . $i];
                $id = $_POST["id_" . $i];
                $sql = "UPDATE `$week_id` SET `name`='$name' WHERE `classNum` = '$classNum' AND `seatNum` = '$i'";
                $conn->query($sql);
            }
        }
    }

    

    echo "<script>alert('" . _lang('changes_saved') . "');</script>";
    echo "<script>window.location.href = 'index.php';</script>";

} else {

}