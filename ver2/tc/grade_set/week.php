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
            --icon-stroke: 1.5px;  /* 一致的筆畫粗度 */
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, "微軟正黑體", "Microsoft JhengHei", sans-serif;
            background: transparent;
            color: var(--color-text-secondary);
        }

        .week-card {
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

        .week-card:hover {
            border-color: var(--color-primary-ghost);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
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

        .week-header {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--color-text);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--color-border-light);
        }

        .week-header i {
            color: var(--color-text-tertiary);
            transition: color 0.2s ease;
        }

        .week-header:hover i {
            color: var(--color-primary);
        }

        .button-group {
            display: flex;
            gap: var(--space-md);
            flex-wrap: wrap;
            margin-top: 0;
            margin-bottom: var(--space-xl);
            align-items: flex-start;
        }

        .button-group .btn {
            flex: 1;
            min-width: 200px;
            justify-content: center;
        }

        .form-table { 
            width: 100%; 
            margin-top: 0; 
            border-collapse: collapse; 
        }
        .form-table th {
            background: var(--color-bg-muted);
            color: var(--color-text);
            padding: 14px 16px;
            font-weight: var(--weight-bold);
            text-align: left;
            border-bottom: 1px solid var(--color-border);
        }
        .form-table td {
            padding: 16px;
            background: var(--color-bg);
            border-bottom: 1px solid var(--color-border);
        }

        .form-control {
            width: 100%;
            padding: var(--space-md);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            font-size: var(--text-body);
            background: var(--color-bg);
            color: var(--color-text-secondary);
            transition: all 0.2s ease;
            font-weight: var(--weight-regular);
            margin-bottom: 0;
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
            padding: calc(var(--space-md) - 1px);
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

        /* (3) SUCCESS BUTTON */
        .btn-success {
            background: var(--color-success-ghost);
            color: var(--color-success);
            border: 1.5px solid var(--color-success);
        }

        .btn-success:hover:not(:disabled) {
            background: var(--color-success);
            color: white;
            border-color: var(--color-success-hover);
        }

        .btn-success:active:not(:disabled) {
            background: var(--color-success-hover);
        }

        /* (4) ICON BUTTON - 無背景按鈕 */
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

        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px 8px;
            margin-left: var(--space-md);
            color: var(--color-text-tertiary);
            transition: all 0.2s ease;
            border-radius: var(--radius-sm);
        }
        .icon-btn:hover { 
            background: var(--color-primary-ghost);
            color: var(--color-primary);
            transform: scale(1.1);
        }

        .table-container {
            overflow-y: auto;
            overflow-x: auto;
            flex: 1;
            margin-top: 8px;
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
            transition: color 0.2s ease, transform 0.2s ease;
        }

        th:hover i {
            color: var(--color-primary);
            transform: scale(1.08);
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

        .empty-state {
            padding: var(--space-2xl);
            text-align: center;
            color: var(--color-text-tertiary);
        }

        /* Focus visible for accessibility */
        .btn:focus-visible {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
        }

        /* Week header icon animation */
        .week-header i {
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .week-header:hover i {
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
        if (isset($_GET["btn_val"])) {
            $_SESSION["tc_class"] = $_GET["tc_class"];

            $_SESSION["week_set_ID"] = $_GET["btn_id"];
            $_SESSION["week_show"] = "Y";
            $_SESSION["week_set_choose"] = $_GET["btn_val"];
            //echo $_SESSION["week_set_choose"];
            $_SESSION["week_set_week_name"] = $_GET["week_name"];
        }



        if ($_SESSION["week_show"] == "Y") {
            if ($_SESSION["week_set_choose"] == "新增區間") {
                $_SESSION["grade_show"] = "N";
                ?>
                <div class="week-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-plus-square"></i>
                            新增考試區間
                        </div>
                    </div>
                    <form action="insert_week.php" method="POST">
                        <table class="form-table">
                            <tr>
                                <th><i class="fas fa-calendar-plus"></i> 請輸入區間的名稱(日期)</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" required name="week_name" id="week_name" class="form-control" placeholder="例如：第一次段考、期中考試">
                                    <div class="button-group">
                                        <input type="submit" id='submitBtn' class="btn btn-primary" value="✓ 確認新增">
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>

                <?php
            } else if ($_SESSION["week_set_choose"] == "week編輯") {
                $classNum = $_SESSION["classNum"];
                $week_ID = $_SESSION["week_set_ID"];
                $_SESSION["grade_show"] = "Y";

                $sql1 = "SELECT * FROM `junior3_week_set` WHERE `week_ID` = '$week_ID'";
                $result1 = $conn->query($sql1);
                $row = $result1->fetch_assoc();
                $week_name = $row["week_name"];

                ?>


                    <div class='week-card'>
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fas fa-edit"></i>
                                <?= $week_name ?> 成績編輯
                            </div>
                        </div>
                        <div class="button-group">
                            <button name='set_grade' id='insert_week' class='btn btn-success' week='<?= $week_ID ?>'
                                tc_class='<?= $_SESSION["tc_class"] ?>' value='新增成績'>
                                <i class="fas fa-plus-circle"></i> 新增考試項目
                            </button>
                            <a href="average_show.php?week_ID=<?= $week_ID ?>" target="_blank" class="btn btn-secondary">
                                <i class="fas fa-chart-bar"></i> 檢視年級成績
                            </a>
                        </div>

                        <?php

                        $sql2 = "SELECT * FROM `junior3_grade_set` WHERE `week_ID` = '$week_ID' ORDER BY `test_ID` ASC";
                        $result2 = $conn->query($sql2);

                        echo "<div class='table-container'>";
                        if ($classNum == "admin") {
                            if ($result2->num_rows > 0) {
                                echo "<table><thead><tr><th><i class='fas fa-book'></i> 考試內容</th><th><i class='fas fa-cog'></i> 成績管理</th></tr></thead><tbody>";
                                while ($row2 = $result2->fetch_assoc()) {
                                    $test_ID = $row2["test_ID"];
                                    $test_name = $row2["test_name"];
                                    $test_date = $row2["test_date"];
                                    if ($test_date == '0000-00-00') {
                                        $set_test_date = '';
                                    } else {
                                        $set_test_date = (new DateTime($test_date))->format('m/d') . ' ';
                                    }
                                    echo "<tr><td>$set_test_date$test_name
                                        <button class='icon-btn' name='set_grade' id='$test_ID' value='grade_name編輯' tc_class='" . $_SESSION["tc_class"] . "' week='$week_ID' test_name='$test_name' test_date='$test_date' title='編輯考試名稱'>
                                        <i class='fas fa-edit'></i></button></td>
                                        <td><button name='set_grade' id='$test_ID' week='$week_ID' value='grade編輯' tc_class='admin' test_name='$set_test_date$test_name' class='btn btn-secondary'><i class='fas fa-tasks'></i> 管理</button></td></tr>";
                                }
                                echo "</tbody></table>";
                            } else {
                                echo "<table><thead><tr><th>考試科目/範圍/名稱</th><th>操作</th></tr></thead><tbody>
                                  <tr><td colspan='2' class='empty-state'><i class='fas fa-inbox'></i><div>尚無成績</div></td></tr></tbody></table>";
                            }
                            echo "</div>"; // Close table-container for admin

                        } else {
                            echo "<div class='table-container'>";
                            if ($result2->num_rows > 0) {
                                echo "<table><thead><tr><th><i class='fas fa-book'></i> 考試內容</th><th><i class='fas fa-chart-line'></i> 班級平均</th><th><i class='fas fa-cog'></i> 班級成績管理</th></tr></thead><tbody>";
                                while ($row2 = $result2->fetch_assoc()) {
                                    $test_ID = $row2["test_ID"];
                                    $test_name = $row2["test_name"];

                                    $test_date = $row2["test_date"];
                                    if ($test_date == '0000-00-00') {
                                        $set_test_date = '';
                                    } else {
                                        $set_test_date = (new DateTime($test_date))->format('m/d') . ' ';
                                    }
                                    
                                    $test_enter = $row2["$classNum"];
                                    if ($test_enter == "") {
                                        $test_enter = "尚未登記";
                                    }
                                    //
                                    echo "<tr><td>$set_test_date$test_name<button class='icon-btn' name='set_grade' id='$test_ID' value='grade_name編輯' tc_class='" . $_SESSION["tc_class"] . "' week='$week_ID' test_name='$test_name'  test_date='$test_date' title='編輯考試名稱'><i class='fas fa-edit'></i></button>
                                        </td>
                                        <td><strong>$test_enter</strong></td><td><button name='set_grade' id='$test_ID' week='$week_ID' value='grade編輯' tc_class='" . $_SESSION["tc_class"] . "' test_name='$set_test_date$test_name' class='btn btn-secondary'><i class='fas fa-tasks'></i> 管理</button></td></tr>";
                                }
                                echo "</tbody></table>";
                            } else {
                                echo "<table><thead><tr><th>考試科目/範圍/名稱</th><th>班級平均</th><th>操作</th></tr></thead><tbody>
                                  <tr><td colspan='3' class='empty-state'><i class='fas fa-inbox'></i><div>尚無成績</div></td></tr></tbody></table>";
                            }
                            echo "</div>"; // Close table-container for teacher
                        }
                        ?>
                    </div>
                <?php
            } else if ($_SESSION["week_set_choose"] == "week_name編輯") {
                $classNum = $_SESSION["classNum"];
                $week_ID = $_SESSION["week_set_ID"];
                $_SESSION["grade_show"] = "N";


                $sql1 = "SELECT * FROM `junior3_week_set` WHERE `week_ID` = '$week_ID'";
                $result1 = $conn->query($sql1);
                $row = $result1->fetch_assoc();
                $week_name = $row["week_name"];
                ?>
                        <div class='week-card'>
                            <div class="card-header">
                                <div class="card-title">
                                    <i class="fas fa-pen"></i>
                                    區間/日期名稱編輯
                                </div>
                            </div>
                            <form method='post' action='update_week_name.php'>
                                <table class="form-table">
                                    <tr>
                                        <th>目前名稱</th>
                                        <td>
                                            <strong><?= $week_name ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>更改名稱</th>
                                        <td>
                                            <input type='text' name='new_week_name' id='new_week_name' class="form-control" required="required" placeholder="輸入新的區間名稱">
                                            <input type="hidden" name='week_ID' value='<?= $week_ID ?>'>
                                            <div class="button-group">
                                                <input type='submit' id='submitBtn' class='btn btn-primary' value='💾 儲存變更'>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                <?php

            }
        } else {
            echo "<div class='week-card'><div class='empty-state'><i class='fas fa-hand-pointer'></i><div>請選擇操作</div></div></div>";
            $_SESSION["grade_show"] = "N";
        }


        ?>

    </div>
</body>


<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function () {
        load_data_grade();

        function load_data_grade(btn_val, btn_id, btn_week, tc_class, test_name, test_date) {
            $.ajax({
                url: "grade.php",
                method: "GET",
                data: {
                    btn_val: btn_val,
                    btn_id: btn_id,
                    btn_week: btn_week,
                    tc_class: tc_class,
                    test_name: test_name,
                    test_date: test_date
                },
                success: function (data) {
                    $('.grade').html(data); // Updated the selector here
                }
            });
        }

        $('[name="set_grade"]').click(function () {
            var btn1 = $(this).val();
            var btn2 = $(this).attr('id');
            var btn3 = $(this).attr('week');
            var btn4 = $(this).attr('tc_class');
            var btn5 = $(this).attr('test_name');
            var btn6 = $(this).attr('test_date');
            if (btn1 != '' && btn2 != '' && btn3 != '' && btn4 != '' && btn5 != '') {
                load_data_grade(btn1, btn2, btn3, btn4, btn5, btn6);
            } else {
                load_data_grade();
            }
        });

    });

    // 滚动到顶部的 JavaScript 代码
    $('button').click(function () {
        scrollToTopAndReturn();
    });

    function scrollToTopAndReturn() {
        // 滚动到顶部
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
</script>