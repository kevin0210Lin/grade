<?php
require_once("set.php");

if (isset($_SESSION['login_check'])) {
    if ($_SESSION['login_check'] != "T") {
        echo "<script>alert('您尚未登入');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }
} else {
    echo "<script>alert('您尚未登入');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php
    require_once("ad1.php");
    ?>
    <meta charset="UTF-8">
    <link href="/set/style.css" rel="stylesheet">
    <link rel="icon" href="" type="image/x-icon">
    <title>成績查詢系統</title>
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
            max-width: 1300px;
            min-width: 700px;
        }

        table {
            width: 100%;
            margin-top: 15px;
            background-color: #FFFCEC;
        }

        th,
        td {
            padding: 20px;
            text-align: center;
        }

        th {
            background-color: #3498db;
            color: white;
            font-size: 20px;
        }

        tr:hover {
            background-color: #FFF8D7;
        }

        .th1 {
            background-color: #FF8000;
        }

        .w-50per {
            width: 50%;
        }
    </style>
</head>

<body>
    <?php
    require_once("ad2.php");
    ?>
    <div class="container">
        <div align="center">
            <font style="font-family:微軟正黑體;color:#000; font-size:2.2rem;">
                <b>六和高中 國三成績系統 成績總覽</b>
            </font><br>

            <?php
            $classNum = $_SESSION["classNum"];
            $seatNum = $_SESSION["seatNum"];
            $name = $_SESSION["name"];

            echo "
                $name 您好<a href='index.php' class='btn btn-danger btn-user'>登出</a>
                <a href='result.php' class='btn btn-info btn-user'>返回主畫面</a>";


            if (isset($_GET["week"])) {
                $week_ID = $_GET["week"];
                $_SESSION["week_ID_choose"] = $week_ID;

                $sqlweekname = "SELECT * FROM `junior3_week_set` WHERE week_ID = '$week_ID'";
                $resultweekname = $conn->query($sqlweekname);
                $row_week = $resultweekname->fetch_assoc();
                $week_name = $row_week["week_name"];
                $week_show_check = $row_week["grade_insert_check"];

                $sqlgrade_Numrows = "SELECT * FROM `junior3_grade_set` WHERE week_ID = '$week_ID' ORDER BY test_ID ASC";
                $resultgrade_Numrows = $conn->query($sqlgrade_Numrows);
                $i = 0;
                $grade_ID = [];
                $grade_name = [];
                $grade_show = [];
                while ($row = $resultgrade_Numrows->fetch_assoc()) {
                    $i++;
                    $grade_ID[$i] = $row["test_ID"];
                    $grade_name[$i] = $row["test_name"];
                    $grade_show[$i] = $row["tc_check"];
                    $grade_average[$i] = $row["average"];
                }

                $sql_grade = "SELECT * FROM `$week_ID` WHERE `name`='$name' AND `classNum` = '$classNum' AND `seatNum` = '$seatNum'";
                $reslut_grade = $conn->query($sql_grade);

                while ($row = $reslut_grade->fetch_assoc()) {
                    ?>
                    <table>
                        <tr>
                            <th>班級</th>
                            <td>
                                <?= $classNum ?>
                            </td>
                            <th>姓名</th>
                            <td>
                                <?= $name ?>
                            </td>
                        </tr>
                        <tr>
                            <th>座號</th>
                            <td>
                                <?= $seatNum ?>
                            </td>
                            <th>考試</th>
                            <td>
                                <?= $week_name ?>
                            </td>
                        </tr>
                    </table>

                    <table>
                        <tr>
                            <th>考試內容</th>
                            <th>你的成績</th>
                            <th>年級平均</th>
                            <th>年級百分比</th>
                        </tr>
                        <?php
                        for ($a = 1; $a <= $i; $a++) {
                            $s_grade = $row[$grade_ID[$a]];
                            if ($s_grade == "") {
                                $s_grade = "無成績";
                            }
                            if ($grade_show[$a] == "11") {
                                $s_grade_per = $row["per_" . $grade_ID[$a]];
                                $s_grade_ave = $grade_average[$a];
                            } else {
                                $s_grade_per = "成績整理中";
                                $s_grade_ave = "成績整理中";
                            }
                            echo "<tr><td>" . $grade_name[$a] . "</td><td>$s_grade</td><td>$s_grade_ave</td><td>$s_grade_per</td></tr>";
                        }
                        ?>
                    </table>
                    <?php

                    $s_sum = $row["SUM"];
                    if ($s_sum == "" || $s_sum == "0") {
                        $s_sum = "";
                    }
                    $s_ave = $row["ave"];
                    if ($s_ave == "" || $s_ave == "0") {
                        $s_ave = "";
                    }
                    $s_gradeRank = $row["gradeRankPer"];
                    if ($s_gradeRank == "" || $s_gradeRank == "0") {
                        $s_gradeRank = "";
                    }
                    $s_classRankPer = $row["classRankPer"];
                    if ($s_classRankPer == "" || $s_classRankPer == "0") {
                        $s_classRankPer = "";
                    }
                    $s_weekave = $row_week["week_ave"];

                    if ($week_show_check == 1) {
                        echo "<table class='th2'>
                        <tr><th class='th1'>您的總成績/平均</th><th class='th1'>年級總平均</th><th class='th1'>全班百分組距</th><th class='th1'>年級百分比</th></tr>
                        <tr><td>$s_sum/$s_ave</td><td>$s_weekave</td><td>$s_classRankPer</td><td>$s_gradeRank</td></tr>";
                    }


                }


            } else {
                echo "<script>alert('執行錯誤');</script>";
                echo "<script>window.location.href = 'result.php';</script>";
            }
