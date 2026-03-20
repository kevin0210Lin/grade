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

    if (isset($_POST["test_date_none"])) {
        $test_date = '';
    } else {
        $test_date = $_POST["test_date"] ?? null;
    }

    $subject = $_POST["subject"];
    $test_name = $_POST["test_name"];
    $week_ID = $_POST["week_ID"];
    $insert_time = date('Y/m/d H:i:s');


    // 找這個 week_ID 最大的 test_ID
    $sql_max = "SELECT COALESCE(MAX(test_ID), 0) + 1 AS new_id 
            FROM junior3_grade_set 
            WHERE week_ID = ?";
    $stmt_max = $conn->prepare($sql_max);
    $stmt_max->bind_param("i", $week_ID);
    $stmt_max->execute();
    $result = $stmt_max->get_result();
    $row = $result->fetch_assoc();
    $test_id_new = $row["new_id"];
    $stmt_max->close();

    // === sql1: INSERT ===
    $sql1 = "INSERT INTO `junior3_grade_set` 
        (`week_ID`, `test_ID`, `test_name`, `test_date`, `subject`, `average`, `insert_time`, `last_set_time`, `insert_people`,
         `901`, `902`, `903`, `904`, `905`, `906`, `907`, `908`, `909`, `910`, `911`, `tc_check`) 
    VALUES (?, ?, ?, ?, ?, '0', ?, ?, ?, '', '', '', '', '', '', '', '', '', '', '', '')";

    $stmt1 = $conn->prepare($sql1);
    $test_name_full = $subject . '' . $test_name;
    $stmt1->bind_param("iissssss", $week_ID, $test_id_new, $test_name_full, $test_date, $subject, $insert_time, $insert_time, $name);
    $stmt1->execute();
    $stmt1->close();


    $sql2 = "ALTER TABLE `" . $conn->real_escape_string($week_ID) . "` 
             ADD `" . $conn->real_escape_string($test_id_new) . "` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `name`";

    $conn->query($sql2);

    $sql3 = "ALTER TABLE `" . $conn->real_escape_string($week_ID) . "` 
         ADD `per_" . $conn->real_escape_string($test_id_new) . "` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `" . $conn->real_escape_string($test_id_new) . "`";
    $conn->query($sql3);

    $sql4 = "UPDATE `junior3_week_set` SET `last_set_time` = ? WHERE `week_ID` = ?";
    $stmt4 = $conn->prepare($sql4);
    $stmt4->bind_param("si", $insert_time, $week_ID);
    $stmt4->execute();
    $stmt4->close();

    $sql5 = "UPDATE `junior3_week_set` SET `grade_insert_check` = '0' WHERE `week_ID` = ?";
    $stmt5 = $conn->prepare($sql5);
    $stmt5->bind_param("i", $week_ID);
    $stmt5->execute();
    $stmt5->close();


    echo "<script>window.location.href = 'index.php';</script>";
} else {
    echo "<script>alert('執行錯誤');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}
