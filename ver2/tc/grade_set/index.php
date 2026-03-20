<?php
require_once("../../set.php");

if (isset($_SESSION['login_check'])) {
    if ($_SESSION['login_check'] != "T") {
        echo "<script>alert('您尚未登入 error-11');</script>";
        echo "<script>window.location.href = '../index.php';</script>";
    }
} else {
    echo "<script>alert('您尚未登入  error-12');</script>";
    echo "<script>window.location.href = '../index.php';</script>";
}

?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <title>六和高中 - 教師成績管理系統</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* Color Tokens - 現代 SaaS 色彩系統 */
            --color-primary: #3b82f6;
            --color-primary-hover: #2563eb;
            --color-primary-active: #1d4ed8;
            --color-primary-ghost: rgba(59, 130, 244, 0.08);

            --color-bg: #ffffff;
            --color-bg-muted: #f8fafc;
            --color-bg-light: #f3f4f6;

            --color-text: #0f172a;
            --color-text-secondary: #4b5563;
            --color-text-tertiary: #9ca3af;

            --color-border: #e5e7eb;
            --color-border-light: #f3f4f6;

            --color-danger: #dc2626;
            --color-danger-hover: #b91c1c;
            --color-danger-ghost: #fee2e2;

            --color-success: #16a34a;
            --color-success-hover: #15803d;
            --color-success-ghost: #dcfce7;

            --color-warning: #f59e0b;
            --color-warning-hover: #d97706;
            --color-warning-ghost: #fef3c7;

            --color-info: #0ea5e9;
            --color-info-hover: #0284c7;
            --color-info-ghost: #f0f9ff;

            /* Spacing Tokens */
            --space-xs: 4px;
            --space-sm: 8px;
            --space-md: 12px;
            --space-lg: 16px;
            --space-xl: 20px;
            --space-2xl: 24px;

            /* Typography Tokens */
            --text-title-page: 1.5rem;
            --text-title-section: 1.25rem;
            --text-body: 1rem;
            --text-caption: 0.875rem;

            --weight-regular: 400;
            --weight-medium: 500;
            --weight-semibold: 600;
            --weight-bold: 700;

            /* Border & Radius */
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 10px;

            /* Icon System (FontAwesome 6.4.0) */
            --icon-stroke: 1.5px;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, "微軟正黑體", "Microsoft JhengHei", sans-serif;
            background: radial-gradient(circle at 20% 20%, rgba(59, 130, 244, 0.08), transparent 30%),
                radial-gradient(circle at 80% 0%, rgba(14, 165, 233, 0.07), transparent 28%),
                var(--color-bg-muted);
            color: var(--color-text-secondary);
            line-height: 1.6;
            position: relative;
            padding: var(--space-2xl) var(--space-lg);
        }

        body::before {
            display: none;
        }

        body::after {
            display: none;
        }

        .main-container {
            max-width: 90%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: var(--space-lg);
            position: relative;
            z-index: 1;
        }

        /* Header Section */
        .header-section {
            background: var(--color-bg);
            background: linear-gradient(135deg, #ffffff, #f9fbff);
            border-radius: var(--radius-lg);
            padding: var(--space-2xl);
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.08);
            border: 1px solid var(--color-border);
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            gap: var(--space-xl);
            transition: all 0.2s ease;
            flex-wrap: wrap;
        }

        .header-section:hover {
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.12);
            border-color: var(--color-primary-ghost);
        }

        .header-brand {
            flex: 1;
            min-width: 280px;
        }

        .header-title {
            font-size: var(--text-title-page);
            font-weight: var(--weight-bold);
            color: var(--color-text);
            display: flex;
            align-items: center;
            gap: var(--space-md);
            letter-spacing: -0.3px;
            margin-bottom: var(--space-md);
        }

        .header-title i {
            color: var(--color-text-tertiary);
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .header-title:hover i {
            color: var(--color-primary);
            transform: scale(1.08);
        }

        .welcome-text {
            font-size: var(--text-body);
            color: var(--color-text-secondary);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            font-weight: var(--weight-medium);
        }

        .welcome-text i {
            color: var(--color-text-tertiary);
            transition: color 0.2s ease;
        }

        .welcome-text:hover i {
            color: var(--color-primary);
        }

        .header-actions {
            display: flex;
            flex-wrap: wrap;
            gap: var(--space-md);
            align-items: center;
        }

        /* Button Styles */
        .btn {
            padding: 11px 20px;
            font-size: var(--text-body);
            font-weight: var(--weight-semibold);
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-sm);
            white-space: nowrap;
            user-select: none;
            position: relative;
            overflow: hidden;
            line-height: 1.4;
            min-height: 42px;
            vertical-align: middle;
        }

        /* Primary Button */
        .btn-primary {
            background: var(--color-primary);
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(59, 130, 244, 0.2);
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--color-primary-hover);
            box-shadow: 0 2px 8px rgba(59, 130, 244, 0.3);
        }

        .btn-primary:active:not(:disabled) {
            transform: scale(0.96);
            filter: brightness(0.95);
            box-shadow: 0 1px 3px rgba(59, 130, 244, 0.2);
        }

        .btn-primary:focus-visible {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
        }

        /* Secondary Button */
        .btn-secondary {
            background: var(--color-bg-light);
            color: var(--color-text);
            border: 1px solid var(--color-border);
        }

        .btn-secondary:hover:not(:disabled) {
            background: var(--color-bg-muted);
            border-color: var(--color-border-light);
            color: var(--color-text);
        }

        .btn-secondary:active:not(:disabled) {
            transform: scale(0.96);
            background: var(--color-border-light);
        }

        .btn-secondary:focus-visible {
            outline: 2px solid var(--color-text-secondary);
            outline-offset: 2px;
        }

        /* Success Button */
        .btn-success {
            background: var(--color-success);
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(22, 163, 74, 0.2);
        }

        .btn-success:hover:not(:disabled) {
            background: var(--color-success-hover);
            box-shadow: 0 2px 8px rgba(22, 163, 74, 0.3);
        }

        .btn-success:active:not(:disabled) {
            transform: scale(0.96);
            filter: brightness(0.95);
        }

        .btn-success:focus-visible {
            outline: 2px solid var(--color-success);
            outline-offset: 2px;
        }

        /* Warning Button */
        .btn-warning {
            background: var(--color-warning);
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(245, 158, 11, 0.2);
        }

        .btn-warning:hover:not(:disabled) {
            background: var(--color-warning-hover);
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:active:not(:disabled) {
            transform: scale(0.96);
            filter: brightness(0.95);
        }

        .btn-warning:focus-visible {
            outline: 2px solid var(--color-warning);
            outline-offset: 2px;
        }

        /* Danger Button */
        .btn-danger {
            background: transparent;
            color: var(--color-danger);
            border: 1px solid var(--color-danger);
        }

        .btn-danger:hover:not(:disabled) {
            background: var(--color-danger);
            color: #ffffff;
        }

        .btn-danger:active:not(:disabled) {
            transform: scale(0.96);
            filter: brightness(0.95);
        }

        .btn-danger:focus-visible {
            outline: 2px solid var(--color-danger);
            outline-offset: 2px;
        }

        /* Info Button */
        .btn-info {
            background: var(--color-info);
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(14, 165, 233, 0.2);
        }

        .btn-info:hover:not(:disabled) {
            background: var(--color-info-hover);
            box-shadow: 0 2px 8px rgba(14, 165, 233, 0.3);
        }

        .btn-info:active:not(:disabled) {
            transform: scale(0.96);
            filter: brightness(0.95);
        }

        .btn-info:focus-visible {
            outline: 2px solid var(--color-info);
            outline-offset: 2px;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Content Grid - 鎖定三欄布局 */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: var(--space-xl);
            align-items: start;
        }

        .content-grid>.week,
        .content-grid>.grade {
            min-height: 360px;
        }

        .modern-card {
            background: var(--color-bg);
            border-radius: var(--radius-lg);
            padding: var(--space-2xl);
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
            border: 1px solid var(--color-border);
            display: flex;
            flex-direction: column;
            gap: var(--space-lg);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
            height: calc(100vh - 200px);
            min-height: 600px;
        }

        .modern-card:hover {
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.12);
            border-color: var(--color-primary-ghost);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: var(--space-lg);
            border-bottom: 2px solid var(--color-primary);
            margin-bottom: var(--space-lg);
            min-height: 40px;
            position: relative;
        }

        .card-header::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 120px;
            height: 2px;
            background: linear-gradient(90deg, var(--color-primary), transparent);
        }

        .card-title {
            font-size: var(--text-title-section);
            font-weight: var(--weight-bold);
            color: var(--color-text);
            display: flex;
            align-items: center;
            gap: var(--space-md);
            letter-spacing: -0.3px;
        }

        .card-title i {
            color: var(--color-text-tertiary);
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .card-title:hover i {
            color: var(--color-primary);
            transform: scale(1.08);
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }

        thead {
            background: var(--color-bg-muted);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        th {
            padding: var(--space-lg) var(--space-md);
            text-align: center;
            color: var(--color-text);
            font-weight: var(--weight-bold);
            font-size: var(--text-caption);
            border-bottom: 2px solid var(--color-primary);
            letter-spacing: 0.3px;
        }

        th i {
            color: var(--color-text-tertiary);
            margin-right: var(--space-sm);
            transition: color 0.2s ease, transform 0.2s ease;
        }

        th:hover i {
            color: var(--color-primary);
            transform: scale(1.08);
        }

        td {
            padding: var(--space-lg) var(--space-md);
            text-align: center;
            border-bottom: 1px solid var(--color-border-light);
            color: var(--color-text-secondary);
            font-weight: var(--weight-medium);
            background: #ffffff;
        }

        tbody tr:nth-child(odd) td {
            background: rgba(59, 130, 244, 0.02);
        }

        tbody tr {
            transition: all 0.2s ease;
        }

        tbody tr:hover td {
            background: var(--color-primary-ghost);
        }

        .icon-btn {
            background: var(--color-bg-light);
            border: 1px solid var(--color-border);
            cursor: pointer;
            padding: var(--space-sm) var(--space-md);
            margin-left: var(--space-md);
            color: var(--color-text-tertiary);
            transition: all 0.2s ease;
            border-radius: var(--radius-sm);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .icon-btn:hover {
            background: var(--color-bg-muted);
            color: var(--color-primary);

            /* 響應式優化 */
            @media (max-width: 1024px) {
                body {
                    padding: var(--space-xl) var(--space-lg);
                }

                .header-section {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .header-actions {
                    width: 100%;
                    justify-content: flex-start;
                }
            }

            @media (max-width: 768px) {
                body {
                    padding: var(--space-lg) var(--space-md);
                }

                .header-title {
                    font-size: var(--text-title-section);
                }

                .button-group .btn {
                    min-width: 140px;
                }
            }

            @media (max-width: 540px) {
                .header-actions {
                    flex-direction: column;
                    align-items: stretch;
                }

                border: 3px solid var(--color-border);
                border-radius: 50%;
                border-top-color: var(--color-primary);
                border-right-color: var(--color-primary);
                animation: spin 0.8s ease-in-out infinite;
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            /* Button Group */
            .button-group {
                display: flex;
                gap: var(--space-md);
                flex-wrap: wrap;
                margin-bottom: var(--space-lg);
            }

            .button-group .btn {
                flex: 1;
                min-width: 200px;
                justify-content: center;
            }

            /* Table Container */
            .table-container {
                overflow-y: auto;
                overflow-x: auto;
                flex: 1;
            }

            /* Scrollbar Customization */
            .table-container::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }

            .table-container::-webkit-scrollbar-track {
                background: var(--color-bg-light);
                border-radius: 4px;
            }

            .table-container::-webkit-scrollbar-thumb {
                background: var(--color-border);
                border-radius: 4px;
                transition: background 0.2s ease;
            }

            .table-container::-webkit-scrollbar-thumb:hover {
                background: var(--color-primary);
            }

            /* Input & Form Controls */
            input,
            textarea,
            select {
                font-family: inherit;
                transition: border 0.2s ease, box-shadow 0.2s ease;
            }

            input:focus,
            textarea:focus,
            select:focus {
                border: 2px solid var(--color-primary);
                box-shadow: 0 0 0 4px var(--color-primary-ghost);
                outline: none;
            }

            input::placeholder,
            textarea::placeholder {
                color: var(--color-text-tertiary);
                font-style: italic;
                opacity: 0.8;
            }

            /* 鎖定布局 - 移除響應式 */
            @media (max-width: 1280px) {
                body {
                    overflow-x: auto;
                }

                .main-container {
                    min-width: 1240px;
                }
            }

            /* 小螢幕保持橫向滾動 */
            @media (max-width: 768px) {
                body {
                    padding: var(--space-lg) var(--space-md);
                }

                .main-container {
                    gap: var(--space-lg);
                }

                .header-section {
                    padding: var(--space-xl) var(--space-lg);
                    flex-direction: column;
                    align-items: flex-start;
                }

                .header-brand {
                    width: 100%;
                }

                .header-title {
                    font-size: var(--text-title-section);
                }

                .main-container {
                    min-width: 1240px;
                }

                .header-section {
                    min-width: 1200px;
                }
            }

            @media (max-width: 480px) {
                body {
                    overflow-x: auto;
                }

                .main-container {
                    min-width: 1240px;
                }
            }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="header-brand">
                <h1 class="header-title">
                    <i class="fas fa-graduation-cap"></i>
                    六和高中 成績管理系統
                </h1>
                <div class="welcome-text">
                    <?php
                    $classNum = $_SESSION["classNum"];
                    $name = $_SESSION["name"];
                    echo "<i class='fas fa-user-circle'></i> $name 老師，歡迎回來";
                    ?>
                </div>
            </div>
            <div class="header-actions">
                <a href='../result.php' class='btn btn-info'>
                    <i class="fas fa-home"></i> 返回主畫面
                </a>
                <a href='../index.php' class='btn btn-danger'>
                    <i class="fas fa-sign-out-alt"></i> 登出
                </a>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Week Management Card -->
            <div class="modern-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-calendar-alt"></i>
                        區間項目編輯
                    </div>
                </div>
                <div class="button-group">
                    <button name="set_week" id="insert_week" class="btn btn-success" week_name="week_name"
                        tc_class="<?= $classNum ?>" value="新增區間">
                        <i class="fas fa-plus-circle"></i> 新增考試區間/日期
                    </button>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar-check"></i> 區間</th>
                                <th><i class="fas fa-clock"></i> 最後更動時間</th>
                                <th><i class="fas fa-cogs"></i> 操作</th>
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
                                    $last_set_time = $row["last_set_time"];
                                    echo "<tr>
                                        <td style='font-weight: 600; color: var(--color-text);'>
                                            $week_name
                                            <button class='icon-btn' name='set_week' id='$week_ID' value='week_name編輯' tc_class='$classNum' week_name='$week_name' title='編輯區間名稱'>
                                                <i class='fas fa-edit'></i>
                                            </button>
                                        </td>
                                        <td style='color: var(--color-text-tertiary); font-size: 0.9rem;'>$last_set_time</td>
                                        <td>
                                            <button name='set_week' id='$week_ID' value='week編輯' tc_class='$classNum' week_name='$week_name' class='btn btn-warning'>
                                                <i class='fas fa-tasks'></i> 成績管理
                                            </button>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3' class='empty-state'>
                                    <i class='fas fa-inbox'></i>
                                    <div style='margin-top: 8px; font-weight: 600;'>尚無考試區間</div>
                                    <div style='font-size: 0.9rem; margin-top: 4px;'>請點擊上方按鈕新增</div>
                                  </td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="week"></div>
            <div class="grade"></div>
        </div>
    </div>
</body>

<script>
    $(document).ready(function () {
        // 初始載入週次資料
        load_data_week();

        function load_data_week(btn_val, btn_id, tc_class, week_name) {
            // 顯示載入動畫（僅在有操作時）
            if (btn_val || btn_id) {
                $('.week').html('<div class="loading-container"><div class="spinner"></div><div>資料載入中...</div></div>');
                // 切換週次時清空成績欄位
                $('.grade').empty();
            }

            $.ajax({
                url: "week.php",
                method: "GET",
                data: {
                    btn_val: btn_val,
                    btn_id: btn_id,
                    tc_class: tc_class,
                    week_name: week_name,
                },
                success: function (data) {
                    $('.week').html(data);
                },
                error: function () {
                    $('.week').html('<div class="empty-state"><i class="fas fa-exclamation-triangle"></i><div>連線發生錯誤，請稍後再試</div></div>');
                }
            });
        }

        // 使用事件委派 (Event Delegation) 優化效能與動態元素支援
        $(document).on('click', '[name="set_week"]', function (e) {
            e.preventDefault();
            var $this = $(this);

            // 取得參數
            var btn1 = $this.attr('value');
            var btn2 = $this.attr('id');
            var btn3 = $this.attr('tc_class');
            var btn4 = $this.attr('week_name');

            // 執行載入
            if (btn1 && btn2 && btn3 && btn4) {
                load_data_week(btn1, btn2, btn3, btn4);
            } else {
                load_data_week();
            }
        });
    });
</script>

<?php

?>