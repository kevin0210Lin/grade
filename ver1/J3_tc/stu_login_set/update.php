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

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $classNum = $_POST["classnum"];

    for ($i = 1; $i <= 9; $i++) {
        $name = $_POST["name_0" . $i];
        $id = $_POST["id_0" . $i];
        $sql = "UPDATE `junior3_login` SET `name`='$name',`loginID`='$id' WHERE `classNum` = '$classNum' AND `seatNum` = '" . "0" . "$i'";
        $conn->query($sql);
    }
    for ($i = 10; $i <= 47; $i++) {
        $name = $_POST["name_" . $i];
        $id = $_POST["id_" . $i];
        $sql = "UPDATE `junior3_login` SET `name`='$name',`loginID`='$id' WHERE `classNum` = '$classNum' AND `seatNum` = '$i'";
        $conn->query($sql);
    }

    $sql = "SELECT * FROM `junior3_week_set`";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $week_id = $row["week_ID"];
            for ($i = 1; $i <= 9; $i++) {
                $name = $_POST["name_0" . $i];
                $id = $_POST["id_0" . $i];
                $sql = "UPDATE `$week_id` SET `name`='$name' WHERE `classNum` = '$classNum' AND `seatNum` = '" . "0" . "$i'";
                $conn->query($sql);
            }
            for ($i = 10; $i <= 47; $i++) {
                $name = $_POST["name_" . $i];
                $id = $_POST["id_" . $i];
                $sql = "UPDATE `$week_id` SET `name`='$name' WHERE `classNum` = '$classNum' AND `seatNum` = '$i'";
                $conn->query($sql);
            }
        }
    }

    

    echo "<script>alert('修正已儲存');</script>";
    echo "<script>window.location.href = 'index.php';</script>";

} else {

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