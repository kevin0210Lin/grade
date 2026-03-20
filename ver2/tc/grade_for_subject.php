<?php
require_once("../set.php");

// 1. 驗證登入狀態
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

// 2. 驗證並取得輸入參數
if (isset($_GET["subject"])) {
    $subject = $_GET["subject"];
} else {
    echo "<script>alert('執行錯誤');</script>";
    echo "<script>window.location.href = 'result.php';</script>";
    exit();
}

$subjects = [
    1 => '國文',
    2 => '數學',
    3 => '英文',
    4 => '地理',
    5 => '歷史',
    6 => '公民',
    7 => '生物',
    8 => '理化',
    9 => '地科'
];

if (!array_key_exists($subject, $subjects)) {
    die("科目不存在");
}
$subjectzh = $subjects[$subject];
$status = $_SESSION["status"];
$name = $_SESSION["name"];

// 3. 查詢考試項目
if (in_array($subject, [4, 5, 6])) {
    $gradeSetSql = "SELECT * FROM `junior3_grade_set` WHERE (`subject` = ? OR `subject` = '社會') ORDER BY `ID` DESC";
} elseif (in_array($subject, [7, 8, 9])) {
    $gradeSetSql = "SELECT * FROM `junior3_grade_set` WHERE (`subject` = ? OR `subject` = '自然') ORDER BY `ID` DESC";
} else {
    $gradeSetSql = "SELECT * FROM `junior3_grade_set` WHERE `subject` = ? ORDER BY `ID` DESC";
}

$stmt = $conn->prepare($gradeSetSql);
$stmt->bind_param("s", $subjectzh);
$stmt->execute();
$result1 = $stmt->get_result();
$gradeSets = $result1->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($gradeSets)) {
    echo "無考試內容 將自動跳轉返回首頁";
    header("refresh:2;url=result.php"); // 3秒后跳转到目标URL
    exit();
}

// 4. 查詢學生資料
$students = [];
if ($status === "subjectteacher" || $status === "teacher") {
    $colName = "subject" . $subject;
    $stmt = $conn->prepare("SELECT classNum FROM `junior3_subjecttc` WHERE `$colName` = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result2 = $stmt->get_result();
    $classNumbers = [];
    while ($row = $result2->fetch_assoc()) {
        $classNumbers[] = $row['classNum'];
    }
    $stmt->close();

    if (empty($classNumbers)) {
        echo "沒有對應班級";
        exit();
    }

    $placeholders = implode(',', array_fill(0, count($classNumbers), '?'));
    $types = str_repeat('s', count($classNumbers));
    $sql = "SELECT classNum, seatNum, name FROM `junior3_login` WHERE classNum IN ($placeholders) ORDER BY `set_ID` ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$classNumbers);
    $stmt->execute();
    $result3 = $stmt->get_result();
    while ($row = $result3->fetch_assoc()) {
        $students[] = $row;
    }
    $stmt->close();
} elseif (in_array($status, ["admin", "adminteacher", "manage"])) {
    $sql = "SELECT classNum, seatNum, name FROM `junior3_login` WHERE `name` != '空' ORDER BY `set_ID` ASC";
    $result3 = $conn->query($sql);
    while ($row = $result3->fetch_assoc()) {
        $students[] = $row;
    }
}

if (empty($students)) {
    echo "沒有學生資料";
    exit();
}

// 5. 一次查詢所有成績資料
$grades = [];
foreach ($gradeSets as $gradeSet) {
    $weekID = $gradeSet["week_ID"];
    $testID = $gradeSet["test_ID"];

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $weekID)) {
        die("無效的 week_ID");
    }

    $sql = "SELECT classNum, seatNum, `$testID` AS grade FROM `$weekID`";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $key = $row["classNum"] . "-" . $row["seatNum"];
        $grades[$key][$testID] = $row["grade"];
    }
}

// 輔助函數：成績顏色判斷
function gradeColor($grade)
{
    if (!is_numeric($grade))
        return '';
    if ($grade >= 90)
        return 'text-success font-weight-bold'; // 高分綠色
    elseif ($grade >= 60)
        return '';              // 及格藍色
    else
        return 'text-danger font-weight-bold';              // 低分紅色
}
?>


<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>單科成績總表 - <?= $subjectzh ?></title>
    <link rel="stylesheet" href="tc-shared-styles.css">
    
    <!-- Bootstrap 4.5.2 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    
    <!-- Bootstrap Table -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/bootstrap-table.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, "微軟正黑體", "Microsoft JhengHei", sans-serif;
            background: radial-gradient(circle at 18% 20%, rgba(59, 130, 244, 0.08), transparent 32%),
                        radial-gradient(circle at 82% 0%, rgba(14, 165, 233, 0.07), transparent 28%),
                        var(--color-bg-muted);
            min-height: 100vh;
            padding: var(--space-2xl) var(--space-lg);
            color: var(--color-text-secondary);
            line-height: 1.6;
        }

        .page-wrapper {
            max-width: 1280px;
            margin: 0 auto;
        }

        .top-nav-card {
            background: linear-gradient(135deg, #ffffff, #f9fbff);
            border-radius: var(--radius-lg);
            padding: var(--space-2xl);
            margin-bottom: var(--space-xl);
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.08);
            border: 1px solid var(--color-border);
            transition: all 0.2s ease;
        }

        .top-nav-card:hover {
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.12);
            border-color: var(--color-primary-ghost);
        }

        .page-title {
            font-size: var(--text-title-page);
            font-weight: var(--weight-bold);
            color: var(--color-text);
            margin-bottom: var(--space-lg);
            display: flex;
            align-items: center;
            gap: var(--space-md);
            flex-wrap: wrap;
        }

        .page-title i {
            color: var(--color-text-tertiary);
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .page-title:hover i {
            color: var(--color-primary);
            transform: scale(1.08);
        }

        .subject-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--space-sm);
            background: var(--color-primary-ghost);
            color: var(--color-primary);
            padding: var(--space-sm) var(--space-lg);
            border-radius: 999px;
            font-size: var(--text-body);
            font-weight: var(--weight-bold);
            border: 1px solid var(--color-primary-ghost);
        }

        .user-info {
            display: inline-flex;
            align-items: center;
            gap: var(--space-sm);
            color: var(--color-text-secondary);
            font-weight: var(--weight-medium);
            padding: var(--space-sm) var(--space-lg);
            background: var(--color-bg-light);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-border);
            font-size: var(--text-body);
        }

        .user-info i {
            color: var(--color-text-tertiary);
        }

        .btn-modern {
            padding: 11px 20px;
            border-radius: var(--radius-md);
            font-weight: var(--weight-semibold);
            border: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: var(--space-sm);
            text-decoration: none;
            font-size: var(--text-body);
            white-space: nowrap;
            line-height: 1.4;
            min-height: 42px;
            cursor: pointer;
        }

        .btn-modern:hover {
            text-decoration: none;
            transform: scale(0.98);
        }

        .btn-modern:active {
            transform: scale(0.96);
        }

        .btn-modern:focus-visible {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
            box-shadow: 0 0 0 4px var(--color-primary-ghost);
        }

        .btn-logout {
            background: transparent;
            color: var(--color-danger);
            border: 1px solid var(--color-danger);
        }

        .btn-logout:hover {
            background: var(--color-danger);
            color: #ffffff;
        }

        .btn-home {
            background: var(--color-info);
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(14, 165, 233, 0.2);
        }

        .btn-home:hover {
            background: var(--color-info-hover);
            box-shadow: 0 2px 8px rgba(14, 165, 233, 0.3);
        }

        .btn-manage {
            background: var(--color-success);
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(22, 163, 74, 0.2);
        }

        .btn-manage:hover {
            background: var(--color-success-hover);
            box-shadow: 0 2px 8px rgba(22, 163, 74, 0.3);
        }

        .btn-group-modern {
            display: flex;
            gap: var(--space-md);
            flex-wrap: wrap;
        }

        /* 表格卡片 */
        .table-card {
            background: var(--color-bg);
            border-radius: var(--radius-lg);
            padding: var(--space-2xl);
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
            border: 1px solid var(--color-border);
            transition: all 0.2s ease;
        }

        .table-card:hover {
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.12);
            border-color: var(--color-primary-ghost);
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

        /* Scrollbar Customization */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--color-bg-light);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--color-border);
            border-radius: 4px;
            transition: background 0.2s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-primary);
        }

        /* 響應式設計 */
        @media (max-width: 768px) {
            body {
                padding: var(--space-lg);
            }

            .page-title {
                font-size: var(--text-title-section);
                flex-direction: column;
                align-items: flex-start;
            }

            .subject-badge {
                margin-left: 0;
            }

            .btn-modern {
                padding: var(--space-md) var(--space-lg);
                font-size: var(--text-caption);
            }

            .top-nav-card, .table-card {
                padding: var(--space-xl);
            }

            .user-info {
                width: 100%;
                justify-content: center;
                margin-bottom: var(--space-md);
            }

            .btn-group-modern {
                width: 100%;
                flex-direction: column;
            }

            .btn-modern {
                width: 100%;
                justify-content: center;
            }
        }

        /* 載入動畫 */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid var(--color-border);
            border-radius: 50%;
            border-top-color: var(--color-primary);
            border-right-color: var(--color-primary);
            animation: spin 0.8s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <!-- JS 套件 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/bootstrap-table.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/extensions/export/bootstrap-table-export.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.24/tableExport.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/locale/bootstrap-table-zh-TW.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
</head>

<body>
    <div class="page-wrapper">
        <!-- 頂部導航 -->
        <div class="top-nav-card">
            <div class="page-title">
                <i class="fas fa-chart-line"></i>
                單科成績總表
                <span class="subject-badge"><?= htmlspecialchars($subjectzh) ?></span>
            </div>
            
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span><?= htmlspecialchars($_SESSION['name']) ?></span>
                </div>
                
                <div class="btn-group-modern">
                    <?php if (in_array($_SESSION['status'], ['teacher', 'admin', 'manage'])): ?>
                        <a href='grade_set/index.php' class='btn-modern btn-manage'>
                            <i class="fas fa-cog"></i> 成績管理
                        </a>
                    <?php endif; ?>
                    <a href='result.php' class='btn-modern btn-home'>
                        <i class="fas fa-home"></i> 返回主畫面
                    </a>
                    <a href='index.php' class='btn-modern btn-logout'>
                        <i class="fas fa-sign-out-alt"></i> 登出
                    </a>
                </div>
            </div>
        </div>

        <!-- 表格卡片 -->
        <div class="table-card">
            <table id="grade-table" class="table table-bordered table-hover" 
                data-toggle="table" 
                data-search="true"
                data-pagination="true" 
                data-page-size="10" 
                data-show-columns="true" 
                data-show-export="true"
                data-export-types='["excel"]' 
                data-export-options='{"fileName": "<?= $subjectzh ?>-單科成績總表"}'
                data-export-data-type="all" 
                data-locale="zh-TW">

            <thead>
                <tr>
                    <th data-field="stu" data-sortable="true">班級/座號/姓名</th>
                    <th data-field="ave" data-sortable="true">平均</th>
                    <?php
                    $n = 0;
                    foreach ($gradeSets as $gradeSet):
                        $n++;
                        $test_date = $gradeSet['test_date'];
                        if ($test_date == '0000-00-00') {
                            $set_test_date = '';
                        } else {
                            $set_test_date = (new DateTime($test_date))->format('m/d') . ' ';
                        }
                        ?>
                        <th data-field="grade<?= $n ?>" data-sortable="true">
                            <?= htmlspecialchars($set_test_date) . htmlspecialchars($gradeSet['test_name']) ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($students as $stu):
                    $key = $stu['classNum'] . '-' . $stu['seatNum'];
                    // 計算學生平均分
                    $total = 0;
                    $count = 0;
                    foreach ($gradeSets as $gradeSet) {
                        $testID = $gradeSet['test_ID'];
                        if (isset($grades[$key][$testID]) && is_numeric($grades[$key][$testID])) {
                            $total += $grades[$key][$testID];
                            $count++;
                        }
                    }
                    $avg = $count ? round($total / $count, 1) : '-';
                    ?>
                    <tr>
                        <td><?= htmlspecialchars("{$stu['classNum']}{$stu['seatNum']} {$stu['name']}") ?></td>
                        <td><?= $avg === '-' ? '-' : $avg . '分' ?></td>
                        <?php foreach ($gradeSets as $gradeSet):
                            $testID = $gradeSet['test_ID'];
                            $grade = $grades[$key][$testID] ?? '';
                            if (is_numeric($grade)) {
                                $colorClass = gradeColor($grade);
                                echo "<td class='$colorClass'>" . htmlspecialchars($grade) . "分</td>";
                            } else {
                                echo '<td><span class="text-muted">未登分</span></td>';
                            }
                        endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>