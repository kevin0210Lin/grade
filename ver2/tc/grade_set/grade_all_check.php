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

<?php
if ($_GET["week_ID"] != "") {
echo "成績計算中，請稍後。";

    $week_ID = $_GET["week_ID"];
    //echo $week_ID;

    $sql_week_grade = "SELECT * FROM `junior3_grade_set` WHERE `week_ID` = '$week_ID' ORDER BY `test_ID` ASC";
    $result_week_grade = $conn->query($sql_week_grade);
    $grade_row_num = $result_week_grade->num_rows;

    $week_check = 0;
    while ($row1 = $result_week_grade->fetch_assoc()) {
        if ($row1["tc_check"] == 11) {
            $week_check++;

            $test_ID = $row1["test_ID"];

            $sql_grade_ave = "SELECT * FROM `$week_ID`";
            $result_grade_ave = $conn->query($sql_grade_ave);

            $stu_grade_sum = 0;
            $stu_count_num = 0;

            while ($row2 = $result_grade_ave->fetch_assoc()) {
                $stu_grade = $row2["$test_ID"];
                //echo $row2["name"];
                //echo $stu_grade;
                $stu_grade_sum = $stu_grade_sum + floatval($stu_grade);

                if (is_numeric($stu_grade)) {
                    $stu_count_num++;
                }
            }

            $stu_average = $stu_count_num > 0 ? round(($stu_grade_sum / $stu_count_num),2) : 0;

            //echo $stu_average."<br>";

            $sql_update_grade_ave = "UPDATE `junior3_grade_set` SET `average` = '$stu_average' WHERE `week_ID` = '$week_ID' AND `test_ID` = '$test_ID'";
            $conn->query($sql_update_grade_ave);
        }
    }

    if ($grade_row_num == $week_check) {
        $sql = "UPDATE `junior3_week_set` SET `grade_insert_check` = '1' WHERE `week_ID` = '$week_ID'";
        $conn->query($sql);
        echo "<script>window.location.href = 'week_all_check.php?week_ID=$week_ID';</script>";
    } else {
        //echo "<script>alert('登記成功');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }

} else {
    echo "<script>alert('執行錯誤');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}