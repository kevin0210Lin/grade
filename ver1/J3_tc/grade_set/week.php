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

<head>
    <link href="/set/style.css" rel="stylesheet">
    <link rel="icon" href="" type="image/x-icon">
    <?php
    require_once("../ad1.php");
    ?>
</head>

<body>
    <?php
    require_once("../ad2.php");
    ?>
    <div align="center">
        <?php
        if (isset($_GET["btn_val"])) {
            $_SESSION["tc_class"] = $_GET["tc_class"];

            $_SESSION["week_set_ID"] = $_GET["btn_id"];
            $_SESSION["week_show"] = "Y";
            $_SESSION["week_set_choose"] = $_GET["btn_val"];
            //echo $_SESSION["week_set_choose"];
            $_SESSION["week_set_week_name"] = $_GET["week_name"];
        }



        if ($_SESSION["week_show"] == "Y") {
            if ($_SESSION["week_set_choose"] == "新增區間") {
                $_SESSION["grade_show"] = "N";
                ?>
                <div class="o-hidden border-0 shadow-lg my-0 padding-10px card1">
                    <b style='font-size:1.5rem;'>新增考試區間</b>
                    <form action="insert_week.php" method="POST">
                        <table>
                            <tr>
                                <th>請輸入區間的名稱(日期)</th>
                            </tr>
                            <tr>
                                <td><input type="text" required name="week_name" id="week_name" placeholder="請輸入">
                                    <input type="submit" id='submitBtn' class="btn btn-primary btn-user" value="點我新增">
                                </td>
                            </tr>
                        </table>

                    </form>
                </div>

                <?php
            } else if ($_SESSION["week_set_choose"] == "week編輯") {
                $classNum = $_SESSION["classNum"];
                $week_ID = $_SESSION["week_set_ID"];
                $_SESSION["grade_show"] = "Y";

                $sql1 = "SELECT * FROM `junior3_week_set` WHERE `week_ID` = '$week_ID'";
                $result1 = $conn->query($sql1);
                $row = $result1->fetch_assoc();
                $week_name = $row["week_name"];

                ?>


                    <div class='o-hidden border-0 shadow-lg padding-10px card1'>
                        <b style='font-size:1.5rem;'><?= $week_name ?> 成績編輯</b>
                        <br>
                        <button name='set_grade' id='insert_week' class='btn btn-success btn-user' week='<?= $week_ID ?>'
                            tc_class='<?= $_SESSION["tc_class"] ?>' value='新增成績'>新增考試科目/範圍/名稱</button>
                        <a href="average_show.php?week_ID=<?= $week_ID ?>" target="_blank"
                            class="btn btn-user btn-info">檢視年級成績(跳新視窗)</a>

                        <?php

                        $sql2 = "SELECT * FROM `junior3_grade_set` WHERE `week_ID` = '$week_ID'";
                        $result2 = $conn->query($sql2);

                        if ($classNum == "admin") {
                            if ($result2->num_rows > 0) {
                                echo "<table><tr><th>考試科目/範圍/名稱</th><th>成績管理</th></tr>";
                                while ($row2 = $result2->fetch_assoc()) {
                                    $test_ID = $row2["test_ID"];
                                    $test_name = $row2["test_name"];
                                    echo "<tr><td>$test_name
                                        <button class='transparent-button' name='set_grade' id='$test_ID' value='grade_name編輯' tc_class='" . $_SESSION["tc_class"] . "' week='$week_ID' test_name='$test_name'>
                                        <img src='img/edit.png' title='編輯考試名稱' alt='編輯名稱' class='icon'></button></td>
                                        <td><button name='set_grade' id='$test_ID' week='$week_ID' value='grade編輯' tc_class='admin' test_name='$test_name' class='btn btn-secondary btn-user'>管理</button></td></tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "<table><tr><th>考試科目/範圍/名稱</th><th>操作</th></tr>
                                  <tr><td colspan='2'>尚無成績</td></tr></table>";
                            }

                        } else {
                            if ($result2->num_rows > 0) {
                                echo "<table><tr><th>考試科目/範圍/名稱</th><th>班級平均</th><th>班級成績管理</th></tr>";
                                while ($row2 = $result2->fetch_assoc()) {
                                    $test_ID = $row2["test_ID"];
                                    $test_name = $row2["test_name"];
                                    $test_enter = $row2["$classNum"];
                                    if ($test_enter == "") {
                                        $test_enter = "尚未登記";
                                    }
                                    echo "<tr><td>$test_name
                                        <button class='transparent-button' name='set_grade' id='$test_ID' value='grade_name編輯' tc_class='" . $_SESSION["tc_class"] . "' week='$week_ID' test_name='$test_name'>
                                        <img src='img/edit.png' title='編輯考試名稱' alt='編輯名稱' class='icon'></button></td>
                                        <td>$test_enter</td><td><button name='set_grade' id='$test_ID' week='$week_ID' value='grade編輯' tc_class='" . $_SESSION["tc_class"] . "' test_name='$test_name' class='btn btn-secondary btn-user'>管理</button></td></tr>";
                                }
                                echo "</table>";
                            } else {
                                echo "<table><tr><th>考試科目/範圍/名稱</th><th>班級平均</th><th>操作</th></tr>
                                  <tr><td colspan='3'>尚無成績</td></tr></table>";
                            }
                        }
                        ?>
                    </div>
                <?php
            } else if ($_SESSION["week_set_choose"] == "week_name編輯") {
                $classNum = $_SESSION["classNum"];
                $week_ID = $_SESSION["week_set_ID"];
                $_SESSION["grade_show"] = "N";


                $sql1 = "SELECT * FROM `junior3_week_set` WHERE `week_ID` = '$week_ID'";
                $result1 = $conn->query($sql1);
                $row = $result1->fetch_assoc();
                $week_name = $row["week_name"];
                ?>
                        <div class='o-hidden border-0 shadow-lg my-0 padding-10px card1'>
                            <b style='font-size:1.5rem;'>區間/日期名稱編輯</b>
                            <form method='post' action='update_week_name.php'>
                                <table>
                                    <tr>
                                        <th>目前名稱</th>
                                        <td>
                                    <?= $week_name ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>更改名稱</th>
                                        <td><input type='text' name='new_week_name' id='new_week_name' required="required">
                                            <input type="hidden" name='week_ID' value='<?= $week_ID ?>'>
                                            <input type='submit' id='submitBtn' class='btn btn-primary btn-user' value='儲存'>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                <?php

            }
        } else {
            echo "請操作";
            $_SESSION["grade_show"] = "N";
        }


        ?>

    </div>
</body>


<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function () {
        load_data_grade();

        function load_data_grade(btn_val, btn_id, btn_week, tc_class, test_name) {
            $.ajax({
                url: "grade.php",
                method: "GET",
                data: {
                    btn_val: btn_val,
                    btn_id: btn_id,
                    btn_week: btn_week,
                    tc_class: tc_class,
                    test_name: test_name
                },
                success: function (data) {
                    $('.grade').html(data); // Updated the selector here
                }
            });
        }

        $('[name="set_grade"]').click(function () {
            var btn1 = $(this).val();
            var btn2 = $(this).attr('id');
            var btn3 = $(this).attr('week');
            var btn4 = $(this).attr('tc_class');
            var btn5 = $(this).attr('test_name');
            if (btn1 != '' && btn2 != '' && btn3 != '' && btn4 != '' && btn5 != '') {
                load_data_grade(btn1, btn2, btn3, btn4, btn5);
            } else {
                load_data_grade();
            }
        });

    });

    // 滚动到顶部的 JavaScript 代码
    $('button').click(function () {
        scrollToTopAndReturn();
    });

    function scrollToTopAndReturn() {
        // 滚动到顶部
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
</script>