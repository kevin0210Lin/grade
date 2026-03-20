<?php
require_once("../set.php");

if (isset($_SESSION['login_check'])) {
    if ($_SESSION['login_check'] != "T") {
        echo "<script>alert('您尚未登入');</script>";
        echo "<script>window.location.href = '../index.php';</script>";
    }
} else {
    echo "<script>alert('您尚未登入');</script>";
    echo "<script>window.location.href = '../index.php';</script>";
}
date_default_timezone_set('Asia/Taipei');

if ($_SESSION["status"] != "manage" && $_SESSION["status"] != "admin") {
    echo "<script>alert('您的權限不足');</script>";
    echo "<script>window.location.href = './';</script>";
    exit;
}
?>

<!doctype HTML>
<html lang="zh-Hant">

<head>
    <title>單次成績報表 - 六和高中國三成績系統</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="tc-shared-styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Microsoft JhengHei', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .page-wrapper {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* 頂部導航卡片 */
        .top-nav-card {
            background: white;
            border-radius: 16px;
            padding: 25px 30px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #3b82f6;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title i {
            font-size: 1.6rem;
        }

        .user-info {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #555;
            font-weight: 500;
            margin-right: 15px;
            padding: 8px 15px;
            background: rgba(59, 130, 246, 0.08);
            border-radius: 20px;
        }

        .user-info i {
            color: #3b82f6;
        }

        /* 按鈕樣式 */
        .btn-modern {
            padding: 10px 24px;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 5px;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .btn-modern:hover {
            text-decoration: none;
        }

        .btn-logout {
            background: #ef4444;
            color: white;
        }

        .btn-home {
            background: #3b82f6;
            color: white;
        }

        .btn-back {
            background: #10b981;
            color: white;
        }

        /* 表格卡片 */
        .table-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .table-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #3b82f6;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-title i {
            font-size: 1.2rem;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            border-radius: 12px;
        }

        th {
            background: #3b82f6;
            color: white;
            font-weight: 600;
            padding: 15px 10px;
            text-align: center;
            font-size: 0.95rem;
            border: none;
        }

        td {
            padding: 12px 8px;
            text-align: center;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        tbody tr {
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        /* 高亮顯示 */
        td[style*="color:orange"] {
            color: #ff9800 !important;
            font-weight: 700;
        }

        /* 響應式設計 */
        @media (max-width: 768px) {
            .page-wrapper {
                padding: 10px;
            }

            .page-title {
                font-size: 1.4rem;
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-modern {
                padding: 8px 16px;
                font-size: 0.85rem;
                margin: 3px;
            }

            .top-nav-card,
            .table-card {
                padding: 20px;
            }

            table {
                font-size: 0.85rem;
            }

            th, td {
                padding: 10px 5px;
            }
        }

        /* 數值高亮 */
        .highlight-value {
            background: rgba(59, 130, 246, 0.1);
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 700;
            color: #3b82f6;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <!-- 頂部導航 -->
        <div class="top-nav-card">
            <div class="page-title">
                <i class="fas fa-chart-bar"></i>
                單次成績報表
            </div>
            
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span><?= htmlspecialchars($_SESSION['name']) ?></span>
                </div>
                
                <div class="btn-group-modern">

            <?php
            $classNum = $_SESSION["classNum"];
            $name = $_SESSION["name"];
            $week_ID = $_GET["week_ID"];

            echo "<a href='grade.php?week=$week_ID' class='btn-modern btn-back'><i class='fas fa-arrow-left'></i> 回到成績總表</a>";
            echo "<a href='result.php' class='btn-modern btn-home'><i class='fas fa-home'></i> 返回主畫面</a>";
            echo "<a href='index.php' class='btn-modern btn-logout'><i class='fas fa-sign-out-alt'></i> 登出</a>";
            ?>
                </div>
            </div>
        </div>

        <!-- 年級總分平均卡片 -->
        <div class="table-card">
            <div class="table-title">
                <i class="fas fa-trophy"></i>
                年級總分平均
            </div>
            <?php
            $week_ID = $_GET["week_ID"];
            $sql1 = "SELECT * FROM `junior3_week_set` WHERE `week_ID` = '$week_ID'";
            $result1 = $conn->query($sql1);
            $row = $result1->fetch_assoc();
            $week_ave = $row["week_ave"];
            ?>
            <table>
                <tr>
                    <th>年級總分平均</th>
                </tr>
                <tr>
                    <td><span class="highlight-value"><?= $week_ave ?></span></td>
                </tr>
            </table>
        </div>

        <!-- 班級平均卡片 -->
        <div class="table-card">
            <div class="table-title">
                <i class="fas fa-users"></i>
                班級平均
            </div>
            <table>
                <tr>
                    <th colspan="13">班級平均</th>
                </tr>
                <tr>
                    <th>考試科目</th>
                    <th>年級平均</th>
                    <th>901</th>
                    <th>902</th>
                    <th>903</th>
                    <th>904</th>
                    <th>905</th>
                    <th>906</th>
                    <th>907</th>
                    <th>908</th>
                    <th>909</th>
                    <th>910</th>
                    <th>911</th>
                </tr>


                <?php
                $week_ID = $_GET["week_ID"];
                $sql = "SELECT * FROM `junior3_grade_set` WHERE `week_ID` = '$week_ID'";
                $result = $conn->query($sql);
                while ($row0 = $result->fetch_assoc()) {
                    $test_name = $row0["test_name"];
                    $test_ave = $row0["average"];

                    echo "<tr><td>$test_name</td><td>$test_ave</td>";

                    for ($i = 901; $i <= 911; $i++) {
                        $class_ave = $row0["$i"];
                        if ($class_ave == "") {
                            $class_ave = "尚未登記";
                        }
                        echo "<td>$class_ave</td>";
                    }
                    echo "</tr>";
                }

                ?>
            </table>
        </div>

        <!-- 成績分布檢視卡片 -->
        <div class="table-card">
            <div class="table-title">
                <i class="fas fa-chart-pie"></i>
                成績分布檢視
            </div>
            <table>
                <tr>
                    <th colspan="12">成績分布檢視</th>
                </tr>
                <tr>
                    <th>校排分布</th>
                    <th>1%~5%</th>
                    <th>6%~10%</th>
                    <th>11%~20%</th>
                    <th>21%~30%</th>
                    <th>31%~40%</th>
                    <th>41%~50%</th>
                    <th>51%~60%</th>
                    <th>61%~70%</th>
                    <th>71%~85%</th>
                    <th>再加油</th>
                    <th>有效人數</th>
                </tr>
                <?php

                $grade_insert_check = $row["grade_insert_check"];
                if ($grade_insert_check == "0") {
                    echo "<tr><td colspan='12'>成績尚未登記完畢</td></tr>";
                } else {
                    for ($i = 901; $i <= 911; $i++) {
                        $class_distributions[$i] = [
                            '1%~5%' => 0,
                            '6%~10%' => 0,
                            '11%~20%' => 0,
                            '21%~30%' => 0,
                            '31%~40%' => 0,
                            '41%~50%' => 0,
                            '51%~60%' => 0,
                            '61%~70%' => 0,
                            '71%~85%' => 0,
                            '再加油' => 0,
                            '有效人數' => 0,
                        ];
                        $sql = "SELECT * FROM `$week_ID` WHERE `classNum` = '$i' AND `name` != '空'";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            $percentage = $row["gradeRankPer"];
                            if ($percentage != "") {
                                if ($percentage === "再加油") {
                                    $class_distributions[$i]['再加油']++;
                                    $class_distributions[$i]['有效人數']++;
                                } else if ($percentage == "0") {

                                } else {
                                    $class_distributions[$i]['有效人數']++;
                                    $percentage = rtrim($percentage, '%');
                                    if ($percentage >= 1 && $percentage <= 5) {
                                        $class_distributions[$i]['1%~5%']++;
                                    } elseif ($percentage >= 6 && $percentage <= 10) {
                                        $class_distributions[$i]['6%~10%']++;
                                    } elseif ($percentage >= 11 && $percentage <= 20) {
                                        $class_distributions[$i]['11%~20%']++;
                                    } elseif ($percentage >= 21 && $percentage <= 30) {
                                        $class_distributions[$i]['21%~30%']++;
                                    } elseif ($percentage >= 31 && $percentage <= 40) {
                                        $class_distributions[$i]['31%~40%']++;
                                    } elseif ($percentage >= 41 && $percentage <= 50) {
                                        $class_distributions[$i]['41%~50%']++;
                                    } elseif ($percentage >= 51 && $percentage <= 60) {
                                        $class_distributions[$i]['51%~60%']++;
                                    } elseif ($percentage >= 61 && $percentage <= 70) {
                                        $class_distributions[$i]['61%~70%']++;
                                    } elseif ($percentage >= 71 && $percentage <= 85) {
                                        $class_distributions[$i]['71%~85%']++;
                                    }
                                }
                            }
                        }
                    }
                    for ($i = 901; $i <= 911; $i++) {
                        echo "<tr>";
                        echo "<td>$i</td>";
                        foreach ($class_distributions[$i] as $range => $count) {
                            if ($count >= 10) {
                                echo "<td style='color:orange'>$count</td>";
                            } else {
                                echo "<td>$count</td>";
                            }
                        }
                        echo "</tr>";
                    }
                }
                ?>

            </table>
        </div>
    </div>
</body>

</html>