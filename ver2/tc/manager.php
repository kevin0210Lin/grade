<?php
require_once("../set.php");

if (isset($_SESSION['login_check'])) {
    if ($_SESSION['login_check'] != "T") {
        $conn->close();
        echo "<script>alert('您尚未登入');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    }
} else {
    $conn->close();
    echo "<script>alert('您尚未登入');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}

if (!isset($_SESSION['manager'])) {
    $conn->close();
    echo "<script>alert('權限不足');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $week_ID = $_POST['week_ID'];
    $open_time = $_POST['open_datetime'];

    // 確保避免 SQL Injection
    $week_ID = mysqli_real_escape_string($conn, $week_ID);
    $open_time = mysqli_real_escape_string($conn, $open_time);

    $sql = "UPDATE `junior3_week_set` 
            SET `open_time` = '$open_time' 
            WHERE `week_ID` = '$week_ID'";

    if (mysqli_query($conn, $sql)) {
         echo "<script>alert('更新成功');</script>";
         echo "<script>window.location.href = 'manager.php';</script>";
    } else {
        echo "<script>alert('更新失敗!!!');</script>";
       echo "<script>window.location.href = 'manager.php';</script>";
    }
}

$name = $_SESSION["name"];
?>


<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="" type="image/x-icon">
    <title>週次管理 - 六和高中成績系統</title>
    <link rel="stylesheet" href="tc-shared-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #f5f7fa;
            --bg-secondary: #f0f4fb;
            --bg-tertiary: #eef2f8;
            --card-bg: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #1f2937;
            --text-tertiary: #6b7280;
            --text-muted: #8b95a5;
            --border-light: #e5e7eb;
            --border-subtle: rgba(59, 130, 244, 0.1);
            --blue-primary: #3b82f6;
            --blue-dark: #2563eb;
            --blue-light: rgba(59, 130, 244, 0.08);
            --red-primary: #ef4444;
            --red-light: rgba(239, 68, 68, 0.08);
            --yellow-primary: #f59e0b;
            --yellow-light: rgba(245, 158, 11, 0.08);
            --green-primary: #10b981;
            --green-light: rgba(16, 185, 129, 0.08);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, "微軟正黑體", "Microsoft JhengHei", sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: 100vh;
            color: var(--text-secondary);
            line-height: 1.6;
            padding: 0;
        }

        .page-wrapper {
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* 頭部導航 */
        .header-nav {
            background: var(--card-bg);
            border-radius: 18px;
            padding: 24px 32px;
            margin-bottom: 32px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            border: 1px solid var(--border-subtle);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            position: relative;
            overflow: hidden;
        }

        .header-nav::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--blue-primary);
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-title h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .header-title i {
            color: var(--blue-primary);
            font-size: 28px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            background: var(--blue-light);
            border-radius: 10px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .user-info i {
            color: var(--blue-primary);
        }

        /* 按鈕樣式 */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--blue-primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--blue-dark);
        }

        .btn-danger {
            background: var(--red-primary);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-warning {
            background: var(--yellow-primary);
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        /* 警告卡片 */
        .warning-card {
            background: var(--card-bg);
            border-radius: 14px;
            padding: 20px 24px;
            margin-bottom: 24px;
            border-left: 4px solid var(--red-primary);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.08);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .warning-card i {
            color: var(--red-primary);
            font-size: 24px;
        }

        .warning-card-content {
            flex: 1;
        }

        .warning-card-title {
            font-weight: 700;
            color: var(--red-primary);
            margin-bottom: 4px;
            font-size: 16px;
        }

        .warning-card-text {
            color: var(--text-tertiary);
            font-size: 14px;
        }

        /* 表格卡片 */
        .table-card {
            background: var(--card-bg);
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            border: 1px solid var(--border-subtle);
            position: relative;
            overflow: hidden;
        }

        .table-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--blue-primary);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 16px;
        }

        th {
            background: var(--blue-primary);
            color: white;
            padding: 16px;
            text-align: center;
            font-weight: 600;
            font-size: 15px;
            border: none;
        }

        th:first-child {
            border-radius: 12px 0 0 0;
        }

        th:last-child {
            border-radius: 0 12px 0 0;
        }

        td {
            padding: 20px 16px;
            text-align: center;
            border-bottom: 1px solid var(--border-light);
            color: var(--text-secondary);
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: var(--blue-light);
        }

        /* 表單樣式 */
        .inline-form {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 12px;
            padding: 16px;
            background: var(--bg-primary);
            border-radius: 10px;
            border: 1px solid var(--border-light);
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-tertiary);
            text-align: left;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-label i {
            color: var(--blue-primary);
        }

        .form-control {
            padding: 10px 14px;
            border: 1.5px solid var(--border-light);
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            background: white;
            color: var(--text-secondary);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--blue-primary);
            box-shadow: 0 0 0 3px var(--blue-light);
        }

        /* 週次區塊樣式 */
        .week-header {
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
        }

        .week-name {
            font-weight: 700;
            font-size: 16px;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .week-name i {
            color: var(--blue-primary);
        }

        /* 測驗項目樣式 */
        .test-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: center;
        }

        .test-name {
            font-weight: 600;
            color: var(--text-secondary);
        }

        /* 響應式設計 */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .header-nav {
                flex-direction: column;
                text-align: center;
            }

            .header-actions {
                width: 100%;
                justify-content: center;
            }

            .table-card {
                padding: 20px;
                overflow-x: auto;
            }

            table {
                font-size: 13px;
            }

            th, td {
                padding: 12px 8px;
            }

            .btn {
                padding: 8px 16px;
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <div class="container">
            <!-- 頂部導航 -->
            <div class="header-nav">
                <div class="header-title">
                    <i class="fas fa-calendar-week"></i>
                    <h1>六和高中 國三成績系統 - 週次管理</h1>
                </div>
                <div class="header-actions">
                    <div class="user-info">
                        <i class="fas fa-user-shield"></i>
                        <span><?php echo $name; ?> 您好</span>
                    </div>
                    <a href='index.php' class='btn btn-danger'>
                        <i class="fas fa-sign-out-alt"></i>
                        登出
                    </a>
                </div>
            </div>

            <!-- 警告提示 -->
            <div class="warning-card">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="warning-card-content">
                    <div class="warning-card-title">⚠️ 重要提醒</div>
                    <div class="warning-card-text">此帳號的刪除操作皆不能復原，請小心操作！</div>
                </div>
            </div>

            <!-- 週次列表表格 -->
            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-calendar-alt"></i> 週次</th>
                            <th><i class="fas fa-file-alt"></i> 考試內容</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM `junior3_week_set` ORDER BY `week_ID` DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $week_ID = $row["week_ID"];
                                $week_name = $row["week_name"];
                                $open_time = $row["open_time"];

                                $sql1 = "SELECT * FROM `junior3_grade_set` WHERE `week_ID` = {$week_ID} ORDER BY `test_ID` ASC";
                                $result1 = $conn->query($sql1);

                                if ($result1->num_rows > 0) {
                                    echo "<tr>
                                <td rowspan='" . $result1->num_rows . "'>
                                    <div class='week-header'>
                                        <div class='week-name'>
                                            <i class='fas fa-bookmark'></i>
                                            {$week_name}
                                        </div>
                                        <a href='manager_update.php?week_ID={$week_ID}' class='btn btn-warning btn-sm' onclick=\"return confirm('⚠️ 是否確定刪除「{$week_name}」區間？\\n此操作無法復原！');\">
                                            <i class='fas fa-trash-alt'></i>
                                            刪除此區間
                                        </a>
                                        <form action='' method='POST' class='inline-form'>
                                            <label for='open_datetime_{$week_ID}' class='form-label'>
                                                <i class='fas fa-clock'></i>
                                                開放查詢時間
                                            </label>
                                            <input type='hidden' name='week_ID' value='{$week_ID}'>
                                            <input 
                                                type='datetime-local'
                                                id='open_datetime_{$week_ID}' 
                                                name='open_datetime' 
                                                class='form-control' 
                                                value='$open_time' 
                                                step='60'
                                                required
                                            >
                                            <button type='submit' class='btn btn-primary btn-sm'>
                                                <i class='fas fa-check'></i>
                                                更新設定
                                            </button>
                                        </form>
                                    </div>
                                </td>";

                                    $first = true;
                                    while ($row1 = $result1->fetch_assoc()) {
                                        $test_name = $row1["test_name"];
                                        $test_ID = $row1['test_ID'];

                                        if (!$first)
                                            echo "<tr>"; // 非第一列需加新列
                                        echo "<td>
                                        <div class='test-item'>
                                            <span class='test-name'>
                                                <i class='fas fa-pencil-alt'></i>
                                                {$test_name}
                                            </span>
                                            <a href='manager_update.php?week_ID={$week_ID}&test_ID={$test_ID}' class='btn btn-warning btn-sm' onclick=\"return confirm('⚠️ 是否確定刪除「{$test_name}」成績？\\n此操作無法復原！');\">
                                                <i class='fas fa-trash-alt'></i>
                                                刪除此成績
                                            </a>
                                        </div>
                                      </td>
                                    </tr>";
                                        $first = false;
                                    }

                                } else {
                                    echo "<tr>
                                <td>
                                    <div class='week-header'>
                                        <div class='week-name'>
                                            <i class='fas fa-bookmark'></i>
                                            {$week_name}
                                        </div>
                                        <a href='manager_update.php?week_ID={$week_ID}' class='btn btn-warning btn-sm' onclick=\"return confirm('⚠️ 是否確定刪除「{$week_name}」區間？\\n此操作無法復原！');\">
                                            <i class='fas fa-trash-alt'></i>
                                            刪除此區間
                                        </a>
                                        <form action='' method='POST' class='inline-form'>
                                            <label for='open_datetime_{$week_ID}' class='form-label'>
                                                <i class='fas fa-clock'></i>
                                                開放查詢時間
                                            </label>
                                            <input type='hidden' name='week_ID' value='{$week_ID}'>
                                            <input 
                                                type='datetime-local'
                                                id='open_datetime_{$week_ID}' 
                                                name='open_datetime' 
                                                class='form-control' 
                                                value='$open_time' 
                                                step='60'
                                                required
                                            >
                                            <button type='submit' class='btn btn-primary btn-sm'>
                                                <i class='fas fa-check'></i>
                                                更新設定
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td style='color: var(--text-muted); font-style: italic;'>
                                    <i class='fas fa-info-circle'></i>
                                    尚無成績
                                </td>
                              </tr>";
                                }
                            }
                        } else {
                            echo "<tr>
                        <td colspan='2' style='padding: 40px; color: var(--text-muted);'>
                            <i class='fas fa-inbox' style='font-size: 48px; opacity: 0.3; display: block; margin-bottom: 16px;'></i>
                            <div style='font-size: 16px;'>目前沒有任何週次設定</div>
                        </td>
                    </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // 確認對話框增強
        document.querySelectorAll('a[onclick*="confirm"]').forEach(link => {
            link.addEventListener('click', function(e) {
                const originalOnclick = this.getAttribute('onclick');
                if (originalOnclick && !originalOnclick.includes('⚠️')) {
                    e.preventDefault();
                    const message = originalOnclick.match(/confirm\('(.+?)'\)/)[1];
                    if (confirm('⚠️ ' + message + '\n此操作無法復原！')) {
                        window.location.href = this.href;
                    }
                }
            });
        });

        // 表單提交前確認
        document.querySelectorAll('.inline-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const datetime = this.querySelector('input[type="datetime-local"]').value;
                if (!datetime) {
                    e.preventDefault();
                    alert('⚠️ 請選擇開放查詢時間！');
                }
            });
        });

        // 滑動動畫
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.table-card, .warning-card').forEach(el => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            observer.observe(el);
        });
    </script>
</body>
</html>