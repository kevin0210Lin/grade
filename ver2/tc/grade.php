<?php
require_once("../set.php");

if (isset($_SESSION['login_check'])) {
    if ($_SESSION['login_check'] != "T") {
        echo "<script>alert('您尚未登入');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('您尚未登入');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit();
}

if (isset($_GET["week"])) {
    $week_ID = $_GET["week"];
    $_SESSION["week_ID_choose"] = $week_ID;
} else {
    echo "<script>alert('執行錯誤');</script>";
    echo "<script>window.location.href = 'result.php';</script>";
    exit();
}
if (isset($_GET["week"])) {
    $testname = $_GET["n"];
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績總覽 - 六和高中成績系統</title>
    <link rel="icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="tc-shared-styles.css">
    <!-- ✅ Bootstrap 4.5.2 + Icons 1.10.4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">

    <!-- ✅ Bootstrap Table CSS 對應版本 1.24.0 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/bootstrap-table.min.css">

    <!-- ✅ Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* Color Tokens - 現代 SaaS 系統 */
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
            --color-success: #16a34a;
            --color-warning: #f59e0b;

            /* Spacing Tokens */
            --space-xs: 4px;
            --space-sm: 8px;
            --space-md: 12px;
            --space-lg: 16px;
            --space-xl: 20px;
            --space-2xl: 24px;

            /* Typography */
            --text-title-page: 1.5rem;
            --text-title-section: 1.25rem;
            --text-body: 1rem;
            --text-caption: 0.875rem;

            --weight-regular: 400;
            --weight-medium: 500;
            --weight-semibold: 600;
            --weight-bold: 700;

            /* Radius */
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 10px;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, "微軟正黑體", "Microsoft JhengHei", sans-serif;
            background: var(--color-bg-muted);
            min-height: 100vh;
            color: var(--color-text-secondary);
            line-height: 1.6;
            padding: var(--space-2xl) var(--space-lg);
        }

        .app-container {
            max-width: 1280px;
            margin: 0 auto;
        }

        /* 頁面標題 */
        .page-header {
            text-align: center;
            margin-bottom: var(--space-2xl);
        }

        .page-title {
            font-size: var(--text-title-page);
            font-weight: var(--weight-bold);
            color: var(--color-text);
            margin-bottom: var(--space-sm);
            letter-spacing: -0.4px;
        }

        .page-subtitle {
            font-size: var(--text-caption);
            color: var(--color-text-tertiary);
            font-weight: var(--weight-semibold);
            letter-spacing: 0.3px;
        }

        /* 導航卡片 */
        .nav-card {
            background: var(--color-bg);
            border-radius: var(--radius-lg);
            padding: var(--space-2xl);
            margin-bottom: var(--space-xl);
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
            border: 1px solid var(--color-border);
            position: relative;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .nav-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--color-primary);
        }

        .nav-card:hover {
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
            border-color: var(--color-primary-ghost);
        }

        .nav-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--color-primary);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--color-text);
            line-height: 1.3;
        }

        .user-role {
            font-size: 0.85rem;
            color: var(--color-text-tertiary);
            font-weight: var(--weight-medium);
        }

        .nav-actions {
            display: flex;
            gap: var(--space-md);
            flex-wrap: wrap;
        }

        .btn-modern {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 20px;
            border-radius: var(--radius-md);
            font-weight: var(--weight-semibold);
            font-size: var(--text-body);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            white-space: nowrap;
            line-height: 1.4;
            min-height: 42px;
            overflow: hidden;
        }

        .btn-modern:focus-visible {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
            box-shadow: 0 0 0 4px var(--color-primary-ghost);
        }

        .btn-modern:hover {
            text-decoration: none;
            transform: scale(0.98);
        }

        .btn-primary-modern {
            background: var(--color-primary);
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(59, 130, 244, 0.2);
        }

        .btn-primary-modern:hover {
            background: var(--color-primary-hover);
        }

        .btn-success-modern {
            background: var(--color-success);
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(22, 163, 74, 0.2);
        }

        .btn-success-modern:hover {
            background: var(--color-success-hover);
        }

        .btn-danger-modern {
            background: transparent;
            color: var(--color-danger);
            border: 1px solid var(--color-danger);
        }

        .btn-danger-modern:hover {
            background: var(--color-danger);
            color: #ffffff;
        }

        .btn-info-modern {
            background: var(--color-info);
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(14, 165, 233, 0.2);
        }

        .btn-info-modern:hover {
            background: var(--color-info-hover);
        }

        /* 表格卡片 */
        .table-card {
            background: var(--color-bg);
            border-radius: var(--radius-lg);
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
            border: 1px solid var(--color-border);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .table-card:hover {
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
            border-color: var(--color-primary-ghost);
        }

        .table-card-header {
            padding: var(--space-2xl);
            border-bottom: 2px solid var(--color-primary);
            background: var(--color-bg-muted);
        }

        #toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .toolbar-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            background: var(--color-success);
            color: #ffffff;
            border-radius: var(--radius-md);
            font-weight: var(--weight-bold);
            font-size: var(--text-body);
        }

        .table-wrapper {
            padding: 2%;
            overflow: auto;

        }

        /* Bootstrap Table 樣式優化 */
        .bootstrap-table .fixed-table-toolbar {
            border-radius: var(--radius-md);
            background: var(--color-bg-light);
            padding: var(--space-lg);
            margin-bottom: 0;
            border: 1px solid var(--color-border);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        .bootstrap-table .fixed-table-toolbar .search input {
            border-radius: var(--radius-md);
            border: 1px solid var(--color-border);
            padding: var(--space-md);
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .bootstrap-table .fixed-table-toolbar .search input:focus {
            border: 2px solid var(--color-primary);
            box-shadow: 0 0 0 4px var(--color-primary-ghost);
            outline: none;
            padding: calc(var(--space-md) - 1px);
        }

        .bootstrap-table .fixed-table-toolbar .search input::placeholder {
            color: var(--color-text-tertiary);
            font-style: italic;
            opacity: 0.8;
        }

        #grade-table {
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        #grade-table thead {
            background: var(--color-bg-muted);
        }

        #grade-table thead th {
            background: var(--color-bg-muted);
            color: var(--color-text);
            font-weight: var(--weight-bold);
            border-bottom: 2px solid var(--color-primary);
            padding: var(--space-lg) var(--space-md);
            text-align: center;
            font-size: var(--text-caption);
            letter-spacing: 0.3px;
        }

        #grade-table tbody td {
            padding: var(--space-lg) var(--space-md);
            vertical-align: middle;
            border-bottom: 1px solid var(--color-border-light);
            text-align: center;
            font-weight: var(--weight-medium);
            color: var(--color-text-secondary);
        }

        #grade-table tbody tr:nth-child(odd) {
            background: rgba(59, 130, 244, 0.02);
        }

        #grade-table tbody tr {
            transition: all 0.2s ease;
        }

        #grade-table tbody tr:hover {
            background: var(--color-primary-ghost);
        }

        /* 成績顏色 */
        .text-success {
            color: var(--color-success) !important;
            font-weight: var(--weight-bold);
        }

        .text-danger {
            color: var(--color-danger) !important;
            font-weight: var(--weight-bold);
        }

        .text-muted {
            color: var(--color-text-tertiary) !important;
            font-style: italic;
        }

        /* 動畫 */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* 響應式設計 */
        @media (max-width: 768px) {
            body {
                padding: 20px 12px;
            }

            .page-title {
                font-size: 1.6rem;
            }

            .nav-card {
                padding: 20px;
            }

            .nav-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-actions {
                width: 100%;
            }

            .btn-modern {
                flex: 1;
                justify-content: center;
                font-size: 0.85rem;
                padding: 10px 14px;
            }

            .table-card-header {
                padding: 20px;
            }
        }
    </style>

    <!-- ✅ JS 套件：jQuery + Bootstrap + Bootstrap Table + Export 對應版本 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/bootstrap-table.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/extensions/export/bootstrap-table-export.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.24/tableExport.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/locale/bootstrap-table-zh-TW.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
</head>

<body>
    <div class="app-container">
        <!-- 頁面標題 -->
        <div class="page-header">
            <h1 class="page-title">六和高中 國三成績系統</h1>
            <p class="page-subtitle">成績總覽</p>
        </div>

        <!-- 導航卡片 -->
        <div class="nav-card">
            <div class="nav-content">
                <?php
                $classNum = $_SESSION["classNum"];
                $name = $_SESSION["name"];
                $status = $_SESSION["status"];

                // 取得姓名首字
                $nameInitial = mb_substr($name, 0, 1);
                ?>

                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="user-details">
                        <div class="user-name"><?= $name ?> 老師</div>
                        <div class="user-role">教師身份</div>
                    </div>
                </div>

                <div class="nav-actions">
                    <a href="result.php" class="btn-modern btn-info-modern">
                        <i class="fas fa-arrow-left"></i> 返回主畫面
                    </a>
                    <?php
                    if ($_SESSION["status"] == "teacher" || $_SESSION["status"] == "admin") {
                        echo '<a href="grade_set/index.php" class="btn-modern btn-primary-modern">
                            <i class="fas fa-cog"></i> 成績管理
                        </a>';
                    }
                    if ($_SESSION["status"] == "manage") {
                        echo '<a href="average_show.php?week_ID=' . $week_ID . '" class="btn-modern btn-success-modern">
                            <i class="fas fa-chart-bar"></i> 成績平均/分佈
                        </a>';
                    }
                    ?>
                    <a href="index.php" class="btn-modern btn-danger-modern">
                        <i class="fas fa-sign-out-alt"></i> 登出
                    </a>
                </div>
            </div>
        </div>

        <!-- 表格卡片 -->
        <div class="table-card">
            <div class="table-card-header">
                <div id="toolbar">
                    <div class="toolbar-badge">
                        <i class="fas fa-file-alt"></i>
                        目前成績：<?= $testname ?>
                    </div>
                </div>
            </div>

            <div class="table-wrapper">

                <table id="grade-table" data-toggle="table" data-search="true" data-pagination="true"
                    data-page-size="10" data-show-columns="true" data-fixed-columns="true" data-fixed-number="3"
                    data-show-export="true" data-export-types='["excel"]'
                    data-export-options='{"fileName": "<?= $name . "-" . $classNum . "-" . $testname ?>-成績總表"}'
                    data-export-data-type="all" data-toolbar="#toolbar" class="table table-hover">
                    <thead>
                        <tr>
                            <th data-field="classNum" data-sortable="true">班級</th>
                            <th data-field="seatNum" data-sortable="true">座號</th>
                            <th data-field="name" data-sortable="true">姓名</th>
                            <!--
                            簽名功能
                            <th data-field="parent" data-sortable="false" data-visible="false">家長</th>
                            -->
                            <?php
                            // 先抓成績欄位
                            $grades = [];
                            $sqlgrade_Numrows = "SELECT * FROM `junior3_grade_set` WHERE week_ID = '$week_ID' ORDER BY test_ID ASC";
                            $resultgrade_Numrows = $conn->query($sqlgrade_Numrows);
                            while ($row = $resultgrade_Numrows->fetch_assoc()) {
                                $grades[] = ["id" => $row["test_ID"], "name" => $row["test_name"]];
                                $test_date = $row['test_date'];
                                if ($test_date == '0000-00-00') {
                                    $set_test_date = '';
                                } else {
                                    $set_test_date = (new DateTime($test_date))->format('m/d') . ' ';
                                }

                                // 動態產生成績欄位
                                echo "<th data-field='grade_{$row['test_ID']}' data-sortable='true'>{$set_test_date}{$row['test_name']}</th>";
                            }
                            ?>
                            <th data-field="SUM" data-sortable="true">總分</th>
                            <th data-field="ave" data-sortable="true">平均</th>
                            <th data-field="classRank" data-sortable="true">班排</th>
                            <th data-field="gradeRankPer" data-sortable="true">校排百分比</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($_SESSION["status"] == "teacher") {
                            $sqlsearch = "SELECT * FROM `$week_ID` WHERE classNum = '$classNum'";
                        } else if ($_SESSION["status"] == "manage" || $_SESSION["status"] == "admin" || $_SESSION["status"] == "adminteacher") {
                            $sqlsearch = "SELECT * FROM `$week_ID`";
                        } else {
                            echo "<script>alert('權限不足');</script>";
                            echo "<script>window.location.href = 'result.php';</script>";
                            exit();
                        }

                        $result_search = $conn->query($sqlsearch);

                        while ($row_search = $result_search->fetch_assoc()) {
                            if ($row_search['name'] == '空')
                                continue;

                            echo "<tr>";
                            echo "<td>{$row_search['classNum']}</td>";
                            echo "<td>{$row_search['seatNum']}</td>";
                            echo "<td>{$row_search['name']}</td>";

                            /*
                            簽名功能(隱藏中)

                            $sign = $row_search["sign"];
                            if ($sign == "") {
                                echo "<td><span class='text-danger'><b>未簽名</b></span></td>";
                            } else {
                                echo "<td><a href='signpic/$sign' target='_blank'><img src='signpic/$sign' alt='簽名' width='120' height='40'></a></td>";
                            }

                            */

                            // 動態輸出成績欄位，key對應thead data-field
                            foreach ($grades as $grade) {
                                echo "<td>{$row_search[$grade['id']]}</td>";
                            }

                            echo "<td>{$row_search['SUM']}</td>";
                            echo "<td>{$row_search['ave']}</td>";
                            echo "<td>{$row_search['classRank']}</td>";
                            echo "<td>{$row_search['gradeRankPer']}</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>