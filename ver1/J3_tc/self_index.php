<?php
require_once("../set.php");

// 檢查連線是否成功
if ($conn->connect_error) {
    die('連線失敗: ' . $conn->connect_error);
}

// 設定字符集
$conn->set_charset("utf8mb4");

if (isset($_SESSION['login_check'])) {
    if ($_SESSION['login_check'] != "T") {
        echo "<script>alert('您尚未登入');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }
} else {
    echo "<script>alert('您尚未登入');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}

$sql = "SELECT * FROM `Self_study_subject` WHERE `control` = 1";
$result = $conn->query($sql);
?>
<!DOCTYPE html>

<head>
    <?php
    require_once("ad1.php");
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/set/style.css" rel="stylesheet">
    <title>國三大自習 解題科目設定</title>
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
                <b>六和高中 國三114會考 考前大自習<br>解題科目設定</b>
            </font><br>
            <table border="1">
                <tr>
                    <th>第一週</th>
                    <td><a href="self_control.php?d=4/21" class="btn btn-info btn-user">4/21</a></td>
                    <td><a href="self_control.php?d=4/22" class="btn btn-info btn-user">4/22</a></td>
                    <td><a href="self_control.php?d=4/23" class="btn btn-info btn-user">4/23</a></td>
                    <td><a href="self_control.php?d=4/24" class="btn btn-info btn-user">4/24</a></td>
                    <td><a href="self_control.php?d=4/25" class="btn btn-info btn-user">4/25</a></td>
                </tr>
                <tr>
                    <th>第二週</th>
                    <td><a href="self_control.php?d=4/28" class="btn btn-info btn-user">4/28</a></td>
                    <td><a href="self_control.php?d=4/29" class="btn btn-info btn-user">4/29</a></td>
                    <td><a href="self_control.php?d=4/30" class="btn btn-info btn-user">4/30</a></td>
                    <td><a href="self_control.php?d=5/1" class="btn btn-info btn-user">5/1</a></td>
                    <td><a href="self_control.php?d=5/2" class="btn btn-info btn-user">5/2</a></td>
                </tr>
                <tr>
                    <th>第三週</th>
                    <td><a href="self_control.php?d=5/5" class="btn btn-info btn-user">5/5</a></td>
                    <td><a href="self_control.php?d=5/6" class="btn btn-info btn-user">5/6</a></td>
                    <td><a href="self_control.php?d=5/7" class="btn btn-info btn-user">5/7</a></td>
                    <td><a href="self_control.php?d=5/8" class="btn btn-info btn-user">5/8</a></td>
                    <td><a href="self_control.php?d=5/9" class="btn btn-info btn-user">5/9</a></td>
                </tr>
                <tr>
                    <th>第四週</th>
                    <td><a href="self_control.php?d=5/12" class="btn btn-info btn-user">5/12</a></td>
                    <td><a href="self_control.php?d=5/13" class="btn btn-info btn-user">5/13</a></td>
                    <td><a href="self_control.php?d=5/14" class="btn btn-info btn-user">5/14</a></td>
                    <td><a href="self_control.php?d=5/15" class="btn btn-info btn-user">5/15</a></td>
                    <td><a href="self_control.php?d=5/16" class="btn btn-info btn-user">5/16</a></td>

                </tr>
            </table>
            <h2 style="color:red">若要更改顯示器左方的文字及鐘聲設定，請洽管理員。</h2>
        </div>
    </div>
</body>