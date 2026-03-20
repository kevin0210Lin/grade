<?php
require_once("../set.php");

if (isset($_SESSION['login_check'])) {
    if ($_SESSION['login_check'] != "T") {
        echo "<script>alert('您尚未登入');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }
} else {
    echo "<script>alert('您尚未登入');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}

$classNum = $_SESSION["classNum"];
$name = $_SESSION["name"];
$status = $_SESSION["status"];

$subjects = [
    1 => '國文',
    2 => '數學',
    3 => '英文',
    4 => '地理',
    5 => '歷史',
    6 => '公民',
    7 => '生物',
    8 => '理化',
    9 => '地科',
];

// 統計資訊
$sql_week = "SELECT * FROM `junior3_week_set` ORDER BY `week_ID` DESC";
$result_week = $conn->query($sql_week);
$totalWeeks = ($result_week && $result_week->num_rows > 0) ? $result_week->num_rows : 0;

$sql_open_week = "SELECT * FROM `junior3_week_set` WHERE grade_insert_check = '1' ORDER BY `week_ID` DESC";
$result_open_week = $conn->query($sql_open_week);
$openWeeks = ($result_open_week && $result_open_week->num_rows > 0) ? $result_open_week->num_rows : 0;

// 計算任教科目數量
$teachingSubjects = 0;
if ($status != "admin" && $status != "manage" && $status != "adminteacher") {
    $sql = "SELECT * FROM `junior3_login_tc` WHERE `name` = '$name'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        for ($i = 1; $i <= 9; $i++) {
            if ($row["subject$i"] == 1) {
                $teachingSubjects++;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>教師成績管理系統</title>
    <link rel="icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="tc-shared-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* 為了向後相容，映射舊變數到新變數 */
        :root {
            --navy: var(--color-text);
            --accent: var(--color-primary);
            --accent-dark: var(--color-primary-hover);
            --muted: var(--color-text-tertiary);
            --card: var(--color-bg);
            --border: var(--color-border);
            --text: var(--color-text-secondary);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 20px 56px;
        }

        .topbar {
            background: white;
            padding: 24px 20px;
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            border: 2px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            text-align: center;
            margin-bottom: 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .brand-text h1 {
            margin: 6px 0 8px 0;
            font-size: 24px;
            color: var(--color-text);
            letter-spacing: -0.4px;
            line-height: 1.3;
            font-weight: 800;
        }

        .brand-text .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            background: var(--color-primary-ghost);
            color: var(--color-primary);
            font-size: 12px;
            letter-spacing: 0.6px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0;
            border: 2px solid rgba(59, 130, 244, 0.2);
        }

        .topbar-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            width: 100%;
            z-index: 1;
        }

        .user-chip {
            background: var(--color-primary-ghost);
            border: 2px solid rgba(59, 130, 244, 0.2);
            border-radius: 12px;
            padding: 12px 16px;
            color: var(--color-text);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-chip i {
            color: var(--color-primary);
            font-size: 16px;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* 按鈕樣式由 tc-shared-styles.css 統一管理 */

        .stats-card {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            margin-bottom: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            padding: 20px;
        }

        .stats-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
        }

        .stats-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stats-icon.blue {
            background: rgba(59, 130, 244, 0.1);
            color: var(--accent);
        }

        .stats-icon.green {
            background: rgba(16, 185, 129, 0.1);
            color: var(--positive);
        }

        .stats-icon.orange {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .stats-content {
            flex: 1;
        }

        .stats-label {
            margin: 0;
            font-size: 11px;
            color: var(--muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .stats-number {
            margin: 6px 0 0 0;
            font-size: 26px;
            font-weight: 900;
            color: var(--navy);
            line-height: 1.1;
            display: flex;
            align-items: baseline;
            gap: 4px;
        }

        .stats-unit {
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
        }

        .card {
            background: var(--card);
            border: 2px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 20px;
            border-bottom: 2px solid var(--border);
            gap: 20px;
            background: #f8fafc;
        }

        .card-header h2 {
            margin: 0;
            color: var(--navy);
            font-size: 20px;
            line-height: 1.4;
            font-weight: 900;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header h2 i {
            color: var(--accent);
            font-size: 22px;
        }

        .card-body {
            padding: 0;
            max-height: calc(100vh - 320px);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .card-body::-webkit-scrollbar {
            width: 8px;
        }

        .card-body::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .card-body::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .card-body::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .subject-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
            padding: 20px;
        }

        .subject-card {
            background: rgba(59, 130, 244, 0.08);
            border: 2px solid rgba(59, 130, 244, 0.15);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .subject-card:hover {
            background: rgba(59, 130, 244, 0.12);
        }

        .subject-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: var(--accent);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
        }

        .subject-name {
            color: var(--navy);
            font-size: 15px;
            font-weight: 800;
            margin: 0;
        }

        .teaching-table {
            width: auto;
            border-collapse: collapse;
            border: 2px solid var(--border);
            margin: 15px;
        }

        .teaching-table thead {
            background: rgba(59, 130, 244, 0.08);
        }

        .teaching-table th {
            padding: 16px;
            text-align: left;
            font-size: 13px;
            font-weight: 800;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 3px solid var(--border);
        }

        .teaching-table td {
            padding: 16px;
            border-bottom: 2px solid var(--border);
            font-size: 14px;
            color: var(--text);
        }

        .teaching-table tr:hover {
            background: rgba(59, 130, 244, 0.03);
        }

        .teaching-table tr:last-child td {
            border-bottom: none;
        }

        .week-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            padding: 20px;
        }

        .week-card {
            background: var(--card);
            border: 2px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
            display: grid;
            gap: 12px;
        }

        .week-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .week-title {
            font-size: 16px;
            font-weight: 900;
            color: var(--navy);
            line-height: 1.3;
            letter-spacing: -0.2px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .week-title i {
            color: var(--accent);
            font-size: 18px;
        }

        .week-body {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
            display: grid;
            gap: 6px;
        }

        .meta-row {
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .meta-row i {
            color: var(--accent);
            margin-top: 2px;
            flex-shrink: 0;
            font-size: 13px;
        }

        .week-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 4px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 10px;
            padding: 7px 12px;
            font-weight: 700;
            font-size: 12px;
            background: rgba(59, 130, 244, 0.1);
            color: var(--accent);
            border: 2px solid rgba(59, 130, 244, 0.22);
            transition: all 0.25s ease;
            white-space: nowrap;
        }

        .pill.positive {
            background: rgba(16, 185, 129, 0.1);
            color: var(--positive);
            border-color: rgba(16, 185, 129, 0.22);
        }

        .pill.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border-color: rgba(245, 158, 11, 0.22);
        }

        .pill.neutral {
            background: rgba(100, 116, 139, 0.08);
            color: var(--text);
            border-color: rgba(100, 116, 139, 0.15);
        }

        .empty-state {
            padding: 40px 20px;
            text-align: center;
            color: var(--muted);
            font-weight: 600;
            display: grid;
            gap: 12px;
            place-items: center;
        }

        .empty-state .empty-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(59, 130, 244, 0.1);
            color: var(--accent);
            display: grid;
            place-items: center;
            font-size: 24px;
        }

        @media (max-width: 768px) {
            .page {
                padding: 18px 12px 36px;
            }

            .topbar {
                gap: 14px;
                padding: 16px;
                margin-bottom: 18px;
            }

            .brand-text h1 {
                font-size: 20px;
            }

            .user-chip {
                width: 100%;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
                padding: 16px;
            }

            .subject-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                gap: 10px;
                padding: 16px;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
                padding: 14px 16px;
            }

            .teaching-table th,
            .teaching-table td {
                padding: 12px 10px;
                font-size: 13px;
            }

            .week-grid {
                padding: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <!-- 頂部資訊 -->
        <div class="topbar">
            <div class="brand">
                <div class="brand-text">
                    <p class="eyebrow">
                        <i class="fas fa-graduation-cap"></i>
                        Teacher Portal
                    </p>
                    <h1>六和高中 國三成績系統</h1>
                </div>
            </div>

            <div class="topbar-meta">
                <div class="user-chip">
                    <i class="fas fa-user-tie"></i>
                    <strong><?php echo htmlspecialchars($name); ?> 老師</strong>
                </div>

                <div class="actions">
                    <a href="index.php" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i>
                        登出
                    </a>
                </div>
            </div>
        </div>

        <!-- 統計卡片 -->
        <?php if ($status == "admin" || $status == "manage" || $status == "adminteacher" || $status == "teacher") { ?>
            <div class="stats-card">
                <div class="stats-grid">
                    <div class="stats-item">
                        <div class="stats-icon blue">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                        <div class="stats-content">
                            <p class="stats-label">總週次</p>
                            <div class="stats-number"><?php echo $totalWeeks; ?><span class="stats-unit">週</span></div>
                        </div>
                    </div>
                    <div class="stats-item">
                        <div class="stats-icon green">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-content">
                            <p class="stats-label">已開放</p>
                            <div class="stats-number"><?php echo $openWeeks; ?><span class="stats-unit">週</span></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <!-- 管理功能區 (管理員、教師) -->
        <?php if ($status == "admin" || $status == "teacher" || $status == "adminteacher") { ?>
            <div class="card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-tools"></i>
                        管理功能
                    </h2>
                </div>
                <div class="card-body">
                    <div class="subject-grid">
                        <a href="grade_set/index.php" class="subject-card">
                            <div class="subject-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                                <i class="fas fa-edit"></i>
                            </div>
                            <p class="subject-name">成績管理</p>
                        </a>
                        <a href="stu_login_set/" class="subject-card">
                            <div class="subject-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <p class="subject-name">學生登入管理</p>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>

        <!-- 權限管理功能 (管理員) -->
        <?php if ($status == "admin" || $status == "manage" || $status == "adminteacher") { ?>
            <div class="card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-user-shield"></i>
                        權限管理
                    </h2>
                </div>
                <div class="card-body">
                    <div class="subject-grid">
                        <a href="tc_login_set/" class="subject-card">
                            <div class="subject-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                                <i class="fas fa-user-cog"></i>
                            </div>
                            <p class="subject-name">個別教師權限</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 單科成績檢視 -->
            <div class="card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-clipboard-list"></i>
                        單科成績檢視
                    </h2>
                </div>
                <div class="card-body">
                    <div class="subject-grid">
                        <?php for ($i = 1; $i <= 9; $i++) { ?>
                            <a href="grade_for_subject.php?subject=<?php echo $i; ?>" class="subject-card">
                                <div class="subject-icon">
                                    <?php echo mb_substr($subjects[$i], 0, 1, 'UTF-8'); ?>
                                </div>
                                <p class="subject-name"><?php echo $subjects[$i]; ?></p>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <!-- 任教科目 (一般教師) -->
            <div class="card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-chalkboard-teacher"></i>
                        我的任教科目
                    </h2>
                </div>
                <div class="card-body">
                    <?php
                    $sql = "SELECT * FROM `junior3_login_tc` WHERE `name` = '$name'";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $hasSubjects = false;

                        echo '<table class="teaching-table">';
                        echo '<thead><tr><th style="text-align: center;">科目</th><th style="text-align: center;">任教班級</th><th style="text-align: center; width: 120px;">操作</th></tr></thead>';
                        echo '<tbody>';

                        for ($i = 1; $i <= 9; $i++) {
                            if ($row["subject$i"] == 1) {
                                $hasSubjects = true;
                                $sql1 = "SELECT * FROM `junior3_subjecttc` WHERE `subject$i` = '$name'";
                                $result1 = $conn->query($sql1);
                                $subjecttcclass = "";
                                $classCount = 0;

                                while ($row1 = $result1->fetch_assoc()) {
                                    $subjecttcclass .= $row1["classNum"] . "、";
                                    $classCount++;
                                }

                                if ($subjecttcclass != "") {
                                    $subjecttcclass = rtrim($subjecttcclass, "、");
                                }

                                echo '<tr>';
                                echo '<td><strong>' . $subjects[$i] . '</strong></td>';
                                echo '<td>' . ($classCount == 0 ? '<span class="pill neutral">無任教資訊</span>' : $subjecttcclass) . '</td>';
                                echo '<td style="text-align: center;">';
                                if ($classCount == 0) {
                                    echo '<span class="pill warning">無資料</span>';
                                } else {
                                    echo '<a href="grade_for_subject.php?subject=' . $i . '" class="btn btn-primary"><i class="fas fa-eye"></i> 檢視任教班級成績</a>';
                                }
                                echo '</td>';
                                echo '</tr>';
                            }
                        }

                        if (!$hasSubjects) {
                            echo '<tr><td colspan="3">';
                            echo '<div class="empty-state">';
                            echo '<div class="empty-icon"><i class="fas fa-inbox"></i></div>';
                            echo '<div>目前無任教科目資訊</div>';
                            echo '</div>';
                            echo '</td></tr>';
                        }

                        echo '</tbody></table>';
                    } else {
                        echo '<div class="empty-state">';
                        echo '<div class="empty-icon"><i class="fas fa-inbox"></i></div>';
                        echo '<div>目前無任教科目資訊</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        <?php } ?>

        <!-- 週次成績檢視 -->
        <?php if ($status == "admin" || $status == "manage" || $status == "adminteacher" || $status == "teacher") { ?>
            <div class="card">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-calendar-alt"></i>
                        週次成績檢視
                    </h2>
                </div>
                <div class="card-body ">
                    <div class="week-grid">
                        <?php
                        $sql = "SELECT * FROM `junior3_week_set` ORDER BY `week_ID` DESC";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $week_ID = $row["week_ID"];
                                $week_name = $row["week_name"];
                                $last_set_time = $row["last_set_time"];
                                $grade_insert_check = $row["grade_insert_check"];

                                echo '<div class="week-card">';
                                echo '<div class="week-header">';
                                echo '<div class="week-title"><i class="fas fa-calendar-day"></i>' . htmlspecialchars($week_name) . '</div>';

                                if ($grade_insert_check == 1) {
                                    echo '<span class="pill positive"><i class="fas fa-check"></i> 已開放查詢</span>';
                                } else {
                                    echo '<span class="pill neutral"><i class="fas fa-lock"></i> 未開放查詢</span>';
                                }

                                echo '</div>';
                                echo '<div class="week-body">';
                                echo '<div class="meta-row">';
                                echo '<i class="fas fa-clock"></i>';
                                echo '<span>最後更新：' . ($last_set_time ? htmlspecialchars($last_set_time) : '尚無資料') . '</span>';
                                echo '</div>';
                                echo '</div>';
                                echo '<div class="week-actions">';
                                echo '<a href="grade.php?week=' . $week_ID . '&n=' . urlencode($week_name) . '" class="btn btn-primary">';
                                echo '<i class="fas fa-eye"></i> 檢視班級成績';
                                echo '</a>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="empty-state">';
                            echo '<div class="empty-icon"><i class="fas fa-calendar-times"></i></div>';
                            echo '<div>目前尚無週次成績資料</div>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</body>

</html>

<?php
//for grade_set/week.php
$_SESSION["week_show"] = "N";
//for grade_set/grade.php
$_SESSION["grade_show"] = "N";
?>