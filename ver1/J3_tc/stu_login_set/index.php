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
?>

<!DOCTYPE html>
<html>

<head>
    <?php
    require_once("../ad1.php");
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
    require_once("../ad2.php");
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
            $name 您好<a href='../index.php' class='btn btn-danger btn-user'>登出</a>";
            echo "<a href='../result.php' class='btn btn-info btn-user'>返回主頁面</a>";
            echo "<a href='../grade_set/index.php' class='btn btn-primary btn-user'>成績管理</a>";



            if ($classNum == "admin") {
                $sql = "SELECT * FROM `junior3_login`";
            } else {
                $sql = "SELECT * FROM `junior3_login` WHERE classNum = '$classNum'";
            }
            $result = $conn->query($sql);
            if ($classNum == "admin") {
                echo "管理員身分不能更改帳號密碼!!!";
            } else {
                echo "<form action='update.php' method='post'>";
            }


            echo "<table><tr><th width='25%'>班級</th><th width='25%'>座號</th><th width='25%'>姓名</th><th width='25%'>登入密碼</th></tr>";
            while ($row = $result->fetch_assoc()) {
                $stu_class = $row["classNum"];
                $stu_name = $row["name"];
                $stu_seatNum = $row["seatNum"];
                $stu_id = $row["loginID"];
                echo "<tr><td>$stu_class</td><td>$stu_seatNum</td><td><input type='text' name='name_$stu_seatNum' value='$stu_name' required></td><td><input type='text' name='id_$stu_seatNum' value='$stu_id' required></td>";

            }
            echo "</table>";

            if ($classNum == "admin") {
                echo "管理員身分不能更改帳號密碼!!!";
            } else {
                echo "<input type='hidden' name='classnum' value='$classNum'>";
                echo "<input type='submit' class='btn btn-primary btn-user' value='儲存'>";
                echo "</form>";
            }


            ?>
        </div>
    </div>

</body>

</html>