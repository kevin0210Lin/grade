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
$name = $_SESSION["name"]
    ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $week_name = $_POST["week_name"];
    $insert_time = date('Y/m/d H:i:s');

    $sql_id_count = "SELECT * FROM `junior3_week_set`";
    $result_id_count = $conn->query($sql_id_count);
    $id_last = 0;
    if ($result_id_count->num_rows > 0) {
        while ($row = $result_id_count->fetch_assoc()) {
            if ($row["week_ID"] > $id_last) {
                $id_last = $row["week_ID"];
            }
        }
        $id_new = $id_last + 1;
    } else {
        $id_new = 1;
    }
    $sql_insert = "INSERT INTO `junior3_week_set` (`week_ID`, `week_name`, `insert_time`, `last_set_time`, `insert_people`,`grade_insert_check`) VALUES ('$id_new', '$week_name', '$insert_time', '$insert_time', '$name','0');";

    $sql_create_table = "CREATE TABLE `$id_new`(
        `classNum` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        `seatNum` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        `name` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        `SUM` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        `ave` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        `gradeRank` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        `gradeRankPer` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        `classRank` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
        `classRankPer` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL)";

    if ($conn->query($sql_insert) === TRUE && $conn->query($sql_create_table) === TRUE) {

        $sql_stu = "SELECT * FROM `junior3_login` WHERE `status` = 'student'";
        $result_stu = $conn->query($sql_stu);
        while ($row = $result_stu->fetch_assoc()) {
            $stu1 = $row["classNum"];
            $stu2 = $row["seatNum"];
            $stu3 = $row["name"];
            $sql = "INSERT INTO `$id_new`(`classNum`, `seatNum`, `name`, `SUM`, `ave`, `gradeRank`, `gradeRankPer`, `classRank`, `classRankPer`) VALUES ('$stu1', '$stu2', '$stu3','0','0','0','0','0','0');";
            $conn->query($sql);
        }

    }
    echo "<script>window.location.href = 'index.php';</script>";
} else {
    echo "<script>alert('執行錯誤');</script>";
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