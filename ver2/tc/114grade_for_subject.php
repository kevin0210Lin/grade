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
    echo "無考試內容";
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
    <title>單科成績總表</title>
    <!-- ✅ 統一設計系統 -->
    <link rel="stylesheet" href="tc-shared-styles.css">
    <!-- ✅ 自訂樣式 -->
    <link href="/set/style.css" rel="stylesheet">

    <!-- ✅ 原本 Bootstrap 4.5.2 + Icons 1.10.4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">

    <!-- ✅ Bootstrap Table CSS 對應版本 1.24.0 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/bootstrap-table.min.css">


    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .container {
            max-width: 95%;
            min-width: 700px;
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
    <!-- 加入 jsPDF 和 jsPDF AutoTable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

    <!-- 🔧 加在 bootstrap-icons 或 style.css 之前都可以 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="container">
        <h3 class="mb-4 font-weight-bold">六和高中 國三成績系統 單科成績總表</h3>

        <!-- ✅ 工具按鈕區 -->
        <div class="mb-3 text-center">
            <span class="mr-2 font-weight-bold text-primary">
                <?= htmlspecialchars($_SESSION['name']) ?> 您好
            </span>
            <a href='index.php' class='btn btn-danger btn-user'>登出</a>
            <a href='result.php' class='btn btn-info btn-user'>返回主畫面</a>
            <?php if (in_array($_SESSION['status'], ['teacher', 'admin'])): ?>
                <a href='grade_set/index.php' class='btn btn-primary btn-user'>成績管理</a>
            <?php endif; ?>
        </div>

        <!-- ✅ 表格 -->
        <table id="grade-table" class="table table-bordered table-hover" data-toggle="table" data-search="true"
            data-pagination="true" data-page-size="25" data-show-columns="true" data-show-export="true"
            data-export-types='["excel", "csv", "pdf"]' data-export-options='{"fileName": "<?= $subjectzh ?>-單科成績總表"}'
            data-export-data-type="all" data-locale="zh-TW">

            <thead>
                <tr>
                    <th data-field="stu" data-sortable="true">班級/座號/姓名</th>
                    <th data-field="ave" data-sortable="true">平均</th>
                    <?php
                    $n = 0;
                    foreach ($gradeSets as $gradeSet):
                        $n++;
                        ?>
                        <th data-field="grade<?= $n ?>" data-sortable="true"><?= htmlspecialchars($gradeSet['test_name']) ?>
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