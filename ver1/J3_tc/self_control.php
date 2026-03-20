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

if (isset($_GET['d'])) {
    $day = $_GET["d"];
} else {
    echo "<script>window.location.href = 'self_index.php';</script>";
}

$sql = "SELECT * FROM `Self_study_subject` WHERE `control` = 1 ORDER BY `ID` ASC";
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
    <title>國三大自習 <?= $day ?>解題科目設定</title>
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

        .th-1 {
            background-color: #AAAAFF;
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
                <b>六和高中 國三114會考 考前大自習<br><?= $day ?> 解題科目設定</b>
            </font>
            <a href="self_index.php" class="btn btn-success btn-user">回上頁</a>
            <form action="self_update.php" method="post">
                <table border="1">
                    <tr>
                        <th>節數</th>
                        <th>解題科目設定</th>
                    </tr>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        $id = $row["ID"];
                        $name = $row["name"];
                        $subject = $row[$day];
                        echo "<tr><th class='th-1'>$name</th><td><input type='text' name='$id' id='$id' value='$subject' class='form-control'></td></tr>";
                    }
                    ?>
                </table>
                <input type="hidden" name="d" value="<?= $day ?>">
                <input type="submit" class="form-control btn btn-primary btn-user" value="更新">
            </form>
        </div>
    </div>
</body>