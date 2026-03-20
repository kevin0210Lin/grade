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
            max-width: 1300px;
            min-width: 700px;
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
            $name = $_SESSION["name"];
            $status = $_SESSION["status"];

            echo "
                $name 您好<a href='index.php' class='btn btn-danger btn-user'>登出</a>
                <a href='result.php' class='btn btn-info btn-user'>返回主畫面</a>";

            if ($_SESSION["status"] == "teacher" || $_SESSION["status"] == "admin") {
                echo "<a href='grade_set/index.php' class='btn btn-primary btn-user'>成績管理</a>";
            }

            if (isset($_GET["week"])) {
                $week_ID = $_GET["week"];
                $_SESSION["week_ID_choose"] = $week_ID;
                ?>
                <div style="display: flex;justify-content: center;">
                    <input type="text" name="search_class" id="search_class" placeholder="請搜尋班級" class="form-control"
                        style="width: auto;">&nbsp
                    <input type="text" name="search_seat" id="search_seat" placeholder="請搜尋座號" class="form-control"
                        style="width: auto;">&nbsp
                    <input type="text" name="search_name" id="search_name" placeholder="請搜尋姓名" class="form-control"
                        style="width: auto;">
                    <a class="btn btn-success btn-user" href="DL.php">點我下載此總表</a>
                </div>

                <div class="grade_show"></div>
                <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

                <script>
                    $(document).ready(function () {

                        load_data();

                        function load_data(queryClass, querySeat, queryName) {
                            $.ajax({
                                url: "search.php",
                                method: "GET",
                                data: {
                                    grade_search_class: queryClass,
                                    grade_search_seat: querySeat,
                                    grade_search_name: queryName
                                },
                                success: function (data) {
                                    $('.grade_show').html(data);
                                }
                            });
                        }

                        $('#search_class, #search_seat, #search_name').keyup(function () {
                            var searchClass = $('#search_class').val();
                            var searchSeat = $('#search_seat').val();
                            var searchName = $('#search_name').val();
                            if (searchClass != '' || searchSeat != '' || searchName != '') {
                                load_data(searchClass, searchSeat, searchName);
                            } else {
                                load_data();
                            }
                        });
                    });
                </script>


                <?php
            } else {
                echo "<script>alert('執行錯誤');</script>";
                echo "<script>window.location.href = 'result.php';</script>";
            }

