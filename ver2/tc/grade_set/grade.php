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
date_default_timezone_set('Asia/Taipei');

?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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

            /* Icon System (FontAwesome 6.4.0) */
            --icon-stroke: 1.5px;
            /* 一致的筆畫粗度 */
        }

        /* ===== Card & Container System ===== */

        .grade-card {
            background: var(--color-bg);
            border-radius: var(--radius-lg);
            padding: var(--space-2xl);
            border: 1px solid var(--color-border);
            position: relative;
            overflow: hidden;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            height: calc(100vh - 200px);
            min-height: 600px;
            display: flex;
            flex-direction: column;
            gap: var(--space-xl);
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        }

        .grade-card:hover {
            border-color: var(--color-primary-ghost);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
        }

        .grade-card form {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
            /* ⭐ 關鍵 */
        }

        .table-container {
            overflow-y: auto;
            overflow-x: auto;
            flex: 1;
            min-height: 0;
            margin-top: 0;
        }


        /* Scrollbar Customization */
        .table-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: var(--color-bg-light);
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: var(--color-border);
            border-radius: 4px;
            transition: background 0.2s ease;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: var(--color-primary);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: var(--space-lg);
            border-bottom: 1px solid var(--color-border-light);
            margin-bottom: 0;
        }

        .card-title {
            font-size: var(--text-title-section);
            font-weight: var(--weight-bold);
            color: var(--color-text);
            display: flex;
            align-items: center;
            gap: var(--space-md);
            letter-spacing: -0.3px;
        }

        .card-title i {
            color: var(--color-text-tertiary);
            font-size: 1.3rem;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .card-title:hover i {
            color: var(--color-primary);
            transform: scale(1.08);
        }

        .grade-header {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--color-text);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 14px;
            border-bottom: 2px solid var(--color-border);
        }

        .grade-header i {
            color: var(--color-text-tertiary);
            transition: color 0.2s ease;
        }

        .grade-header:hover i {
            color: var(--color-primary);
        }

        .form-control {
            width: 100%;
            padding: var(--space-md) var(--space-lg);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            font-size: var(--text-body);
            background: var(--color-bg);
            color: var(--color-text);
            transition: all 0.15s ease;
            font-weight: var(--weight-regular);
        }

        .form-control::placeholder {
            color: var(--color-text-tertiary);
            font-style: italic;
            opacity: 0.8;
        }

        .form-control:focus {
            outline: none;
            border: 2px solid var(--color-primary);
            box-shadow: 0 0 0 4px var(--color-primary-ghost);
            padding: calc(var(--space-md) - 1px) calc(var(--space-lg) - 1px);
        }

        .form-control:hover:not(:focus) {
            border-color: var(--color-primary);
        }

        /* ===== Button System (3-Level Component) ===== */

        /* Base Button */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-sm);
            padding: 11px 20px;
            border: none;
            border-radius: var(--radius-md);
            font-weight: var(--weight-semibold);
            font-size: var(--text-body);
            cursor: pointer;
            transition: all 0.2s ease;
            letter-spacing: 0.2px;
            position: relative;
            overflow: hidden;
            white-space: nowrap;
            line-height: 1.4;
            min-height: 42px;
            vertical-align: middle;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* (1) PRIMARY BUTTON - 主要行動 */
        .btn-primary {
            background: var(--color-primary);
            color: #fff;
            border: none;
            box-shadow: 0 1px 3px rgba(59, 130, 244, 0.15);
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--color-primary-hover);
            box-shadow: 0 2px 6px rgba(59, 130, 244, 0.2);
        }

        .btn-primary:active:not(:disabled) {
            background: var(--color-primary-active);
            transform: scale(0.96);
            filter: brightness(0.95);
        }

        /* (2) SECONDARY BUTTON - 次要行動 (Outline) */
        .btn-secondary {
            background: transparent;
            color: var(--color-primary);
            border: 1.5px solid var(--color-primary);
            box-shadow: none;
        }

        .btn-secondary:hover:not(:disabled) {
            background: var(--color-primary-ghost);
            border-color: var(--color-primary-hover);
        }

        .btn-secondary:active:not(:disabled) {
            background: rgba(59, 130, 244, 0.12);
        }

        /* (3) ICON BUTTON - 無背景按鈕 */
        .btn-icon {
            background: transparent;
            color: var(--color-text-tertiary);
            border: none;
            padding: 8px 8px;
            min-width: 32px;
            min-height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-icon:hover:not(:disabled) {
            color: var(--color-primary);
            background: var(--color-bg-light);
        }

        /* Danger Button */
        /* Danger Button - Ghost by default */
        .btn-danger {
            background: transparent;
            color: var(--color-danger);
            border: 1.5px solid var(--color-danger);
        }

        .btn-danger:hover:not(:disabled) {
            background: var(--color-danger);
            color: #fff;
            border-color: var(--color-danger);
        }

        /* Button Sizes */
        .btn-sm {
            padding: 8px 12px;
            font-size: 0.875rem;
        }

        .btn-lg {
            padding: 12px 20px;
            font-size: 1.05rem;
        }

        .btn-block {
            width: 100%;
        }

        /* Button Group */
        .button-group {
            display: flex;
            gap: var(--space-lg);
            flex-wrap: wrap;
            justify-content: center;
            margin-top: auto;
            padding-top: var(--space-lg);
        }

        .button-group .btn {
            flex: 1;
            min-width: 140px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .button-group {
                flex-direction: column;
            }

            .button-group .btn {
                width: 100%;
            }
        }

        /* ===== Table System (Notion/Linear style) ===== */

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }

        thead {
            background: var(--color-bg-muted);
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 2px solid var(--color-primary);
        }

        th {
            padding: var(--space-lg) var(--space-md);
            text-align: center;
            color: var(--color-text);
            font-weight: var(--weight-semibold);
            font-size: var(--text-caption);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: transparent;
        }

        th i {
            color: var(--color-text-tertiary);
            margin-right: var(--space-sm);
            transition: color 0.2s ease;
        }

        th:hover i {
            color: var(--color-primary);
        }

        td {
            padding: var(--space-xl) var(--space-md);
            text-align: center;
            border-bottom: 1px solid var(--color-border-light);
            color: var(--color-text-secondary);
            background: transparent;
            font-weight: var(--weight-regular);
            vertical-align: middle;
            min-height: 44px;
        }

        tbody tr {
            transition: background-color 0.15s ease;
            background: var(--color-bg);
        }

        tbody tr:nth-child(odd) {
            background: rgba(59, 130, 244, 0.02);
        }

        tbody tr:hover {
            background: var(--color-bg-light);
        }

        .grade-input {
            width: 80px;
            padding: var(--space-sm) var(--space-md);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
            text-align: center;
            font-size: var(--text-body);
            font-weight: var(--weight-semibold);
            background: var(--color-bg);
            color: var(--color-text);
            transition: all 0.15s ease;
        }

        .grade-input:focus {
            outline: none;
            border: 2px solid var(--color-primary);
            box-shadow: 0 0 0 4px var(--color-primary-ghost);
            padding: calc(var(--space-sm) - 1px) calc(var(--space-md) - 1px);
        }

        .grade-input:hover:not(:focus) {
            border-color: var(--color-primary);
        }

        .form-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: var(--space-lg);
            margin-bottom: var(--space-xl);
            align-items: center;
            padding: var(--space-md) 0;
        }

        .form-label {
            font-weight: var(--weight-semibold);
            color: var(--color-text);
            display: flex;
            align-items: center;
            gap: var(--space-md);
            font-size: var(--text-body);
        }

        .form-label i {
            color: var(--color-text-tertiary);
            width: 20px;
            text-align: center;
            flex-shrink: 0;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .form-label:hover i {
            color: var(--color-primary);
            transform: scale(1.08);
        }

        .form-input-group {
            display: flex;
            gap: var(--space-lg);
            align-items: center;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            font-weight: var(--weight-medium);
            color: var(--color-text-secondary);
            font-size: var(--text-body);
        }

        .text-danger {
            color: var(--color-danger);
            font-size: var(--text-caption);
            margin-top: var(--space-md);
        }

        .empty-state {
            padding: var(--space-2xl);
            text-align: center;
            color: var(--color-text-tertiary);
        }

        select.form-control {
            cursor: pointer;
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--color-primary);
        }

        input[type="date"] {
            padding: var(--space-md) var(--space-lg);
            font-size: var(--text-body);
        }

        /* Focus visible for accessibility */
        .btn:focus-visible {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
        }

        /* Grade header icon animation */
        .grade-header i {
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .grade-header:hover i {
            color: var(--color-primary);
            transform: scale(1.08);
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--color-bg-light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--color-border);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-primary);
        }
    </style>
</head>

<body>
    <div align="center">
        <?php
        if ($_SESSION["grade_show"] == "Y") {
            if (isset($_GET["btn_val"])) {
                //echo $_GET["tc_class"];
        
                if ($_GET["btn_val"] == "新增成績") {
                    ?>
                    <div class="grade-card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fas fa-plus-circle"></i>
                                新增成績項目
                            </div>
                        </div>
                        <form action="insert_grade.php" method="POST">
                            <div class="form-row">
                                <div class="form-label">
                                    <i class="fas fa-calendar"></i> 考試日期
                                </div>
                                <div class="form-input-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="test_date_none" id="test_date_none">
                                        無日期
                                    </label>
                                    <input type="date" name="test_date" id="test_date" class="form-control">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-label">
                                    <i class="fas fa-book"></i> 科目選擇
                                </div>
                                <div>
                                    <select name="subject" id="subject" class="form-control" required>
                                        <option value="" disabled selected>請選擇科目</option>
                                        <option value="國文">國文</option>
                                        <option value="數學">數學</option>
                                        <option value="英文">英文</option>
                                        <option value="英聽">英聽</option>
                                        <option value="地理">地理</option>
                                        <option value="歷史">歷史</option>
                                        <option value="公民">公民</option>
                                        <option value="社會">社會</option>
                                        <option value="生物">生物</option>
                                        <option value="理化">理化</option>
                                        <option value="地科">地科</option>
                                        <option value="自然">自然</option>
                                        <option value="其他">其他</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-label">
                                    <i class="fas fa-pencil-alt"></i> 考試內容
                                </div>
                                <div>
                                    <input type="text" required name="test_name" id="test_name" class="form-control"
                                        placeholder="請輸入考試內容(不必輸入科目)">
                                    <input type="hidden" name="week_ID" id="week_ID" value="<?= $_GET["btn_week"] ?>">
                                </div>
                            </div>

                            <div class="button-group">
                                <input type="submit" id='submitBtn' class="btn btn-primary" value="✓ 確認新增">
                            </div>

                            <script>
                                document.getElementById("test_date_none").addEventListener("change", function () {
                                    const dateInput = document.getElementById("test_date");
                                    if (this.checked) {
                                        dateInput.disabled = true;   // 不能輸入
                                        dateInput.value = "0000-00-00"; // 清空避免送出
                                    } else {
                                        dateInput.disabled = false;  // 可以輸入
                                        dateInput.value = "";  // 可以輸入
                                    }
                                });
                            </script>
                        </form>
                    </div>

                    <?php
                } else if ($_GET["btn_val"] == "grade編輯") {
                    $test_ID = $_GET["btn_id"];
                    $week_ID = $_GET["btn_week"];
                    $test_name = $_GET["test_name"];
                    $tc_class = $_GET["tc_class"];

                    if ($tc_class == "admin") {
                        ?>
                            <div class="grade-card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <i class="fas fa-clipboard-list"></i>
                                    <?= $test_name ?> 成績管理
                                    </div>
                                </div>
                                <div class="table-container">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th><i class="fas fa-school"></i> 班級</th>
                                                <th><i class="fas fa-hashtag"></i> 座號</th>
                                                <th><i class="fas fa-user"></i> 姓名</th>
                                                <th><i class="fas fa-chart-line"></i> 成績</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql1 = "SELECT * FROM `$week_ID`";
                                            $result1 = $conn->query($sql1);
                                            if ($result1->num_rows > 0) {
                                                $_SESSION["$week_ID-$test_ID-$tc_class-stu_num"] = [];
                                                $i = 0;
                                                while ($row1 = $result1->fetch_assoc()) {
                                                    $stu_name = $row1["name"];

                                                    if ($stu_name == "空") {
                                                    } else {
                                                        $i++;
                                                        $stu_classNum = $row1["classNum"];
                                                        $stu_seatNum = $row1["seatNum"];

                                                        $stu_grade = (float) $row1["$test_ID"];
                                                        $_SESSION["$week_ID-$test_ID-$tc_class-stu_num"][$i] = $stu_seatNum;
                                                        // 設定第一個 input 的 id 為 "first"
                                                        $input_id = $i == 1 ? 'id="first"' : '';
                                                        echo "<tr><td>$stu_classNum</td><td>$stu_seatNum</td><td>$stu_name</td><td><input type='number' name='$stu_seatNum' min='0' max='100' class='grade-input' value='$stu_grade' $input_id></td></tr>";
                                                    }
                                                }
                                            } else {
                                                echo "<tr><td colspan='4' class='empty-state'><i class='fas fa-exclamation-triangle'></i><div>班級設定有誤，請洽管理員</div></td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div> <!-- Close table-container -->
                            </div>
                        <?php

                    } else {


                        ?>
                            <div class="grade-card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <i class="fas fa-clipboard-list"></i>
                                    <?= $test_name ?> 成績管理
                                    </div>
                                </div>
                                <form action="update_grade.php" method="POST">
                                    <div class="table-container">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th><i class="fas fa-school"></i> 班級</th>
                                                    <th><i class="fas fa-hashtag"></i> 座號</th>
                                                    <th><i class="fas fa-user"></i> 姓名</th>
                                                    <th><i class="fas fa-chart-line"></i> 成績</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql1 = "SELECT * FROM `$week_ID` WHERE `classNum` = '$tc_class'";
                                                $result1 = $conn->query($sql1);
                                                if ($result1->num_rows > 0) {
                                                    $_SESSION["$week_ID-$test_ID-$tc_class-stu_num"] = [];
                                                    $i = 0;
                                                    while ($row1 = $result1->fetch_assoc()) {
                                                        $stu_name = $row1["name"];

                                                        if ($stu_name == "空") {
                                                        } else {
                                                            $i++;
                                                            $stu_classNum = $row1["classNum"];
                                                            $stu_seatNum = $row1["seatNum"];

                                                            $stu_grade = $row1["$test_ID"];
                                                            $_SESSION["$week_ID-$test_ID-$tc_class-stu_num"][$i] = $stu_seatNum;
                                                            // 設定第一個 input 的 id 為 "first"
                                                            $input_id = $i == 1 ? 'id="first"' : '';
                                                            echo "<tr><td>$stu_classNum</td><td>$stu_seatNum</td><td>$stu_name</td><td><input type='number' name='{$stu_seatNum}' min='0' max='100' step='0.5' class='grade-input' value='{$stu_grade}' oninput=\"this.setCustomValidity(''); if (this.value > 100) { this.setCustomValidity('成績不可超過 100 分'); }\" {$input_id}></td></tr>";
                                                        }
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='4' class='empty-state'><i class='fas fa-exclamation-triangle'></i><div>班級設定有誤，請洽管理員</div></td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div> <!-- Close table-container -->
                                    <input type="hidden" name="week_ID" value="<?= $week_ID ?>">
                                    <input type="hidden" name="test_ID" value="<?= $test_ID ?>">
                                    <input type="hidden" name="tc_class" value="<?= $tc_class ?>">
                                    <div class="button-group">
                                        <input type="submit" class="btn btn-primary" value="💾 登記成績">
                                    </div>
                                </form>
                            </div>
                        <?php
                    }
                } else if ($_GET["btn_val"] == "grade_name編輯") {
                    $test_ID = $_GET["btn_id"];
                    $week_ID = $_GET["btn_week"];
                    $get_test_name = $_GET["test_name"];
                    $test_date = $_GET["test_date"];

                    if ($test_date == '0000-00-00') {
                        $test_date_show = '無日期';
                        $test_date_check = 1;
                    } else {
                        $test_date_show = (new DateTime($test_date))->format('m/d') . ' ';
                        $test_date_check = 0;
                    }
                    $tc_class = $_GET["tc_class"];
                    $subject = mb_substr($get_test_name, 0, 2, 'UTF-8');
                    $test_name = mb_substr($get_test_name, 2, null, 'UTF-8');
                    ?>
                            <div class="grade-card">
                                <div class="card-header">
                                    <div class="card-title">
                                        <i class="fas fa-edit"></i>
                                        名稱變更
                                    </div>
                                </div>
                                <form action="update_grade_name.php" method="POST">
                                    <div class="table-container">
                                        <div class="form-row">
                                            <div class="form-label">
                                                <i class="fas fa-calendar"></i> 考試日期
                                            </div>
                                            <div>
                                                <strong><?= $test_date_show ?></strong>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-label">
                                                <i class="fas fa-book"></i> 科目
                                            </div>
                                            <div>
                                                <strong><?= $subject ?></strong>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-label">
                                                <i class="fas fa-info-circle"></i> 目前名稱
                                            </div>
                                            <div>
                                                <strong><?= $test_name ?></strong>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-label">
                                                <i class="fas fa-calendar-alt"></i> 更新日期
                                            </div>
                                            <div class="form-input-group">
                                                <label class="checkbox-label">
                                                    <input type="checkbox" name="test_date_none" id="test_date_none_update"
                                                <?= $test_date_check ? 'checked' : '' ?>> 無日期</label>
                                                <input type="date" name="test_date" id="test_date_update" class="form-control"
                                                    value="<?= ($test_date && $test_date != "0000-00-00") ? $test_date : "" ?>">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-label">
                                                <i class="fas fa-pen"></i> 更新內容
                                            </div>
                                            <div>
                                                <select name="new_subject" id="new_subject" class="form-control" required
                                                    style="margin-bottom: 10px;">
                                                    <option value="<?= $subject ?>" selected><?= $subject ?></option>
                                                    <option value="國文">國文</option>
                                                    <option value="數學">數學</option>
                                                    <option value="英文">英文</option>
                                                    <option value="英聽">英聽</option>
                                                    <option value="地理">地理</option>
                                                    <option value="歷史">歷史</option>
                                                    <option value="公民">公民</option>
                                                    <option value="社會">社會</option>
                                                    <option value="生物">生物</option>
                                                    <option value="理化">理化</option>
                                                    <option value="地科">地科</option>
                                                    <option value="自然">自然</option>
                                                    <option value="其他">其他</option>
                                                </select>
                                                <input type='text' name='new_test_name' id='new_test_name' class="form-control"
                                                    required="required" value="<?= $test_name ?>" placeholder="輸入考試內容">
                                                <input type="hidden" name='week_ID' value='<?= $week_ID ?>'>
                                                <input type="hidden" name='test_ID' value='<?= $test_ID ?>'>
                                                <p class='text-danger'>⚠️ 如需刪除該成績，請洽管理員</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="button-group">
                                        <input type='submit' id='submitBtn' class='btn btn-primary' value='💾 儲存變更'>
                                    </div>

                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            const checkbox = document.getElementById("test_date_none_update");
                                            const dateInput = document.getElementById("test_date_update");

                                            function toggleDateInput() {
                                                if (checkbox.checked) {
                                                    dateInput.disabled = true;
                                                    dateInput.value = "";
                                                } else {
                                                    dateInput.disabled = false;
                                                    dateInput.value = "<?= ($test_date && $test_date != "0000-00-00") ? $test_date : "" ?>";
                                                }
                                            }

                                            checkbox.addEventListener("change", toggleDateInput);
                                            toggleDateInput(); // 初始化
                                        });
                                    </script>
                                </form>
                            </div>
                    <?php
                } else {
                    echo $_GET["btn_val"];
                }
            } else {
                echo "<div class='grade-card'><div class='empty-state'><i class='fas fa-hand-pointer'></i><div>請選擇操作</div></div></div>";
            }
        }
        ?>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>
    $(document).ready(function () {
        $('.grade-input').keypress(function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var nextInput = $(this).closest('tr').next().find('.grade-input');
                if (nextInput.length > 0) {
                    nextInput.focus();
                }
            }
        });

        // 監聽 id="first" 的粘貼事件
        $('#first').on('paste', function (e) {
            e.preventDefault();
            var pastedData = e.originalEvent.clipboardData.getData('text');
            var rows = pastedData.split('\n');
            var seatNums = $('input.grade-input').map(function () {
                return $(this).attr('name');
            }).get();

            for (var i = 0; i < rows.length; i++) {
                if (i < seatNums.length) {
                    $('input[name="' + seatNums[i] + '"]').val(rows[i].trim());
                }
            }
        });
    });
</script>