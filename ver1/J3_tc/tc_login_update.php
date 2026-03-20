<?php
require_once("../set.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_id = $_POST["old_id"];
    $old_pass = $_POST["old_pass"];
    $new_id = $_POST["new_id"];
    $new_pass = $_POST["new_pass"];
    $new_pass_check = $_POST["new_pass_check"];

    if ($new_pass != $new_pass_check) {
        echo "<script>alert('密碼不一致，請重新輸入');</script>";
        echo "<script>window.location.href = 'tc_login.php';</script>";
    } else {
        echo $new_id . $new_pass . $new_pass_check;
        $sql = "UPDATE `junior3_login_tc` SET `id`='$new_id',`password`='$new_pass' WHERE `id`= '$old_id' AND `password`= '$old_pass'";
        $conn->query( $sql );
        echo "<script>alert('帳號密碼更新完畢，請重新登入');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }
} else {
    echo "<script>window.location.href = 'index.php';</script>";
}
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