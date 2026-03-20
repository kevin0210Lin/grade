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
$name = $_SESSION["name"];
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $test_name = $_POST["test_name"];
    $week_ID = $_POST["week_ID"];
    $insert_time = date('Y/m/d H:i:s');

    $sql_id_count = "SELECT * FROM `junior3_grade_set` WHERE `week_ID` = $week_ID";
    $result_id_count = $conn->query($sql_id_count);
    $id_last = 0;
    if ($result_id_count->num_rows > 0) {
        while ($row = $result_id_count->fetch_assoc()) {
            if ($row["test_ID"] > $id_last) {
                $id_last = $row["test_ID"];
            }
        }
        $id_new = $id_last + 1;
    } else {
        $id_new = 1;
    }

    $sql1 = "INSERT INTO `junior3_grade_set` (`week_ID`, `test_ID`, `test_name`, `average`, `insert_time`, `last_set_time`, `insert_people`, `901`, `902`, `903`, `904`, `905`, `906`, `907`, `908`, `909`, `910`, `911`, `tc_check`) 
    VALUES ('$week_ID', '$id_new', '$test_name', '0', '$insert_time', '$insert_time', '$name', '', '', '', '', '', '', '', '', '', '', '', '');";

    if ($id_last == 0) {
        $sql2 = "ALTER TABLE `$week_ID` ADD `$id_new` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `name`;";
    } else {
        $sql2 = "ALTER TABLE `$week_ID` ADD `$id_new` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `per_$id_last`;";
    }

    $sql3 = "ALTER TABLE `$week_ID` ADD `per_$id_new` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `$id_new`;";
    
    $sql4 = "UPDATE `junior3_week_set` SET `last_set_time` = '$insert_time' WHERE `week_ID` = '$week_ID'";

    $conn->query($sql1);
    $conn->query($sql2);
    $conn->query($sql3);
    $conn->query($sql4);

    $sql5 = "UPDATE `junior3_week_set` SET `grade_insert_check` = '0' WHERE `week_ID` = $week_ID";
    $conn->query($sql5);

    echo "<script>window.location.href = 'index.php';</script>";
}else{
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