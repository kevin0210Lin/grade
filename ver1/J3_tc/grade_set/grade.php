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
date_default_timezone_set('Asia/Taipei');

?>
<head>
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
        if ($_SESSION["grade_show"] == "Y") {
            if (isset($_GET["btn_val"])) {
                //echo $_GET["tc_class"];
        
                if ($_GET["btn_val"] == "新增成績") {
                    ?>
                    <div class="o-hidden border-0 shadow-lg my-0 padding-10px card1">
                        <b style='font-size:1.5rem;'>新增成績項目</b>
                        <form action="insert_grade.php" method="POST">
                            <table>
                                <tr>
                                    <th>請輸入考試科目/範圍/名稱</th>
                                </tr>
                                <tr>
                                    <td><input type="text" required name="test_name" id="test_name" placeholder="請輸入">
                                        <input type="submit" id='submitBtn' class="btn btn-primary btn-user" value="點我新增">
                                        <input type="hidden" name="week_ID" id="week_ID" value="<?= $_GET["btn_week"] ?>">
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>

                    <?php
                } else if ($_GET["btn_val"] == "grade編輯") {
                    $test_ID = $_GET["btn_id"];
                    $week_ID = $_GET["btn_week"];
                    $test_name = $_GET["test_name"];
                    $tc_class = $_GET["tc_class"];

                    if ($tc_class == "admin") {
                        ?>
                            <div class="o-hidden border-0 shadow-lg my-0 padding-10px card1">
                                <b style='font-size:1.5rem;'><?= $test_name ?> 成績管理</b>
                                <table>
                                    <tr>
                                        <th>班級</th>
                                        <th>座號</th>
                                        <th>姓名</th>
                                        <th>成績</th>
                                    </tr>
                                    <?php
                                    $sql1 = "SELECT * FROM `$week_ID`";
                                    $result1 = $conn->query($sql1);
                                    if ($result1->num_rows > 0) {
                                        $_SESSION["$week_ID-$test_ID-$tc_class-stu_num"] = [];
                                        $i = 0;
                                        while ($row1 = $result1->fetch_assoc()) {
                                            $stu_name = $row1["name"];

                                            if ($stu_name == "空") {
                                            } else {
                                                $i++;
                                                $stu_classNum = $row1["classNum"];
                                                $stu_seatNum = $row1["seatNum"];

                                                $stu_grade = $row1["$test_ID"];
                                                $_SESSION["$week_ID-$test_ID-$tc_class-stu_num"][$i] = $stu_seatNum;
                                                // 設定第一個 input 的 id 為 "first"
                                                $input_id = $i == 1 ? 'id="first"' : '';
                                                echo "<tr><td>$stu_classNum</td><td>$stu_seatNum</td><td>$stu_name</td><td><input type='text' name='$stu_seatNum' size='10' class='grade-input' value='$stu_grade' $input_id></td></tr>";
                                            }
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>班級設定有誤，請洽管理員</td></tr>";
                                    }
                                    ?>
                                </table>
                            </div>
                        <?php

                    } else {


                        ?>
                            <div class="o-hidden border-0 shadow-lg my-0 padding-10px card1">
                                <b style='font-size:1.5rem;'><?= $test_name ?> 成績管理</b>
                                <form action="update_grade.php" method="POST">
                                    <table>
                                        <tr>
                                            <th>班級</th>
                                            <th>座號</th>
                                            <th>姓名</th>
                                            <th>成績</th>
                                        </tr>
                                        <?php
                                        $sql1 = "SELECT * FROM `$week_ID` WHERE `classNum` = '$tc_class'";
                                        $result1 = $conn->query($sql1);
                                        if ($result1->num_rows > 0) {
                                            $_SESSION["$week_ID-$test_ID-$tc_class-stu_num"] = [];
                                            $i = 0;
                                            while ($row1 = $result1->fetch_assoc()) {
                                                $stu_name = $row1["name"];

                                                if ($stu_name == "空") {
                                                } else {
                                                    $i++;
                                                    $stu_classNum = $row1["classNum"];
                                                    $stu_seatNum = $row1["seatNum"];

                                                    $stu_grade = $row1["$test_ID"];
                                                    $_SESSION["$week_ID-$test_ID-$tc_class-stu_num"][$i] = $stu_seatNum;
                                                    // 設定第一個 input 的 id 為 "first"
                                                    $input_id = $i == 1 ? 'id="first"' : '';
                                                    echo "<tr><td>$stu_classNum</td><td>$stu_seatNum</td><td>$stu_name</td><td><input type='text' name='$stu_seatNum' size='10' class='grade-input' value='$stu_grade' $input_id></td></tr>";
                                                }
                                            }
                                        } else {
                                            echo "<tr><td colspan='4'>班級設定有誤，請洽管理員</td></tr>";
                                        }
                                        ?>
                                    </table>
                                    <input type="hidden" name="week_ID" value="<?= $week_ID ?>">
                                    <input type="hidden" name="test_ID" value="<?= $test_ID ?>">
                                    <input type="hidden" name="tc_class" value="<?= $tc_class ?>">
                                    <input type="submit" class="btn btn-primary btn-user" value="登記">
                                </form>
                            </div>
                        <?php
                    }
                } else if ($_GET["btn_val"] == "grade_name編輯") {
                    $test_ID = $_GET["btn_id"];
                    $week_ID = $_GET["btn_week"];
                    $test_name = $_GET["test_name"];
                    $tc_class = $_GET["tc_class"];
                    ?>
                            <div class="o-hidden border-0 shadow-lg my-0 padding-10px card1">
                                <b style='font-size:1.5rem;'>名稱變更</b>
                                <form action="update_grade_name.php" method="POST">
                                    <table>
                                        <tr>
                                            <th>目前名稱</th>
                                            <td>
                                        <?= $test_name ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>更改名稱</th>
                                            <td>
                                                <input type='text' name='new_test_name' id='new_test_name' required="required">
                                                <input type="hidden" name='week_ID' value='<?= $week_ID ?>'>
                                                <input type="hidden" name='test_ID' value='<?= $test_ID ?>'>
                                                <input type='submit' id='submitBtn' class='btn btn-primary btn-user' value='儲存'>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                    <?php
                } else {
                    echo $_GET["btn_val"];
                }
            } else {
                echo "請操作";
            }
        }
        ?>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>
    $(document).ready(function () {
        $('.grade-input').keypress(function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var nextInput = $(this).closest('tr').next().find('.grade-input');
                if (nextInput.length > 0) {
                    nextInput.focus();
                }
            }
        });

        // 監聽 id="first" 的粘貼事件
        $('#first').on('paste', function (e) {
            e.preventDefault();
            var pastedData = e.originalEvent.clipboardData.getData('text');
            var rows = pastedData.split('\n');
            var seatNums = $('input.grade-input').map(function () {
                return $(this).attr('name');
            }).get();

            for (var i = 0; i < rows.length; i++) {
                if (i < seatNums.length) {
                    $('input[name="' + seatNums[i] + '"]').val(rows[i].trim());
                }
            }
        });
    });
</script>