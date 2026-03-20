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
            border-bottom: 1px solid #ddd;
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

        .icon {
            margin: 0;
            margin-left: .3em;
            padding: 0 !important;
            width: 16px;
            height: 16px;
        }

        .transparent-button {
            background: none;
            border: none;
            padding: 0;
            margin: 0;
            cursor: pointer;
        }

        .transparent-button img {
            pointer-events: none;
            /* 使得點擊事件只作用於按鈕 */
        }
    </style>

    <?php
    require_once("../ad1.php");
    ?>
</head>

<body>
    <?php
    require_once("../ad2.php");
    ?>
    <div class="container">
        <div align="center">
            <font style="font-family:微軟正黑體;color:#000; font-size:2.5rem;">
                <b>國三老師成績管理頁面</b>
                <br>
            </font>

            <?php
            $classNum = $_SESSION["classNum"];
            $name = $_SESSION["name"];
            echo "$name 你好";
            ?>

            <a href='../index.php' class='btn btn-danger btn-user'>登出</a>
            <a href='../result.php' class='btn btn-info btn-user'>返回主畫面</a>
            <a href='../stu_login_set/' class='btn btn-primary btn-user'>學生登入管理</a>
        </div>
        <div align="center">
            <div class="a">
                <div class="w-100 margin-10px">
                    <div class="o-hidden border-0 shadow-lg my-0 padding-10px card1">
                        <b style='font-size:1.5rem;'>區間項目編輯</b>
                        <button name="set_week" id="insert_week" class="btn btn-success btn-user" week_name="week_name"
                            tc_class="<?= $classNum ?>" value="新增區間">新增考試區間/日期</button>
                        <table class="w-100">
                            <tr>
                                <th>區間</th>
                                <th>最後更動時間</th>
                                <th>操作</th>
                            </tr>
                            <?php
                            $sql = "SELECT * FROM `junior3_week_set` ORDER BY `week_ID` DESC";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $week_ID = $row["week_ID"];
                                    $week_name = $row["week_name"];
                                    $last_set_time = $row["last_set_time"];
                                    echo "<tr>
                                            <td>$week_name
                                                <button class='transparent-button' name='set_week' id='$week_ID' value='week_name編輯' tc_class='$classNum' week_name='$week_name'>
                                                    <img src='img/edit.png' title='編輯區間名稱' alt='編輯名稱' class='icon'>
                                                </button>
                                            </td>
                                            <td>$last_set_time</td>
                                            <td>
                                                <button name='set_week' id='$week_ID' value='week編輯' tc_class='$classNum' week_name='$week_name' class='btn btn-warning btn-user'>成績管理</button>
                                            </td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>尚無週次</td></tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
                <div class="w-100 margin-10px">
                    <div class="week">
                    </div>
                </div>

                <div class="w-100 margin-10px">
                    <div class="grade">
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    $(document).ready(function () {
        // 載入週次資料
        load_data_week();

        function load_data_week(btn_val, btn_id, tc_class, week_name) {
            $.ajax({
                url: "week.php",
                method: "GET",
                data: {
                    btn_val: btn_val,
                    btn_id: btn_id,
                    tc_class: tc_class,
                    week_name: week_name,
                },
                success: function (data) {
                    $('.week').html(data);
                }
            });
        }

        // 監聽按鈕和透明按鈕點擊事件
        $('[name="set_week"]').click(function () {
            var btn1 = $(this).attr('value');
            var btn2 = $(this).attr('id');
            var btn3 = $(this).attr('tc_class');
            var btn4 = $(this).attr('week_name');

            if (btn1 && btn2 && btn3 && btn4) {
                load_data_week(btn1, btn2, btn3, btn4);
            } else {
                load_data_week();
            }
        });
    });

    // 滾動到顶部的 JavaScript 代碼
    $('button').click(function () {
        scrollToTopAndReturn();
    });

    function scrollToTopAndReturn() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
</script>