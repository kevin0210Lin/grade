<?php
require_once("../../set.php");
require_once("lang.php");

if (isset($_SESSION['login_check'])) {
    if ($_SESSION['login_check'] != "T") {
        echo "<script>alert('" . _lang('not_logged_in') . "');</script>";
        echo "<script>window.location.href = '../index.php';</script>";
    }
} else {
    echo "<script>alert('" . _lang('not_logged_in') . "');</script>";
    echo "<script>window.location.href = '../index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="<?php echo substr($language, 0, 2); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../tc-shared-styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/set/style.css" rel="stylesheet">
    <link rel="icon" href="" type="image/x-icon">
    <title><?php echo _lang('page_title'); ?></title>
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
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, "微軟正黑體", "Microsoft JhengHei", sans-serif;
            background: var(--color-bg-muted);
            min-height: 100vh;
            padding: var(--space-2xl);
            color: var(--color-text);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: var(--space-lg);
        }

        /* ===== Header Card ===== */
        .header-card {
            background: var(--color-bg);
            border-radius: var(--radius-lg);
            padding: var(--space-2xl);
            border: 1px solid var(--color-border);
            display: flex;
            flex-direction: column;
            gap: var(--space-md);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        }

        .header-card:hover {
            border-color: var(--color-primary-ghost);
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
            justify-content: center;
        }

        .user-info {
            font-size: var(--text-body);
            color: var(--color-text-secondary);
            margin-right: auto;
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }

        /* ===== Button System ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-sm);
            padding: 10px 16px;
            border: none;
            border-radius: var(--radius-md);
            font-weight: var(--weight-semibold);
            font-size: var(--text-body);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Primary Button */
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

        /* Secondary Button */
        .btn-info {
            background: var(--color-info);
            color: #ffffff;
            box-shadow: 0 1px 3px rgba(14, 165, 233, 0.2);
        }

        .btn-info:hover:not(:disabled) {
            background: var(--color-primary-ghost);
            border-color: var(--color-primary-hover);
        }

        .btn-info:active:not(:disabled) {
            background: rgba(59, 130, 244, 0.12);
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

        /* ===== Table Card ===== */
        .table-card {
            background: var(--color-bg);
            border-radius: var(--radius-lg);
            padding: var(--space-2xl);
            border: 1px solid var(--color-border);
            display: flex;
            flex-direction: column;
            gap: var(--space-lg);
            transition: border-color 0.2s ease;
            animation: slideInUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: hidden;
        }

        .table-card>* {
            animation-fill-mode: backwards;
        }

        .table-card:hover {
            border-color: var(--color-primary-ghost);
        }

        /* ===== Slide In Animation ===== */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: var(--space-lg);
            border-bottom: 1px solid var(--color-border-light);
            animation: slideInLeft 0.6s ease 0.1s backwards;
        }

        .table-title {
            font-size: var(--text-title-section);
            font-weight: var(--weight-bold);
            color: var(--color-text);
            display: flex;
            align-items: center;
            gap: var(--space-md);
            animation: slideInLeft 0.5s ease 0.15s backwards;
        }

        .table-title i {
            color: var(--color-text-tertiary);
            transition: color 0.2s ease;
            animation: scaleIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s backwards;
        }

        .table-title:hover i {
            color: var(--color-primary);
        }

        /* ===== Modern Table ===== */
        .modern-table {
            width: 100%;
            border-collapse: collapse;
            animation: scaleIn 0.6s ease 0.25s backwards;
        }

        .modern-table thead th {
            background: var(--color-bg-muted);
            color: var(--color-text);
            padding: var(--space-lg);
            text-align: center;
            font-weight: var(--weight-bold);
            font-size: var(--text-body);
            border-bottom: 2px solid var(--color-primary);
            animation: slideInDown 0.5s ease 0.3s backwards;
        }

        .modern-table thead th:nth-child(1) {
            animation-delay: 0.3s;
        }

        .modern-table thead th:nth-child(2) {
            animation-delay: 0.35s;
        }

        .modern-table thead th:nth-child(3) {
            animation-delay: 0.4s;
        }

        .modern-table thead th:nth-child(4) {
            animation-delay: 0.45s;
        }

        .modern-table tbody tr {
            background: var(--color-bg);
            animation: slideInLeft 0.6s ease backwards;
            transition: all 0.3s ease;
        }

        .modern-table tbody tr:nth-child(1) {
            animation-delay: 0.35s;
        }

        .modern-table tbody tr:nth-child(2) {
            animation-delay: 0.4s;
        }

        .modern-table tbody tr:nth-child(3) {
            animation-delay: 0.45s;
        }

        .modern-table tbody tr:nth-child(4) {
            animation-delay: 0.5s;
        }

        .modern-table tbody tr:nth-child(5) {
            animation-delay: 0.55s;
        }

        .modern-table tbody tr:nth-child(n+6) {
            animation-delay: 0.6s;
        }

        .modern-table tbody tr:hover {
            background: var(--color-bg-light);
            transform: translateX(4px);
        }

        /* ===== Form Control ===== */
        .modern-table input[type="text"] {
            width: 100%;
            padding: var(--space-md);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            font-size: var(--text-body);
            background: var(--color-bg);
            color: var(--color-text-secondary);
            transition: all 0.2s ease;
            font-weight: var(--weight-regular);
            animation: slideInRight 0.5s ease backwards;
        }

        .modern-table tbody tr:nth-child(1) input {
            animation-delay: 0.45s;
        }

        .modern-table tbody tr:nth-child(2) input {
            animation-delay: 0.5s;
        }

        .modern-table tbody tr:nth-child(3) input {
            animation-delay: 0.55s;
        }

        .modern-table tbody tr:nth-child(4) input {
            animation-delay: 0.6s;
        }

        .modern-table tbody tr:nth-child(5) input {
            animation-delay: 0.65s;
        }

        .modern-table tbody tr:nth-child(n+6) input {
            animation-delay: 0.7s;
        }

        .modern-table input[type="text"]::placeholder {
            color: var(--color-text-tertiary);
            font-style: italic;
            opacity: 0.8;
        }

        .modern-table input[type="text"]:focus {
            outline: none;
            border: 2px solid var(--color-primary);
            box-shadow: 0 0 0 4px var(--color-primary-ghost);
            padding: calc(var(--space-md) - 1px);
        }

        .modern-table input[type="text"]:hover:not(:focus) {
            border-color: var(--color-primary);
        }

        /* ===== Submit Container ===== */
        .submit-container {
            text-align: center;
            margin-top: var(--space-lg);
            animation: slideInUp 0.6s ease 0.75s backwards;
        }

        .btn-submit {
            padding: 10px 16px;
            font-size: var(--text-body);
            transition: all 0.3s ease;
            animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.8s backwards;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 244, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* ===== Notice ===== */
        .admin-notice {
            background: var(--color-success-ghost);
            color: var(--color-success-hover);
            padding: var(--space-lg);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-success);
            text-align: center;
            font-size: var(--text-body);
            font-weight: var(--weight-semibold);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-md);
            animation: slideInDown 0.6s ease 0.2s backwards;
        }

        .admin-notice i {
            font-size: 1.1rem;
            animation: scaleIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.25s backwards;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .admin-notice i {
            font-size: 1.1rem;
        }

        /* Focus visible for accessibility */
        .btn:focus-visible {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
        }

        .table-title i {
            color: var(--color-text-tertiary);
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .table-title:hover i {
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
    <div class="container">
        <div class="header-card">
            <h1 class="header-title">
                <i class="fas fa-school"></i> <?php echo _lang('school_name'); ?>
            </h1>
            <?php
            $classNum = $_SESSION["classNum"];
            $name = $_SESSION["name"];

            echo "<div class='header-actions'>";
            echo "<span class='user-info'><i class='fas fa-user-circle'></i> $name " . _lang('hello') . "</span>";
            echo "<a href='../index.php' class='btn btn-danger'><i class='fas fa-sign-out-alt'></i> " . _lang('logout') . "</a>";
            echo "<a href='../result.php' class='btn btn-info'><i class='fas fa-home'></i> " . _lang('back_to_home') . "</a>";
            echo "</div>";
            ?>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="fas fa-user-graduate"></i> <?php echo _lang('student_login_management'); ?>
                </h2>
            </div>

            <?php
            if ($classNum == "admin") {
                $sql = "SELECT * FROM `junior3_login`";
            } else {
                $sql = "SELECT * FROM `junior3_login` WHERE classNum = '$classNum'";
            }
            $result = $conn->query($sql);

            if ($classNum == "admin") {
                echo "<div class='admin-notice'>";
                echo "<i class='fas fa-exclamation-triangle'></i> " . _lang('admin_cannot_modify');
                echo "</div>";
            } else {
                echo "<form action='update.php' method='post'>";
            }

            echo "<table class='modern-table'>";
            echo "<thead><tr>";
            echo "<th width='25%'>" . _lang('class') . "</th>";
            echo "<th width='25%'>" . _lang('seat_number') . "</th>";
            echo "<th width='25%'>" . _lang('name') . "</th>";
            echo "<th width='25%'>" . _lang('login_password') . "</th>";
            echo "</tr></thead>";
            echo "<tbody>";

            while ($row = $result->fetch_assoc()) {
                $stu_class = $row["classNum"];
                $stu_name = $row["name"];
                $stu_seatNum = $row["seatNum"];
                $stu_id = $row["loginID"];
                echo "<tr>";
                echo "<td><strong>$stu_class</strong></td>";
                echo "<td><strong>$stu_seatNum</strong></td>";
                echo "<td><input type='text' name='name_$stu_seatNum' value='$stu_name' required></td>";
                echo "<td><input type='text' name='id_$stu_seatNum' value='$stu_id' required></td>";
                echo "</tr>";
            }

            echo "</tbody></table>";

            if ($classNum == "admin") {
                echo "<div class='admin-notice' style='margin-top: 20px;'>";
                echo "<i class='fas fa-exclamation-triangle'></i> " . _lang('admin_cannot_modify');
                echo "</div>";
            } else {
                echo "<input type='hidden' name='classnum' value='$classNum'>";
                echo "<div class='submit-container'>";
                echo "<button type='submit' class='btn btn-primary btn-submit'>";
                echo "<i class='fas fa-save'></i> " . _lang('save_changes');
                echo "</button>";
                echo "</div>";
                echo "</form>";
            }
            ?>
        </div>
    </div>

</body>

</html>