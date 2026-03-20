<?php
require_once("../../set.php");

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // 生成 CSRF Token
}

if (isset($_SESSION['login_check'])) {
    if ($_SESSION['login_check'] != "T") {
        echo "<script>alert('您尚未登入');</script>";
        echo "<script>window.location.href = '../index.php';</script>";
    }
} else {
    echo "<script>alert('您尚未登入');</script>";
    echo "<script>window.location.href = '../index.php';</script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['field'], $_POST['value'], $_POST['id'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "error-21";
        exit;
    }

    $field = $_POST['field'];
    $value = $_POST['value'];
    $id = $_POST['id'];

    $sql = "UPDATE `junior3_subjecttc` SET `$field` = ? WHERE `classNum` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $value, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "更新成功";
    } else {
        echo "更新失敗或無更改";
    }
    exit;
}


?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../tc-shared-styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/set/style.css?v=<?php echo date('isHd'); ?>" rel="stylesheet">
    <script type="text/javascript" src="index.js?v=<?php echo date('isHd'); ?>"></script>
    <link rel="icon" href="" type="image/x-icon">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <title>班級科任教師管理</title>
    <style>
        :root {
            --bg: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            --card: #ffffff;
            --text: #111827;
            --muted: #4b5563;
            --border: #e5e7eb;
            --primary: #2563eb;
            --accent: #0ea5e9;
            --success: #10b981;
            --danger: #ef4444;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', 'Microsoft JhengHei', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            min-height: 100vh;
            padding: 24px;
            color: var(--text);
        }

        .container { max-width: 1400px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px; }

        .header-card {
            background: var(--card);
            border-radius: 14px;
            padding: 24px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .header-title { font-size: 1.8rem; font-weight: 700; color: var(--text); text-align: center; }
        .header-actions { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
        .user-info { font-size: 1rem; color: var(--muted); margin-right: auto; }

        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #fff;
            transition: background-color 0.2s ease, opacity 0.2s ease;
        }

        .btn-danger { background: var(--danger); }
        .btn-info { background: var(--accent); }
        .btn-secondary { background: #6b7280; }
        .btn-success { background: var(--success); }
        .btn:hover { opacity: 0.9; }

        .table-card {
            background: var(--card);
            border-radius: 14px;
            padding: 24px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .table-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 12px; border-bottom: 1px solid var(--border); }
        .table-title { font-size: 1.3rem; font-weight: 700; color: var(--text); }

        .modern-table { width: 100%; border-collapse: collapse; }
        .modern-table thead th { background: var(--primary); color: #fff; padding: 12px 10px; text-align: center; font-weight: 600; font-size: 0.9rem; }
        .modern-table tbody tr { background: var(--card); }
        .modern-table tbody td { padding: 12px 10px; text-align: center; color: var(--muted); border-bottom: 1px solid var(--border); }
        .modern-table tbody td:first-child { font-weight: 700; background: #f9fafb; }
        .modern-table tbody tr:hover { background: #f3f4f6; }

        .modern-table select {
            width: 100%;
            padding: 8px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.95rem;
            background: #fff;
        }

        .toast-container { position: fixed; bottom: 20px; right: 20px; z-index: 2000; }
        .toast { background: var(--success); color: #fff; padding: 12px 18px; border-radius: 10px; box-shadow: var(--shadow); margin-top: 8px; }

        @media (max-width: 1200px) { .modern-table { font-size: 0.9rem; } }
        @media (max-width: 768px) {
            body { padding: 16px; }
            .header-actions { flex-direction: column; align-items: flex-start; }
            .user-info { margin-right: 0; }
            .container { padding: 0; }
            .table-card { padding: 16px; overflow-x: auto; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll("select[name='classsubjecttcset']").forEach((selectElement) => {
                selectElement.addEventListener("change", function () {
                    const selectedValue = this.value; // 获取选中的值
                    const cclass = this.getAttribute("cclass"); // 获取班级编号
                    const subject = this.getAttribute("ssubject"); // 获取科目编号
                    const csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute("content"); // 获取 CSRF Token

                    // 发送 AJAX 请求
                    fetch("", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: new URLSearchParams({
                            csrf_token: csrfToken,
                            field: `subject${subject}`,
                            value: selectedValue,
                            id: cclass,
                        }),
                    })
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error("Network response was not ok");
                            }
                            return response.text();
                        })
                        .then((data) => {
                            if (data === "更新成功") {
                                showToast("更新成功！");
                            } else {
                                showToast("更新失敗：" + data);
                            }
                        })
                        .catch((error) => {
                            console.error("更新錯誤：", error);
                            alert("更新失敗，請稍後再試！");
                        });
                });
            });
        });

        function showToast(message) {
            const container = document.querySelector('.toast-container') || createToastContainer();
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.textContent = message;

            container.appendChild(toast);
            setTimeout(() => {
                toast.style.transition = "opacity 0.5s";
                toast.style.opacity = 0;
                setTimeout(() => container.removeChild(toast), 500);
            }, 4500);
        }

        function createToastContainer() {
            const container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
            return container;
        }
    </script>
</head>

<body>
    <div class="container">
        <div class="header-card">
            <h1 class="header-title">
                <i class="fas fa-school"></i> 六和高中 國三成績管理系統
            </h1>
            <?php
            $classNum = $_SESSION["classNum"];
            $name = $_SESSION["name"];
            ?>
            <div class="header-actions">
                <span class="user-info">
                    <i class="fas fa-user-circle"></i> <?= $name ?> 您好
                </span>
                <a href='../index.php' class='btn btn-danger'>
                    <i class="fas fa-sign-out-alt"></i> 登出
                </a>
                <a href='../result.php' class='btn btn-info'>
                    <i class="fas fa-home"></i> 返回主頁面
                </a>
                <a href='./' class='btn btn-secondary'>
                    <i class="fas fa-user-tie"></i> 班級教師帳號管理
                </a>
                <a href='index2.php' class='btn btn-secondary'>
                    <i class="fas fa-chalkboard-teacher"></i> 科任教師帳號管理
                </a>
            </div>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="fas fa-users-cog"></i> 班級科任教師管理
                </h2>
            </div>

            <table class="modern-table">
                <thead>
                    <tr>
                        <th width='10%'>班級</th>
                        <th width='10%'>國文</th>
                        <th width='10%'>數學</th>
                        <th width='10%'>英文</th>
                        <th width='10%'>地理</th>
                        <th width='10%'>歷史</th>
                        <th width='10%'>公民</th>
                        <th width='10%'>生物</th>
                        <th width='10%'>理化</th>
                        <th width='10%'>地科</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    for ($cclassNum = 901; $cclassNum <= 911; $cclassNum++) {
                        echo "<tr>";
                        echo "<td><strong>$cclassNum</strong></td>";
                        for ($aa = 1; $aa <= 9; $aa++) {
                            echo "<td><select name='classsubjecttcset' cclass='$cclassNum' ssubject='$aa'>";
                            $a = 0;

                            $sql = "SELECT * FROM `junior3_subjecttc` WHERE `classNum` = '$cclassNum'";
                            $result = $conn->query($sql);
                            $row = $result->fetch_assoc();
                            $subjectstc = $row["subject" . $aa];
                            echo "<optgroup label='當前教師'>";
                            echo "<option value='$subjectstc' selected>$subjectstc</option>";
                            echo "</optgroup>";
                            echo "<optgroup label='其他教師'>";

                            $sql = "SELECT * FROM `junior3_login_tc` WHERE `subject" . $aa . "`='1'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $a++;
                                    $name = $row["name"];
                                    echo "<option value='$name'>$name</option>";
                                }
                            } else {
                                echo "<option>無科目教師</option>";
                            }
                            echo "</optgroup>";
                            echo "</select></td>";
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
                <form id="addTeacherForm" onsubmit="event.preventDefault(); submitAddTeacherForm();">


                    <div style="margin-bottom: 10px;">
                        <table style="width:100%;">

                    </div>
                    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn btn-secondary" onclick="closeaddModal()">取消</button>
                        <button type="submit" class="btn btn-primary">提交</button>
                    </div>
                </form>
            </div>
        </div>


    </div>
</body>

</html>