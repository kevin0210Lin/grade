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
    <script type="text/javascript" src="index.js?v=<?php echo date('isHd'); ?>"></script>
    <link rel="icon" href="" type="image/x-icon">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <title>教師登入管理系統</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            /* Color Tokens - 現代 SaaS 色彩系統 */
            --color-primary: #3b82f6;
            --color-primary-hover: #2563eb;
            --color-primary-active: #1d4ed8;
            --color-primary-ghost: rgba(59, 130, 244, 0.08);
            
            --color-bg: #ffffff;
            --color-bg-muted: #f8fafc;
            --color-bg-light: #f3f4f6;
            
            --color-text: #0f172a;
            --color-text-secondary: #4b5563;
            --color-text-tertiary: #9ca3af;
            
            --color-border: #e5e7eb;
            --color-border-light: #f3f4f6;
            
            --color-danger: #dc2626;
            --color-danger-hover: #b91c1c;
            --color-danger-ghost: #fee2e2;
            
            --color-success: #16a34a;
            --color-success-hover: #15803d;
            --color-success-ghost: #dcfce7;
            
            --color-warning: #f59e0b;
            --color-warning-hover: #d97706;
            --color-warning-ghost: #fef3c7;
            
            --color-info: #0ea5e9;
            --color-info-hover: #0284c7;
            --color-info-ghost: #f0f9ff;
            
            /* Spacing Tokens */
            --space-xs: 4px;
            --space-sm: 8px;
            --space-md: 12px;
            --space-lg: 16px;
            --space-xl: 20px;
            --space-2xl: 24px;
            
            /* Typography Tokens */
            --text-title-page: 1.5rem;
            --text-title-section: 1.25rem;
            --text-body: 1rem;
            --text-caption: 0.875rem;
            
            --weight-regular: 400;
            --weight-medium: 500;
            --weight-semibold: 600;
            --weight-bold: 700;
            
            /* Border & Radius */
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 10px;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, "微軟正黑體", "Microsoft JhengHei", sans-serif;
            background: var(--color-bg-muted);
            min-height: 100vh;
            padding: var(--space-2xl) var(--space-lg);
            color: var(--color-text-secondary);
            line-height: 1.6;
        }

        .container { 
            max-width: 1280px; 
            margin: 0 auto; 
            display: flex; 
            flex-direction: column; 
            gap: var(--space-lg); 
        }

        .header-card {
            background: var(--color-bg);
            border-radius: var(--radius-lg);
            padding: var(--space-2xl);
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
            border: 1px solid var(--color-border);
            transition: all 0.2s ease;
        }

        .header-card:hover {
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
        }

        .header-title { 
            font-size: var(--text-title-page); 
            font-weight: var(--weight-bold); 
            color: var(--color-text); 
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-md);
            margin-bottom: var(--space-lg);
        }

        .header-title i {
            color: var(--color-text-tertiary);
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .header-title:hover i {
            color: var(--color-primary);
            transform: scale(1.08);
        }

        .header-actions {
            display: flex;
            flex-wrap: wrap;
            gap: var(--space-md);
            align-items: center;
        }

        .user-info { 
            font-size: var(--text-body); 
            color: var(--color-text-secondary); 
            margin-right: auto;
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            font-weight: var(--weight-medium);
        }

        .user-info i {
            color: var(--color-text-tertiary);
        }

        /* Button Styles */
        .btn {
            padding: 11px 20px;
            font-size: var(--text-body);
            font-weight: var(--weight-semibold);
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-sm);
            white-space: nowrap;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            line-height: 1.4;
            min-height: 42px;
            vertical-align: middle;
        }

        .btn-primary {
            background: var(--color-primary);
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(59, 130, 244, 0.2);
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--color-primary-hover);
            box-shadow: 0 2px 8px rgba(59, 130, 244, 0.3);
        }

        .btn-primary:active:not(:disabled) {
            transform: scale(0.96);
            filter: brightness(0.95);
        }

        .btn-secondary {
            background: var(--color-bg-light);
            color: var(--color-text);
            border: 1px solid var(--color-border);
        }

        .btn-secondary:hover:not(:disabled) {
            background: var(--color-bg-muted);
        }

        .btn-secondary:active:not(:disabled) {
            transform: scale(0.96);
        }

        .btn-success {
            background: var(--color-success);
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(22, 163, 74, 0.2);
        }

        .btn-success:hover:not(:disabled) {
            background: var(--color-success-hover);
            box-shadow: 0 2px 8px rgba(22, 163, 74, 0.3);
        }

        .btn-success:active:not(:disabled) {
            transform: scale(0.96);
            filter: brightness(0.95);
        }

        .btn-warning {
            background: var(--color-warning);
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(245, 158, 11, 0.2);
        }

        .btn-warning:hover:not(:disabled) {
            background: var(--color-warning-hover);
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:active:not(:disabled) {
            transform: scale(0.96);
            filter: brightness(0.95);
        }

        .btn-danger {
            background: transparent;
            color: var(--color-danger);
            border: 1px solid var(--color-danger);
        }

        .btn-danger:hover:not(:disabled) {
            background: var(--color-danger);
            color: #ffffff;
        }

        .btn-danger:active:not(:disabled) {
            transform: scale(0.96);
        }

        .btn-info {
            background: var(--color-info);
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(14, 165, 233, 0.2);
        }

        .btn-info:hover:not(:disabled) {
            background: var(--color-info-hover);
            box-shadow: 0 2px 8px rgba(14, 165, 233, 0.3);
        }

        .btn-info:active:not(:disabled) {
            transform: scale(0.96);
            filter: brightness(0.95);
        }

        .btn:focus-visible {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .table-card {
            background: var(--color-bg);
            border-radius: var(--radius-lg);
            padding: var(--space-2xl);
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
            border: 1px solid var(--color-border);
            transition: all 0.2s ease;
        }

        .table-card:hover {
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: var(--space-lg);
            border-bottom: 2px solid var(--color-primary);
            margin-bottom: var(--space-lg);
        }

        .table-title { 
            font-size: var(--text-title-section); 
            font-weight: var(--weight-bold); 
            color: var(--color-text);
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }

        .table-title i {
            color: var(--color-text-tertiary);
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .table-title:hover i {
            color: var(--color-primary);
            transform: scale(1.08);
        }

        .modern-table { 
            width: 100%; 
            border-collapse: collapse; 
        }

        .modern-table thead {
            background: var(--color-bg-muted);
        }

        .modern-table thead th { 
            color: var(--color-text); 
            padding: var(--space-lg) var(--space-md); 
            text-align: center; 
            font-weight: var(--weight-bold); 
            font-size: var(--text-caption);
            border-bottom: 2px solid var(--color-primary);
        }

        .modern-table thead th i {
            color: var(--color-text-tertiary);
            margin-right: var(--space-sm);
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .modern-table thead th:hover i {
            color: var(--color-primary);
            transform: scale(1.08);
        }

        .modern-table tbody tr:nth-child(odd) {
            background: rgba(59, 130, 244, 0.02);
        }

        .modern-table tbody tr {
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover { 
            background: var(--color-primary-ghost);
        }

        .modern-table tbody td { 
            padding: var(--space-lg) var(--space-md); 
            text-align: center; 
            color: var(--color-text-secondary); 
            border-bottom: 1px solid var(--color-border-light);
            font-weight: var(--weight-medium);
        }

        .icon-btn {
            background: var(--color-bg-light);
            border: 1px solid var(--color-border);
            cursor: pointer;
            padding: var(--space-sm) var(--space-md);
            margin-left: var(--space-sm);
            border-radius: var(--radius-sm);
            color: var(--color-text-tertiary);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .icon-btn:hover { 
            background: var(--color-bg-muted);
            color: var(--color-primary);
            border-color: var(--color-primary);
        }

        .icon-btn:active {
            transform: scale(0.96);
        }

        .icon-btn i { 
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .icon-btn:hover i {
            color: var(--color-primary);
            transform: scale(1.08);
        }

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background-color: rgba(15, 23, 42, 0.5);
            z-index: 1000;
            backdrop-filter: blur(4px);
        }

        .modal-content {
            background: var(--color-bg);
            margin: 5% auto;
            padding: var(--space-2xl);
            border-radius: var(--radius-lg);
            width: 90%;
            max-width: 640px;
            box-shadow: 0 16px 48px rgba(15, 23, 42, 0.2);
            border: 1px solid var(--color-border);
        }

        .modal-content h2 { 
            color: var(--color-text); 
            margin-bottom: var(--space-xl); 
            font-size: var(--text-title-section); 
            display: flex; 
            align-items: center; 
            gap: var(--space-sm);
            font-weight: var(--weight-bold);
        }

        .modal-content h2 i {
            color: var(--color-text-tertiary);
        }

        .form-group { 
            margin-bottom: var(--space-lg); 
        }

        .form-group label { 
            display: block; 
            margin-bottom: var(--space-sm); 
            color: var(--color-text); 
            font-weight: var(--weight-semibold);
            font-size: var(--text-body);
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group select {
            width: 100%;
            padding: var(--space-md);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            font-size: var(--text-body);
            background: var(--color-bg);
            color: var(--color-text-secondary);
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus {
            border: 2px solid var(--color-primary);
            box-shadow: 0 0 0 4px var(--color-primary-ghost);
            outline: none;
            padding: calc(var(--space-md) - 1px);
        }

        .form-group input::placeholder {
            color: var(--color-text-tertiary);
            font-style: italic;
            opacity: 0.8;
        }

        .checkbox-grid { 
            width: 100%; 
            border-collapse: collapse; 
        }

        .checkbox-grid td { 
            padding: var(--space-md); 
            text-align: left; 
            color: var(--color-text-secondary);
            font-weight: var(--weight-medium);
        }

        .checkbox-grid input[type="checkbox"] { 
            margin-right: var(--space-sm); 
            width: 18px; 
            height: 18px; 
            cursor: pointer;
            accent-color: var(--color-primary);
        }

        .modal-actions { 
            margin-top: var(--space-xl); 
            display: flex; 
            justify-content: flex-end; 
            gap: var(--space-md); 
        }

        .toast-container { 
            position: fixed; 
            bottom: 20px; 
            right: 20px; 
            z-index: 2000; 
        }

        .toast { 
            background: var(--color-success); 
            color: #fff; 
            padding: var(--space-md) var(--space-lg); 
            border-radius: var(--radius-md); 
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3); 
            margin-top: var(--space-sm);
            font-weight: var(--weight-medium);
        }

        /* Scrollbar Customization */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--color-bg-light);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--color-border);
            border-radius: 4px;
            transition: background 0.2s ease;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-primary);
        }

        @media (max-width: 768px) {
            body { padding: var(--space-lg); }
            .header-actions { flex-direction: column; align-items: flex-start; }
            .user-info { margin-right: 0; margin-bottom: var(--space-sm); }
            .modern-table { font-size: var(--text-caption); }
            .modern-table thead th, 
            .modern-table tbody td { 
                padding: var(--space-md) var(--space-sm); 
            }
            .modal-content {
                width: 95%;
                margin: 10% auto;
                padding: var(--space-xl);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Card -->
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
                <a href='index2.php' class='btn btn-secondary'>
                    <i class="fas fa-chalkboard-teacher"></i> 科任教師帳號管理
                </a>
                <a href='index3.php' class='btn btn-secondary'>
                    <i class="fas fa-users-cog"></i> 班級科任教師管理
                </a>
            </div>
        </div>

        <!-- Table Card -->
        <div class="table-card">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="fas fa-user-tie"></i> 班級教師帳號管理
                </h2>
                <button class='btn btn-success' onclick="openaddModal()">
                    <i class="fas fa-plus-circle"></i> 新增教師
                </button>
            </div>

            <table class="modern-table">
                <thead>
                    <tr>
                        <th width='15%'>任教班級</th>
                        <th width='20%'>老師名稱</th>
                        <th width='20%'>登入帳號</th>
                        <th width='25%'>任教科目</th>
                        <th width='20%'>管理</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $sql = "SELECT * FROM `junior3_login_tc` WHERE `status` = 'teacher' ORDER BY `classNum` ASC";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $num_ID = $row["num_ID"];
                        $class = $row["classNum"];
                        $login_id = $row["id"];
                        $login_pass = $row["password"];
                        $tc_name = $row["name"];
                        echo "<tr>
                                <td><strong>$class</strong></td>
                                <td>
                                    $tc_name
                                    <button class='icon-btn' onclick='openteachersetModal(event)' tcclass='$class' tcname='$tc_name' tcaccount='$login_id'";
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
                            8 => '物理',
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
                                <button class='btn btn-warning' onclick='openchangeModal(event)' tcclass='$class' tcname='$tc_name'>
                                    <i class='fas fa-exchange-alt'></i> 更換班級教師
                                </button>
                              </td>
                          </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Add Teacher Modal -->
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
                        <input type="text" id="newid" name="newid" required onchange="foremail()">
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
                        <label for="newemail">電子郵件</label>
                        <input type="email" id="newemail" name="newemail" required>
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
                                <td class="td-10" colspan="3"><b>請選擇任教科目</b></td>

                            </tr>
                            <tr>
                                <td class="td-11"><input type="checkbox" name="subject1"> 國文</td>
                                <td class="td-11"><input type="checkbox" name="subject2"> 數學</td>
                                <td class="td-11"><input type="checkbox" name="subject3"> 英文</td>
                            </tr>
                            <tr>
                                <td class="td-12"><input type="checkbox" name="subject4"> 地理</td>
                                <td class="td-12"><input type="checkbox" name="subject5"> 歷史</td>
                                <td class="td-12"><input type="checkbox" name="subject6"> 公民</td>
                            </tr>
                            <tr>
                                <td class="td-13"><input type="checkbox" name="subject7"> 生物</td>
                                <td class="td-13"><input type="checkbox" name="subject8"> 理化</td>
                                <td class="td-13"><input type="checkbox" name="subject9"> 地科</td>
                            </tr>
                        </table>

                    </div>
                    <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="btn btn-secondary" onclick="closeaddModal()">取消</button>
                        <button type="submit" class="btn btn-primary">提交</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Teacher Modal -->
        <div id="changeTeacherModal" class="modal">
            <div class="modal-content">
                <h2><i class="fas fa-exchange-alt"></i> 更換教師</h2>
                <form id="changeTeacherForm" onsubmit="event.preventDefault(); submitchangeTeacherForm();">
                    <div class="form-group">
                        <label for="oldclassNum">任教班級</label>
                        <input type="text" name="oldclassNum" value="" readonly style="background: #f7fafc;">
                    </div>
                    <div class="form-group">
                        <label for="oldteachername">原教師姓名</label>
                        <input type="text" name="oldteachername" value="" readonly style="background: #f7fafc;">
                    </div>
                    <div class="form-group">
                        <label for="newteachername">新教師姓名</label>
                        <select name="newteachername">
                            <option selected disabled>請選擇</option>
                            <?php
                            $sql = "SELECT * FROM `junior3_login_tc` WHERE `status` = 'subjectteacher' ORDER BY `name` ASC";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                $subjecttcname = $row["name"];
                                echo "<option value='$subjecttcname'>$subjecttcname</option>";
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

        <!-- Delete Teacher Modal -->
        <div id="deleteTeacherModal" class="modal">
            <div class="modal-content">
                <h2><i class="fas fa-trash-alt"></i> 刪除教師</h2>
                <form id="deleteTeacherForm" onsubmit="event.preventDefault(); submitdeleteTeacherForm();">
                    <div class="form-group">
                        <label for="oldteachernamedelete">刪除教師姓名</label>
                        <input type="text" name="oldteachernamedelete" value="" readonly style="background: #f7fafc;">
                    </div>
                    <div class="form-group">
                        <label for="oldclassNumdelete">當前任教班級</label>
                        <input type="text" name="oldclassNumdelete" value="" readonly style="background: #f7fafc;">
                    </div>
                    <div class="form-group">
                        <label for="newteachername">班級新任教師</label>
                        <select name="newteachername">
                            <option value="null" selected disabled>請選擇(預設為暫不設定)</option>
                            <option value="null">暫不設定</option>
                            <?php
                            $sql = "SELECT * FROM `junior3_login_tc` WHERE `status` = 'subjectteacher' ORDER BY `name` ASC";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                $subjecttcname = $row["name"];
                                echo "<option value='$subjecttcname'>$subjecttcname</option>";
                            }
                            ?>
                        </select>
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

        <!-- Teacher Set Modal -->
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
                            <button type="button" class="btn btn-danger" onclick="opendeleteModal(event)" tcclass=""
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