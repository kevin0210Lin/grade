<?php
require_once("../../set.php");

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

?>

<!doctype HTML>
<html lang="zh-Hant">

<head>
    <title>tc成績管理系統</title>
    <meta charset="UTF-8">
    <link href="/set/style.css" rel="stylesheet">
    <link rel="icon" href="" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <style>
        body {
            background-color: #f8f9fc;
            font-family: '微軟正黑體', Arial, sans-serif;
            height: 100vh;
            width: 100%;
            margin: 0;
            align-items: center;
        }

        .container {
            max-width: 1300px;
            min-width: 700px;
        }

        .a {
            display: flex;
        }

        .w-100 {
            width: 100%;
        }

        table {
            border-collapse: collapse;
            margin-top: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
        }

        th,
        td {
            padding-top: 15px;
            padding-bottom: 15px;
            padding-left: 5px;
            padding-right: 5px;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .margin-10px {
            margin: 10px;
        }

        .padding-10px {
            padding: 10px;
        }

        .card1 {
            border-radius: 20px;
        }

        .container {
            margin-top: 10px;
            width: 100%;
            padding: 0px;
        }
    </style>
</head>

<body>
    <div align="center">
        <div class="container">
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
                    <td><?= $week_ave ?></td>
                </tr>
            </table>
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
                $sql = "SELECT * FROM `junior3_grade_set` WHERE `week_ID` = '$week_ID' ORDER BY `test_ID` ASC";
                $result = $conn->query($sql);
                while ($row0 = $result->fetch_assoc()) {
                    $test_name = $row0["test_name"];
                    $test_date = $row0["test_date"];
                    $test_ave = $row0["average"];

                    if ($test_date == '0000-00-00') {
                        $set_test_date = '';
                    } else {
                        $set_test_date = (new DateTime($test_date))->format('m/d') . ' ';
                    }

                    echo "<tr><td>$set_test_date$test_name</td><td>$test_ave</td>";

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
                        $sql = "SELECT * FROM `$week_ID` WHERE `classNum` = '$i'";
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
            <!--
            <table>
                <tr>
                    <th colspan="12">家長檢視率</th>
                </tr>
                <tr>
                    <th>全年級</th>
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
                <tr>
                    <?php
                    /*
                    $sql1 = "SELECT * FROM `$week_ID` WHERE `name` != '空';";
                    $result1 = $conn->query($sql1);
                    $rowCount1 = $result1->num_rows;

                    $sql2 = "SELECT * FROM `$week_ID` WHERE `name` != '空' AND `sign` != '';";
                    $result2 = $conn->query($sql2);
                    $rowCount2 = $result2->num_rows;
                    $week_sign = $rowCount2 . "/" . $rowCount1;
                    $week_sign_per = round($rowCount2 / $rowCount1 * 100) . "%";
                    if ($rowCount2 / $rowCount1 * 100 >= 70) {
                        echo "<td style='color:orange'>$week_sign_per ($week_sign)</td>";
                    } else if ($rowCount2 / $rowCount1 * 100 <= 30) {
                        echo "<td style='color:red'>$week_sign_per ($week_sign)</td>";
                    } else {
                        echo "<td>$week_sign_per ($week_sign)</td>";

                    }

                    for ($i = 901; $i <= 911; $i++) {
                        $sql1 = "SELECT * FROM `$week_ID` WHERE `classNum` = '$i' AND `name` != '空';";
                        $result1 = $conn->query($sql1);
                        $rowCount1 = $result1->num_rows;

                        $sql2 = "SELECT * FROM `$week_ID` WHERE `classNum` = '$i' AND `name` != '空' AND `sign` != '';";
                        $result2 = $conn->query($sql2);
                        $rowCount2 = $result2->num_rows;
                        $week_sign = $rowCount2 . "/" . $rowCount1;
                        $week_sign_per = round($rowCount2 / $rowCount1 * 100) . "%";
                        if ($rowCount2 / $rowCount1 * 100 >= 70) {
                            echo "<td style='color:orange'>$week_sign_per ($week_sign)</td>";
                        } else if ($rowCount2 / $rowCount1 * 100 <= 30) {
                            echo "<td style='color:red'>$week_sign_per ($week_sign)</td>";
                        } else {
                            echo "<td>$week_sign_per ($week_sign)</td>";

                        }
                    }
                        */
                    ?>
                </tr>
            </table>
            -->
        </div>
    </div>
</body>