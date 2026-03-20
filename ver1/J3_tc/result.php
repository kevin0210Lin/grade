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
            max-width: 1200px;
            min-width: 700px;
        }

        header {
            background-color: #3498db;
            padding: 30px;
            color: white;
            font-size: 36px;
            width: 100%;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
        }

        h1 {
            color: #333;
            margin-top: 20px;
            font-size: 28px;
        }

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

        footer {
            background-color: #3498db;
            padding: 15px;
            color: white;
            width: 100%;
            text-align: center;
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
                <b>六和高中 國三成績系統</b>
            </font><br>
            <?php
            $classNum = $_SESSION["classNum"];
            $name = $_SESSION["name"];

            echo "
            $name 您好<a href='index.php' class='btn btn-danger btn-user'>登出</a>";

            if ($_SESSION["status"] == "admin") {
                echo "<a href='self_index.php' class='btn btn-success btn-user'>大自習</a>";
            }

            if ($_SESSION["status"] == "admin" || $_SESSION["status"] == "teacher") {
                echo "<a href='grade_set/index.php' class='btn btn-primary btn-user'>成績管理</a>";
                echo "<a href='stu_login_set/' class='btn btn-info btn-user'>學生登入管理</a>";
            }

            //echo "<p class='text-danger'>本網頁將於2024/9/13 16:30 至 2024/9/15 23:59 進行停機維護作業，造成不便請見諒</p>";
            $sql = "SELECT * FROM `junior3_week_set` ORDER BY `week_ID` DESC";
            $result = $conn->query($sql);
            echo "<table><tr><th width='40%'>週次/考試日期</th><th width='40%'>成績最後更新時間</th><th width='20%'>檢視班級成績</th></tr>";
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $week_ID = $row["week_ID"];
                    $week_name = $row["week_name"];
                    $last_set_time = $row["last_set_time"];
                    echo "<tr><td>$week_name</td><td>$last_set_time</td><td><a href='grade.php?week=$week_ID' class='btn btn-success btn-user'>點我</a></td></tr>";
                }
            } else {
                echo "<tr><td colspan='3'>尚無成績資料</td></tr>";
            }
            echo "</table>";
            ?>
        </div>
    </div>

</body>

</html>

<?php

//for grade_set/week.php
$_SESSION["week_show"] = "N";
//for grade_set/grade.php
$_SESSION["grade_show"] = "N";

?>