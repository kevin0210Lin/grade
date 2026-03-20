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

date_default_timezone_set('Asia/Taipei');

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=' . $classNum . '成績-' . date("Y-m-d H:i:s") . '.xls');
?>

<head>
    <?php
    require_once("ad1.php");
    ?>
    <!--
<link href="http://123.0.251.186:1080/set/style.css" rel="stylesheet">
-->
    <link rel="icon" href="" type="image/x-icon">
    <style>
        table {
            width: 100%;
            margin-top: 15px;
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
            background-color: #ecf0f1;
        }
    </style>

</head>

<?php

$week_ID = $_SESSION["week_ID_choose"];
$sql1 = "SELECT * FROM `junior3_week_set` WHERE `week_ID` = '$week_ID'";
$result1 = $conn->query($sql1);
$row = $result1->fetch_assoc();
$week_ave = $row["week_ave"];

?>
<?php
require_once("ad2.php");
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
    $week_ID = $_SESSION["week_ID_choose"];
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


<?php
$classNum = $_SESSION["classNum"];


if ($_SESSION["status"] == "teacher") {

    if (isset($_GET['grade_search_class']) || isset($_GET['grade_search_seat']) || isset($_GET['grade_search_name'])) {
        $sqlsearch = "SELECT * FROM `$week_ID` WHERE classNum = '$classNum' AND ";

        if (isset($_GET['grade_search_class'])) {
            $grade_search_class = $_GET['grade_search_class'];
            $sqlsearch .= "classNum LIKE '%$grade_search_class%' AND ";
        }
        if (isset($_GET['grade_search_seat'])) {
            $grade_search_seat = $_GET['grade_search_seat'];
            $sqlsearch .= "seatNum LIKE '%$grade_search_seat%' AND ";
        }
        if (isset($_GET['grade_search_name'])) {
            $grade_search_name = $_GET['grade_search_name'];
            $sqlsearch .= "name LIKE '%$grade_search_name%'";
        }
    } else {
        $sqlsearch = "SELECT * FROM `$week_ID` WHERE classNum = '$classNum'";
    }
    //echo $sqlsearch;

    $result_search = $conn->query($sqlsearch);
    $sqlgrade_Numrows = "SELECT * FROM `junior3_grade_set` WHERE week_ID = '$week_ID' ORDER BY test_ID ASC";
    $resultgrade_Numrows = $conn->query($sqlgrade_Numrows);
    $i = 0;
    $grade_ID = [];
    $grade_name = [];
    while ($row = $resultgrade_Numrows->fetch_assoc()) {
        $i++;
        $grade_ID[$i] = $row["test_ID"];
        $grade_name[$i] = $row["test_name"];
    }

    echo "<table><tr><th>班級</th><th>座號</th><th>姓名</th>";
    for ($a = 1; $a <= $i; $a++) {
        echo "<th>" . $grade_name[$a] . "</th>";
    }
    echo "<th>總分</th><th>平均</th><th>班排</th><th>校排百分比</th></tr>";

    while ($row_search = $result_search->fetch_assoc()) {
        $s_classNum = $row_search["classNum"];
        $s_seatNum = $row_search["seatNum"];
        $s_name = $row_search["name"];


        echo "<tr><td>$s_classNum</td><td>$s_seatNum</td><td>$s_name</td>";
        for ($a = 1; $a <= $i; $a++) {
            $s_grade = $row_search[$grade_ID[$a]];
            echo "<td>$s_grade</td>";
        }
        $aa1 = $row_search["SUM"];
        $aa2 = round($row_search["ave"], 2);
        $aa3 = $row_search["classRank"];
        $aa4 = $row_search["gradeRankPer"];
        echo "<td>$aa1</td><td>$aa2</td><td>$aa3</td><td>$aa4</td></tr>";
    }
} else if ($_SESSION["status"] == "manage" || $_SESSION["status"] == "admin") {

    if (isset($_GET['grade_search_class']) || isset($_GET['grade_search_seat']) || isset($_GET['grade_search_name'])) {
        $sqlsearch = "SELECT * FROM `$week_ID` WHERE ";

        if (isset($_GET['grade_search_class'])) {
            $grade_search_class = $_GET['grade_search_class'];
            $sqlsearch .= "classNum LIKE '%$grade_search_class%' AND ";
        }
        if (isset($_GET['grade_search_seat'])) {
            $grade_search_seat = $_GET['grade_search_seat'];
            $sqlsearch .= "seatNum LIKE '%$grade_search_seat%' AND ";
        }
        if (isset($_GET['grade_search_name'])) {
            $grade_search_name = $_GET['grade_search_name'];
            $sqlsearch .= "name LIKE '%$grade_search_name%'";
        }
    } else {
        $sqlsearch = "SELECT * FROM `$week_ID`";
    }
    //echo $sqlsearch;

    $result_search = $conn->query($sqlsearch);
    $sqlgrade_Numrows = "SELECT * FROM `junior3_grade_set` WHERE week_ID = '$week_ID' ORDER BY test_ID ASC";
    $resultgrade_Numrows = $conn->query($sqlgrade_Numrows);
    $i = 0;
    $grade_ID = [];
    $grade_name = [];
    while ($row = $resultgrade_Numrows->fetch_assoc()) {
        $i++;
        $grade_ID[$i] = $row["test_ID"];
        $grade_name[$i] = $row["test_name"];
    }

    echo "<table><tr><th>班級</th><th>座號</th><th>姓名</th>";
    for ($a = 1; $a <= $i; $a++) {
        echo "<th>" . $grade_name[$a] . "</th>";
    }
    echo "<th>總分</th><th>平均</th><th>班排</th><th>校排百分比</th></tr>";

    while ($row_search = $result_search->fetch_assoc()) {
        $s_classNum = $row_search["classNum"];
        $s_seatNum = $row_search["seatNum"];
        $s_name = $row_search["name"];


        echo "<tr><td>$s_classNum</td><td>$s_seatNum</td><td>$s_name</td>";
        for ($a = 1; $a <= $i; $a++) {
            $s_grade = $row_search[$grade_ID[$a]];
            echo "<td>$s_grade</td>";
        }
        $aa1 = $row_search["SUM"];
        $aa2 = round($row_search["ave"], 2);
        $aa3 = $row_search["classRank"];
        $aa4 = $row_search["gradeRankPer"];
        echo "<td>$aa1</td><td>$aa2</td><td>$aa3</td><td>$aa4</td></tr>";
    }
}

$conn->close()
    ?>