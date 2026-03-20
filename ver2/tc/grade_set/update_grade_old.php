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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $test_ID = $_POST["test_ID"];
    $week_ID = $_POST["week_ID"];
    $tc_class = $_POST["tc_class"];
    $update_OK = 0;

    $class_SUM = 0;
    $count_grade = 0;

    for ($i = 1; $i <= count($_SESSION["$week_ID-$test_ID-$tc_class-stu_num"]); $i++) {
        $stu_seatNum = $_SESSION["$week_ID-$test_ID-$tc_class-stu_num"][$i];
        $stu_grade = $_POST["$stu_seatNum"];

        if (!is_numeric($stu_grade)) {
            $stu_grade = "";
        }

        if ($stu_grade != "") {
            $count_grade++;
        }

        $class_SUM = round($class_SUM + floatval($stu_grade), 2);

        $sql1 = "UPDATE `$week_ID` SET `$test_ID` = '$stu_grade' WHERE `classNum` = '$tc_class' AND `seatNum` = '$stu_seatNum'";
        if ($conn->query($sql1) === TRUE) {
            $update_OK++;
        }
    }

    if ($count_grade == 0) {
        $count_grade++;
    }

    $class_ave = round(($class_SUM / $count_grade), 2);

    $now_time = date('Y/m/d H:i:s');
    $sql2 = "UPDATE `junior3_grade_set` SET `$tc_class` = '$class_ave' , `last_set_time` = '$now_time' WHERE `week_ID` = '$week_ID' AND `test_ID` = '$test_ID'";
    $sql3 = "UPDATE `junior3_week_set` SET `last_set_time` = '$now_time' WHERE `week_ID` = '$week_ID'";

    if ($update_OK == count($_SESSION["$week_ID-$test_ID-$tc_class-stu_num"]) && $conn->query($sql2) === TRUE && $conn->query($sql3) === TRUE) {
        $sql_tc_check = "SELECT * FROM `junior3_grade_set` WHERE `week_ID` = '$week_ID' AND `test_ID` = '$test_ID'";
        $result_tc_check = $conn->query($sql_tc_check);
        $row_tc_check = $result_tc_check->fetch_assoc();

        $tc_check = 0;
        for ($class = 901; $class <= 911; $class++) {
            if ($row_tc_check["$class"] != "") {
                $tc_check++;
            }
        }

        $now_time = date('Y/m/d H:i:s');

        $sql4 = "UPDATE `junior3_grade_set` SET `tc_check` = '$tc_check' , `last_set_time` = '$now_time' WHERE `week_ID` = '$week_ID' AND `test_ID` = '$test_ID'";

        if ($conn->query($sql4) === TRUE) {
            if ($tc_check == 11) {

                $sql_stu_grade = "SELECT * FROM `$week_ID`";
                $result_stu_grade = $conn->query($sql_stu_grade);

                $a = 0;
                $grade = [];
                $classNum = [];
                $seatNum = [];
                $name = [];

                while ($row_grade = $result_stu_grade->fetch_assoc()) {
                    $a++;

                    $classNum[$a] = $row_grade["classNum"];
                    $seatNum[$a] = $row_grade["seatNum"];
                    $name[$a] = $row_grade["name"];
                    $grade[$a] = $row_grade["$test_ID"];
                }

                // 計算單一個成績中，學生在年級中的排名
                // 添加排名
                // 創建一個關聯數組來存儲每個學生的分數
                $total_scores = array();
                foreach ($grade as $key => $value) {
                    if (is_numeric($value) && $value > 0) {
                        $total_scores[$key] = $value;
                    }
                }

                // 根據總分進行降序排序
                arsort($total_scores);

                // 初始化排名
                $rank = 1;
                // 遍歷排序後的數組，為每個學生分配名次和百分比
                $total_students = count($total_scores);
                $last_score = null;
                $last_rank = null;

                foreach ($total_scores as $key => $total_score) {
                    if ($total_score === $last_score) {
                        $percentage = round(($last_rank / $total_students) * 100);
                    } else {
                        $percentage = round(($rank / $total_students) * 100);
                        $last_rank = $rank;
                    }

                    if ($percentage > 85) {
                        $percentage = "再加油";
                    } else if ($percentage == 0) {
                        $percentage = "1%";
                    } else {
                        $percentage = $percentage . "%";
                    }

                    // 仅更新有成绩的学生的百分比
                    $sql = "UPDATE `$week_ID` SET `per_$test_ID` = '$percentage' WHERE `classNum` = '{$classNum[$key]}' AND `seatNum` = '{$seatNum[$key]}' AND `name` = '{$name[$key]}'";
                    $conn->query($sql); // 更新全年級排名

                    $last_score = $total_score;
                    $rank++;
                }

                // 将没有成绩的学生的 `per` 字段设置为空
                foreach ($grade as $key => $value) {
                    if (!is_numeric($value) || $value <= 0) {
                        $sql = "UPDATE `$week_ID` SET `per_$test_ID` = '' WHERE `classNum` = '{$classNum[$key]}' AND `seatNum` = '{$seatNum[$key]}' AND `name` = '{$name[$key]}'";
                        $conn->query($sql);
                    }
                }

                echo "<script>window.location.href = 'grade_all_check.php?week_ID=$week_ID';</script>";

            } else {
                echo "<script>window.location.href = 'index.php';</script>";
            }
        }
    }
} else {
    echo "<script>alert('執行錯誤');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}
?>
