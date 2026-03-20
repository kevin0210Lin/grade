<?php
/**
 * 統一的教師端頁面 Header 模板
 * 使用 grade_set/index.php 的現代設計系統
 */

// 確保會話已啟動
if (!isset($_SESSION)) {
    session_start();
}

// 檢查登入狀態
if (!isset($_SESSION['login_check']) || $_SESSION['login_check'] != "T") {
    header("Location: index.php");
    exit();
}

$classNum = isset($_SESSION["classNum"]) ? $_SESSION["classNum"] : "";
$name = isset($_SESSION["name"]) ? $_SESSION["name"] : "未知";
$title = isset($page_title) ? $page_title : "教師成績管理系統";
$current_page = isset($current_page) ? $current_page : "";
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="tc-shared-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <?php if (isset($additional_styles)) { echo $additional_styles; } ?>
</head>

<body>
    <!-- 主容器開始 -->
    <div class="main-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="header-brand">
                <h1 class="header-title">
                    <i class="fas fa-graduation-cap"></i>
                    六和高中 成績管理系統
                </h1>
                <div class="welcome-text">
                    <i class="fas fa-user-circle"></i>
                    <?php echo htmlspecialchars($name); ?> 老師，歡迎回來
                </div>
            </div>
            <div class="header-actions">
                <?php if ($current_page !== 'result.php') { ?>
                    <a href='result.php' class='btn btn-info'>
                        <i class="fas fa-home"></i> 返回主畫面
                    </a>
                <?php } ?>
                <?php if ($current_page !== 'grade_set' && isset($classNum)) { ?>
                    <a href='grade_set/' class='btn btn-primary'>
                        <i class="fas fa-tasks"></i> 成績管理
                    </a>
                <?php } ?>
                <?php if ($current_page !== 'stu_login_set' && isset($classNum)) { ?>
                    <a href='stu_login_set/' class='btn btn-primary'>
                        <i class="fas fa-users-cog"></i> 學生登入管理
                    </a>
                <?php } ?>
                <a href='index.php' class='btn btn-danger'>
                    <i class="fas fa-sign-out-alt"></i> 登出
                </a>
            </div>
        </div>
        <!-- Header Section 結束 -->
