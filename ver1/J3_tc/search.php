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
?>

<head>
    <?php
    require_once("ad1.php");
    ?>
    <link href="/set/style.css" rel="stylesheet">
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
require_once("ad2.php");
?>


<?php
$classNum = $_SESSION["classNum"];
$week_ID = $_SESSION["week_ID_choose"];

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
