<?php
ini_set('display_errors', 1);
session_start();
$old_id = $_SESSION["old_id"];
$old_pass = $_SESSION["old_pass"];

if (isset($_SESSION['login_check'])) {
    if ($_SESSION['login_check'] != "T") {
        echo "<script>alert('您尚未登入');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }
} else {
    echo "<script>alert('您尚未登入');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}
?>

<head>
    <?php
    require_once("ad1.php");
    ?>
    <title>TC 密碼變更</title>
    <meta charset="UTF-8">
    <link href="/set/style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="" type="image/x-icon">
    <style>
        body {
            background-color: #f8f9fc;
            font-family: '微軟正黑體', Arial, sans-serif;
            display: flex;
            height: 100vh;
            width: 100%;
            margin: 0;
        }

        .container {
            text-align: center;
            width: 80%;
            margin-top: 120px;
            /*  上方外距 16px  */
            margin-bottom: auto;
            /*  下方外距 16px  */
            margin-left: auto;
            /*  左方外距 18px  */
            margin-right: auto;
            /*  右方外距 18px  */
        }

        .card {
            border: 0;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .title {
            margin-top: 0px;
            margin-bottom: 20px;
            text-align: center;
            line-height: 60px;
            text-indent: 0px;
            padding-top: 20px;
        }

        .title span {
            font-size: 2.6em;
            font-weight: bold;
            color: #4e73df;
        }

        .p-4 {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control-user {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: #ffffff;
            border-radius: 20px;
            padding: 1rem;
            font-size: 1.2rem;
            line-height: 1.6;
            color: #55595c;
            width: 90%;
            margin: auto;
        }

        body.parent {
            display: flex;
            /* 水平置中 */
            justify-content: center;
            /* 垂直置中 */
            align-items: center;
        }
    </style>
</head>

<body>
    <?php
    require_once("ad2.php");
    ?>

    <body class="bg-gradient-green">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-11">
                    <div class="card o-hidden border-0 shadow-lg my-0">
                        <div class="title" align="center">
                            <span>密碼變更</span>

                            <form action="tc_login_update.php" method="post">
                                <input type="password" required placeholder="請輸入新帳號"
                                    class="form-control form-control-user" name="new_id">
                                <input type="password" required placeholder="請輸入新密碼"
                                    class="form-control form-control-user" style="margin-top:10px;" name="new_pass">
                                <input type="password" required placeholder="請再次輸入新密碼"
                                    class="form-control form-control-user" style="margin-top:10px;"
                                    name="new_pass_check">

                                <input type="hidden" name="old_id" value="<?= $old_id ?>">
                                <input type="hidden" name="old_pass" value="<?= $old_pass ?>">

                                <input type="submit" class="btn btn-primary btn-user" value="儲存">
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </body>
</body>