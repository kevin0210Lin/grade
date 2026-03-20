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

/*
//老師名稱.帳號.密碼變更即時更新
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['field'], $_POST['value'], $_POST['id'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "error-21";
        exit;
    }

    $field = $_POST['field'];
    $value = $_POST['value'];
    $id = $_POST['id'];

    $sql = "UPDATE `junior3_login_tc` SET `$field` = ? WHERE `num_ID` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $value, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "更新成功";
    } else {
        echo "更新失败或無更改";
    }
    exit;
}

*/

?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../tc-shared-styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/set/style.css?v=<?php echo date('isHd'); ?>" rel="stylesheet">
    <script type="text/javascript" src="index2.js?v=<?php echo date('isHd'); ?>"></script>
    <link rel="icon" href="" type="image/x-icon">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <title>科任教師帳號管理系統</title>
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
            --warning: #f59e0b;
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

        .container { max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px; }

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
        .btn-warning { background: var(--warning); color: #1f2937; }
        .btn-primary { background: var(--primary); }
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
        .modern-table thead th { background: var(--primary); color: #fff; padding: 12px; text-align: center; font-weight: 600; font-size: 0.95rem; }
        .modern-table tbody tr { background: var(--card); }
        .modern-table tbody td { padding: 14px 12px; text-align: center; color: var(--muted); border-bottom: 1px solid var(--border); }
        .modern-table tbody tr:hover { background: #f3f4f6; }

        .icon-btn { background: none; border: none; cursor: pointer; padding: 6px; border-radius: 8px; color: var(--primary); transition: opacity 0.2s ease; }
        .icon-btn:hover { opacity: 0.8; }
        .icon-btn i { font-size: 1.1rem; }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background-color: rgba(17, 24, 39, 0.5);
            z-index: 1000;
        }

        .modal-content {
            background: var(--card);
            margin: 5% auto;
            padding: 28px;
            border-radius: 14px;
            width: 90%;
            max-width: 640px;
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.18);
        }

        .modal-content h2 { color: var(--text); margin-bottom: 20px; font-size: 1.4rem; display: flex; align-items: center; gap: 8px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; color: var(--muted); font-weight: 600; }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group select { width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 10px; font-size: 1rem; }

        .checkbox-grid { width: 100%; border-collapse: collapse; }
        .checkbox-grid td { padding: 10px; text-align: left; color: var(--muted); }
        .checkbox-grid input[type="checkbox"] { margin-right: 8px; width: 18px; height: 18px; cursor: pointer; }

        .modal-actions { margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px; }

        @media (max-width: 768px) {
            body { padding: 16px; }
            .header-actions { flex-direction: column; align-items: flex-start; }
            .user-info { margin-right: 0; }
            .modern-table { font-size: 0.9rem; }
            .modern-table thead th, .modern-table tbody td { padding: 10px 6px; }
        }
    </style>
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
                <a href='index3.php' class='btn btn-secondary'>
                    <i class="fas fa-users-cog"></i> 班級科任教師管理
                </a>
            </div>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="fas fa-chalkboard-teacher"></i> 科任教師帳號管理
                </h2>
                <button class='btn btn-success' onclick="openaddModal()">
                    <i class="fas fa-plus-circle"></i> 新增教師
                </button>
            </div>

            <table class="modern-table">
                <thead>
                    <tr>
                        <th width='25%'>老師名稱</th>
                        <th width='25%'>登入帳號</th>
                        <th width='25%'>任教科目</th>
                        <th width='25%'>管理</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $sql = "SELECT * FROM `junior3_login_tc` WHERE `status` = 'subjectteacher' ORDER BY `name` ASC";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $num_ID = $row["num_ID"];
                        $login_id = $row["id"];
                        $login_pass = $row["password"];
                        $tc_name = $row["name"];
                        echo "<tr>
                                <td>
                                    $tc_name
                                    <button class='icon-btn' onclick='openteachersetModal(event)' tcname='$tc_name' tcaccount='$login_id'";
                        for ($i = 1; $i <= 9; $i++) {
                            $subject_set = $row["subject" . $i];
                            echo " $i='$subject_set'";
                        }
                        echo " title='$tc_name 教師檔案編輯'>
                                        <i class='fas fa-edit'></i>
                                    </button>
                                </td>
                                <td>$login_id</td>";

                        echo "<td>";
                        $subjects = [
                            1 => '國文',
                            2 => '數學',
                            3 => '英文',
                            4 => '地理',
                            5 => '歷史',
                            6 => '公民',
                            7 => '生物',
                            8 => '理化',
                            9 => '地科',
                        ];

                        $subjectecho = "";

                        for ($i = 1; $i <= 9; $i++) {
                            $subject_set = $row["subject" . $i];
                            if ($subject_set == "1") {
                                $subjectecho .= $subjects[$i] . ".";
                            }
                        }
                        if ($subjectecho == "") {
                            $subjectecho = "無任教科目";
                        } else {
                            $subjectecho = rtrim($subjectecho, ".");
                        }

                        echo "$subjectecho</td>";

                        echo "<td>
                                <button class='btn btn-warning' onclick='openchangeModal(event)' tcname='$tc_name'>
                                    <i class='fas fa-exchange-alt'></i> 替換為班級教師
                                </button>
                              </td>
                          </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div id="addTeacherModal" class="modal">
            <div class="modal-content">
                <h2><i class="fas fa-user-plus"></i> 新增教師</h2>
                <form id="addTeacherForm" onsubmit="event.preventDefault(); submitAddTeacherForm();">
                    <div class="form-group">
                        <label for="newname">老師名稱</label>
                        <input type="text" id="newname" name="newname" required>
                    </div>
                    <div class="form-group">
                        <label for="newid">登入帳號</label>
                        <input type="text" id="newid" name="newid" required>
                    </div>
                    <div class="form-group">
                        <label>預設密碼</label>
                        <input type="text" value="123456" readonly style="background: #f7fafc;">
                    </div>
                    <div class="form-group">
                        <label for="position">職別</label>
                        <select name="position" id="position">
                            <option value="subjectteacher" selected>請選擇(預設為科任教師)</option>
                            <option value="subjectteacher">科任教師</option>
                            <?php
                            for ($i = 901; $i <= 911; $i++) {
                                $sql = "SELECT * FROM `junior3_login_tc` WHERE `classNum` = '$i' AND `status` = 'teacher'";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $tcname = $row["name"];
                                    echo "<option value='$i' disabled>$i 導師(目前為 $tcname 老師)</option>";
                                } else {
                                    echo "<option value='$i'>$i 導師(目前暫無資訊)</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><strong>請選擇任教科目</strong></label>
                        <table class="checkbox-grid">
                            <tr>
                                <td><input type="checkbox" name="subject1"> 國文</td>
                                <td><input type="checkbox" name="subject2"> 數學</td>
                                <td><input type="checkbox" name="subject3"> 英文</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="subject4"> 地理</td>
                                <td><input type="checkbox" name="subject5"> 歷史</td>
                                <td><input type="checkbox" name="subject6"> 公民</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="subject7"> 生物</td>
                                <td><input type="checkbox" name="subject8"> 理化</td>
                                <td><input type="checkbox" name="subject9"> 地科</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeaddModal()">
                            <i class="fas fa-times"></i> 取消
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> 提交
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="changeTeacherModal" class="modal">
            <div class="modal-content">
                <h2><i class="fas fa-exchange-alt"></i> 替換為班級教師</h2>
                <form id="changeTeacherForm" onsubmit="event.preventDefault(); submitchangeTeacherForm();">
                    <div class="form-group">
                        <label for="oldteachername">教師姓名</label>
                        <input type="text" name="oldteachername" value="" readonly style="background: #f7fafc;">
                    </div>
                    <div class="form-group">
                        <label for="classNum">替換班級</label>
                        <select name="classNum">
                            <option selected disabled>請選擇</option>
                            <?php
                            for ($i = 901; $i <= 911; $i++) {
                                $sql = "SELECT * FROM `junior3_login_tc` WHERE `classNum` = '$i' AND `status` = 'teacher'";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $classttcname = $row["name"];
                                    echo "<option value='$i'>班級:$i 老師:$classttcname</option>";
                                }else{
                                    echo "<option value='$i'>※ 班級$i 老師:暫無導師</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closechangeModal()">
                            <i class="fas fa-times"></i> 取消
                        </button>
                        <button type="button" class="btn btn-primary" onclick="submitchangeTeacherForm()">
                            <i class="fas fa-check"></i> 提交
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="deleteTeacherModal" class="modal">
            <div class="modal-content">
                <h2><i class="fas fa-trash-alt"></i> 刪除教師</h2>
                <form id="deleteTeacherForm" onsubmit="event.preventDefault(); submitdeleteTeacherForm();">
                    <div class="form-group">
                        <label for="oldteachernamedelete">刪除教師姓名</label>
                        <input type="text" name="oldteachernamedelete" value="" readonly style="background: #f7fafc;">
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closedeleteModal()">
                            <i class="fas fa-times"></i> 取消
                        </button>
                        <button type="button" class="btn btn-danger" onclick="submitdeleteTeacherForm()">
                            <i class="fas fa-trash"></i> 確認刪除
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="TeachersetModal" class="modal">
            <div class="modal-content">
                <h2><i class="fas fa-user-edit"></i> 教師資料編輯</h2>
                <form id="TeachersetForm" onsubmit="event.preventDefault(); submitTeachersetForm();">
                    <div class="form-group">
                        <label for="newteachername1">教師姓名</label>
                        <input type="text" name="newteachername1">
                        <input type="hidden" name="oldteachername1" value="">
                    </div>
                    <div class="form-group">
                        <label for="newteacheraccount1">登入帳號</label>
                        <input type="text" name="newteacheraccount1">
                        <input type="hidden" name="oldteacheraccount1" value="">
                    </div>
                    <div class="form-group">
                        <label>密碼</label>
                        <button type="button" class="btn btn-info" onclick="resetTeacherPassword(event)"
                            name="resetpass" id="reserpass" tcaccount="" tcname="">
                            <i class="fas fa-key"></i> 重置密碼
                        </button>
                    </div>
                    <div class="form-group">
                        <label><strong>任教科目</strong></label>
                        <table class="checkbox-grid">
                            <tr>
                                <td><input type="checkbox" name="setsubject1"> 國文</td>
                                <td><input type="checkbox" name="setsubject2"> 數學</td>
                                <td><input type="checkbox" name="setsubject3"> 英文</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="setsubject4"> 地理</td>
                                <td><input type="checkbox" name="setsubject5"> 歷史</td>
                                <td><input type="checkbox" name="setsubject6"> 公民</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="setsubject7"> 生物</td>
                                <td><input type="checkbox" name="setsubject8"> 理化</td>
                                <td><input type="checkbox" name="setsubject9"> 地科</td>
                            </tr>
                        </table>
                    </div>
                    <div style="display: flex; margin-top: 30px; width: 100%;">
                        <div style="display: flex; justify-content: flex-start; gap: 10px; width: 100%;">
                            <button type="button" class="btn btn-danger" onclick="opendeleteModal(event)" 
                                tcaccount="" tcname="" name="deleteaccount" id="deleteaccount">
                                <i class="fas fa-trash-alt"></i> 刪除此帳號
                            </button>
                        </div>
                        <div style="display: flex; justify-content: flex-end; gap: 10px; width: 100%;">
                            <button type="button" class="btn btn-secondary" onclick="closeTeachersetModal()">
                                <i class="fas fa-times"></i> 取消
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> 儲存
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</body>

</html>