<?php
session_start();
ini_set('display_errors', 1);
$_SESSION['login_check'] = "F";
?>

<!DOCTYPE html>
<html>

<head>
    <?php
    require_once("ad1.php");
    ?>
    <title>六和高中 國三成績管理系統</title>
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

<body class="bg-gradient-green">
    <?php
    require_once("ad2.php");
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <div class="card o-hidden border-0 shadow-lg my-0">
                    <div class="title" align="center">
                        <span>六和高中 國三成績管理系統</span>

                        <form action="login_check.php" method="post">
                            <input required="required" type="text" class="form-control form-control-user" name="id"
                                id="id" placeholder="帳號">
                            <input required="required" type="password" class="form-control form-control-user"
                                name="password" id="password" style="margin-top:10px;" placeholder="密碼">
                            <input type="submit" class="btn btn-primary btn-user" style="width:90%;border-radius:15px"
                                value="登入">
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>