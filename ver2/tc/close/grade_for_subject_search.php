<?php
require_once("../set.php");

// 1. 驗證登入狀態
if (!isset($_SESSION['login_check']) || $_SESSION['login_check'] !== 'T') {
    // 使用 header 跳轉，比 JavaScript 更乾淨
    header("Location: index.php");
    exit();
}

// 2. 驗證並取得輸入參數
$subject = filter_input(INPUT_GET, 'grade_search_subject', FILTER_VALIDATE_INT);
if ($subject === false || $subject === null) {
    die("無效的科目參數");
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

// 3. 根據科目設定查詢條件（老師與管理員邏輯略有不同，但查詢 grade_set 資料是一致的）
if (in_array($subject, [4, 5, 6])) {
    $gradeSetSql = "SELECT * FROM `junior3_grade_set` WHERE (`subject` = ? OR `subject` = '社會') ORDER BY `ID` DESC";
} elseif (in_array($subject, [7, 8, 9])) {
    $gradeSetSql = "SELECT * FROM `junior3_grade_set` WHERE (`subject` = ? OR `subject` = '自然') ORDER BY `ID` DESC";
} else {
    $gradeSetSql = "SELECT * FROM `junior3_grade_set` WHERE `subject` = ? ORDER BY `ID` DESC";
}

// 使用預處理語句查詢 grade_set 資料
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

// 4. 取得學生資料：
//    (a) 若為老師，僅取得自己所屬班級；
//    (b) 管理員則取得所有學生資料。
$students = [];
if ($status === "subjectteacher" || $status === "teacher") {
    // 先從科目專屬資料表取得老師對應的班級（欄位名稱依科目而定，如 subject4）
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

    // 一次查詢出所有屬於這些班級的學生（利用 IN 子句）
    $placeholders = implode(',', array_fill(0, count($classNumbers), '?'));
    $types = str_repeat('s', count($classNumbers));
    $sql = "SELECT classNum, seatNum, name FROM `junior3_login` WHERE classNum IN ($placeholders) ORDER BY `set_ID` ASC";
    $stmt = $conn->prepare($sql);
    // 使用展開運算子動態綁定參數
    $stmt->bind_param($types, ...$classNumbers);
    $stmt->execute();
    $result3 = $stmt->get_result();
    while ($row = $result3->fetch_assoc()) {
        $students[] = $row;
    }
    $stmt->close();
} elseif (in_array($status, ["admin", "adminteacher", "manage"])) {
    $sql = "SELECT classNum, seatNum, name FROM `junior3_login` ORDER BY `set_ID` ASC";
    $result3 = $conn->query($sql);
    while ($row = $result3->fetch_assoc()) {
        $students[] = $row;
    }
}

if (empty($students)) {
    echo "沒有學生資料";
    exit();
}
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>考試成績</title>
    <link rel="stylesheet" href="../tc-shared-styles.css">
    <link href="/set/style.css" rel="stylesheet">
    <link rel="icon" href="" type="image/x-icon">
    <!-- 引入 Bootstrap Table 與 Sticky Header CSS/JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/bootstrap-table.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/extensions/sticky-header/bootstrap-table-sticky-header.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/bootstrap-table.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/extensions/sticky-header/bootstrap-table-sticky-header.min.js"></script>
    <style>
        .table-container {
            width: 100%;
            overflow: auto;
            position: relative;
            display: flex;
            flex-direction: column;
            padding-top: 15px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            min-width: max-content;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 8px;
            white-space: nowrap;
        }

        .sticky-header th {
            background-color: #f4f4f4;
        }

        .sticky-left {
            position: sticky;
            left: 0;
            z-index: 1;
            background-color: #fff;
        }

        .sticky-top-left {
            position: sticky;
            top: 0;
            left: 0;
            z-index: 2;
            background-color: #fff;
        }

        .w-200 {
            width: 200px;
        }

        .w-80 {
            width: 80px;
        }

        .td-2 {
            background-color: #ECECFF;
        }
    </style>
</head>

<body>
    <div class="table-container">
        <table data-toggle="table" data-sticky-header="true" data-sticky-column="true"
            class="table table-bordered table-striped sticky-header">
            <thead>
                <tr>
                    <th class="sticky-top-left w-200">班級/座號<br>姓名</th>
                    <?php foreach ($students as $stu): ?>
                        <th class="sticky-left w-80">
                            <?= htmlspecialchars($stu['classNum'] . $stu['seatNum'] . " <br>" . $stu['name']); ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // 5. 輸出每筆考試資料的成績
                // 為了避免 N+1 問題，對於每筆考試，使用一次查詢抓出所有相關班級的成績
                foreach ($gradeSets as $gradeSet) {
                    $weekID = $gradeSet["week_ID"];
                    $testID = $gradeSet["test_ID"];
                    $testName = $gradeSet["test_name"];

                    echo "<tr>";
                    echo "<td class='sticky-left td-2'>" . htmlspecialchars($testName) . "</td>";

                    // 驗證 weekID 格式（僅允許英數字及底線）以避免 SQL 注入
                    if (!preg_match('/^[a-zA-Z0-9_]+$/', $weekID)) {
                        die("無效的 week_ID");
                    }

                    // 收集所有學生的班級編號
                    $classNums = array_map(function ($stu) {
                        return $stu['classNum'];
                    }, $students);
                    $placeholders = implode(',', array_fill(0, count($classNums), '?'));
                    $types = str_repeat('s', count($classNums));
                    $sqlGrades = "SELECT classNum, `$testID` AS grade FROM `$weekID` WHERE classNum IN ($placeholders)";
                    $stmt = $conn->prepare($sqlGrades);
                    $stmt->bind_param($types, ...$classNums);
                    $stmt->execute();
                    $resultGrades = $stmt->get_result();
                    $gradeMapping = [];
                    while ($row = $resultGrades->fetch_assoc()) {
                        $gradeMapping[$row["classNum"]] = $row["grade"];
                    }
                    $stmt->close();

                    // 輸出各班的成績（依照表頭順序）
                    foreach ($students as $stu) {
                        $grade = isset($gradeMapping[$stu["classNum"]]) ? $gradeMapping[$stu["classNum"]] : "";
                        $gradeshow = ($grade === "" || is_null($grade)) ? "未登分" : htmlspecialchars($grade) . "分";
                        echo "<td>$gradeshow</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>