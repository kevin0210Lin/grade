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

if ($_GET["week_ID"] != "") {
    echo "成績計算中，請稍後。";
    $week_ID = $_GET["week_ID"];

    $sql_grade = "SELECT * FROM `junior3_grade_set` WHERE `week_ID` = '$week_ID' ORDER BY `test_ID` ASC";
    $result_grade = $conn->query($sql_grade);
    $i = 0;
    $row_ID = [];
    while ($row1 = $result_grade->fetch_assoc()) {
        $i++;
        $row_ID[$i] = $row1["test_ID"];
    }

    $sql_stu_grade = "SELECT * FROM `$week_ID`";
    $result_stu_grade = $conn->query($sql_stu_grade);

    $a = 0;
    $sum = [];
    $average = [];
    $classNum = [];
    $seatNum = [];
    $name = [];

    // 取總成績
    while ($row_grade = $result_stu_grade->fetch_assoc()) {
        $a++;
        $sum[$a] = 0;

        $classNum[$a] = $row_grade["classNum"];
        $seatNum[$a] = $row_grade["seatNum"];
        $name[$a] = $row_grade["name"];

        $count = 0;
        for ($i = 1; $i <= count($row_ID); $i++) {
            // 檢查成績是否為數字
            if (is_numeric($row_grade[$row_ID[$i]])) {
                $sum[$a] += floatval($row_grade[$row_ID[$i]]);
                $count++;
            }
        }

        // 確保除數不為零
        $average[$a] = $count > 0 ? $sum[$a] / $count : 0;

        // 更新資料庫中的總分與平均分
        $sql = "UPDATE `$week_ID` SET `SUM` = '" . $sum[$a] . "' , `ave` ='" . $average[$a] . "' WHERE `classNum` = '" . $classNum[$a] . "' AND `seatNum` = '" . $seatNum[$a] . "' AND `name` = '" . $name[$a] . "'";
        $conn->query($sql);
    }

    // 計算總分平均
    $total_sum = array_sum($sum);
    $total_count = count(array_filter($sum, function ($value) {
        return $value != 0;
    }));
    $total_average = $total_count > 0 ? round($total_sum / $total_count, 2) : 0;

    $sql = "UPDATE `junior3_week_set` SET `week_ave`='$total_average' WHERE `week_ID`='$week_ID'";
    $conn->query($sql);

    // 添加年级排名
    $total_scores = array();
    foreach ($sum as $key => $value) {
        if ($value > 0) { // 如果總分大於 0 才加入排名
            $total_scores[$key] = $value;
        }
    }

    arsort($total_scores);
    $rank = 1;
    $total_students = count($total_scores);
    foreach ($total_scores as $key => $total_score) {
        $percentage = round(($rank / $total_students) * 100);

        if ($percentage > 85) {
            $percentage = "再加油";
        } else if ($percentage == 0) {
            $percentage = "1%";
        } else {
            $percentage = $percentage . "%";
        }

        $sql = "UPDATE `$week_ID` SET `gradeRank` = '$rank' , `gradeRankPer` ='$percentage' WHERE `classNum` = '{$classNum[$key]}' AND `seatNum` = '{$seatNum[$key]}' AND `name` = '{$name[$key]}'";
        $conn->query($sql);
        $rank++;
    }
    echo "<br>";

    // 計算班級排名
    foreach (array_unique($classNum) as $class) {
        $sql_class_stu_grade = "SELECT * FROM `$week_ID` WHERE `classNum` = '$class'";
        $result_class_stu_grade = $conn->query($sql_class_stu_grade);

        $class_sum = [];
        $class_average = [];
        $class_seatNum = [];
        $class_name = [];
        $b = 0;

        while ($row_class_grade = $result_class_stu_grade->fetch_assoc()) {
            $b++;
            $class_sum[$b] = 0;

            $class_seatNum[$b] = $row_class_grade["seatNum"];
            $class_name[$b] = $row_class_grade["name"];

            $count = 0;
            for ($i = 1; $i <= count($row_ID); $i++) {
                if (is_numeric($row_class_grade[$row_ID[$i]])) {
                    $class_sum[$b] += floatval($row_class_grade[$row_ID[$i]]);
                    $count++;
                }
            }

            $class_average[$b] = $count > 0 ? $class_sum[$b] / $count : 0;

            $sql = "UPDATE `$week_ID` SET `SUM` = '" . $class_sum[$b] . "' , `ave` ='" . $class_average[$b] . "' WHERE `classNum` = '$class' AND `seatNum` = '" . $class_seatNum[$b] . "' AND `name` = '" . $class_name[$b] . "'";
            $conn->query($sql);
        }

        arsort($class_sum);
        $rank = 1;
        $class_students = count($class_sum);
        foreach ($class_sum as $key => $class_score) {
            $percentage = round(($rank / $class_students) * 100);

            if ($percentage > 85) {
                $percentage = "再加油";
            } else if ($percentage >= 70) {
                $percentage = "71%~85%";
            } else if ($percentage >= 60) {
                $percentage = "61%~70%";
            } else if ($percentage >= 50) {
                $percentage = "51%~60%";
            } else if ($percentage >= 40) {
                $percentage = "41%~50%";
            } else if ($percentage >= 30) {
                $percentage = "31%~40%";
            } else if ($percentage >= 20) {
                $percentage = "21%~30%";
            } else if ($percentage >= 10) {
                $percentage = "11%~20%";
            } else if ($percentage >= 0) {
                $percentage = "1%~10%";
            }

            $sql = "UPDATE `$week_ID` SET `classRank` = '$rank' , `classRankPer` ='$percentage' WHERE `classNum` = '$class' AND `seatNum` = '{$class_seatNum[$key]}' AND `name` = '{$class_name[$key]}'";
            $conn->query($sql);
            $rank++;
        }
    }

    echo "<script>window.location.href = 'index.php';</script>";
}
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
</body>