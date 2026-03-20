<?php
require_once("../set.php");

if (isset($_SESSION['login_check'])) {
    if ($_SESSION['login_check'] != "T") {
        echo "<script>alert('您尚未登入1');</script>";
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
    <div class="container">
        <div align="center">
            <font style="font-family:微軟正黑體;color:#000; font-size:2.2rem;">
                <b>六和高中 國三成績系統</b>
            </font><br>
            <?php
            $classNum = $_SESSION["classNum"];
            $seatNum = $_SESSION["seatNum"];
            $name = $_SESSION["name"];

            echo "
        $name 您好<a href='index.php' class='btn btn-danger btn-user'>登出</a>";

            $sql = "SELECT * FROM `junior3_week_set` ORDER BY `week_ID` DESC";
            $result = $conn->query($sql);
            echo "<table><tr><th width='60%'>週次/考試日期</th><th width='40%'>檢視成績</th></tr>";
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $week_ID = $row["week_ID"];
                    $week_name = $row["week_name"];
                    echo "<tr><td>$week_name</td><td><a href='grade.php?week=$week_ID' class='btn btn-success btn-user'>點我</a></td></tr>";
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