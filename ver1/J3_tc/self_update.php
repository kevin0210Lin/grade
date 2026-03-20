<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once("../set.php");
} else {
    echo "<script>window.location.href = 'self_index.php';</script>";
}
$d = $_POST["d"];

$sql = "SELECT * FROM `Self_study_subject` WHERE `control` = 1 ORDER BY `ID` ASC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $id = $row["ID"];
    $value = $_POST[$id];
    $sql = "UPDATE `Self_study_subject` SET `$d`='$value' WHERE `ID` = '$id';";
    $conn->query($sql);
}
$conn->close();
echo "<script>alert('您已更新解題科目，大自習的電腦也請重新整理頁面才會更新喔~');</script>";
echo "<script>window.location.href = 'self_control.php?d=$d';</script>";
?>

<head>
    <?php
    require_once("ad1.php");
    ?>
</head>

<body>
    <?php
    require_once("ad2.php");
    ?>
</body>