<?php
require_once("set.php");

if (!isset($_SESSION['login_check']) || $_SESSION['login_check'] !== "T") {
    echo "<script>alert('您尚未登入');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}

$classNum = $_SESSION["classNum"];
$seatNum = $_SESSION["seatNum"];
$name = $_SESSION["name"];

$week_ID = isset($_GET["week"]) ? $conn->real_escape_string($_GET["week"]) : '';
if ($week_ID === '') {
    echo "<script>alert('未指定週次');</script>";
    echo "<script>window.location.href = 'result.php';</script>";
    exit;
}

$_SESSION["week_ID_choose"] = $week_ID;

$sqlWeek = "SELECT * FROM `junior3_week_set` WHERE week_ID = '$week_ID'";
$resultWeek = $conn->query($sqlWeek);
if (!$resultWeek || $resultWeek->num_rows === 0) {
    echo "<script>alert('查無週次資料');</script>";
    echo "<script>window.location.href = 'result.php';</script>";
    exit;
}

$row_week = $resultWeek->fetch_assoc();
$week_name = $row_week["week_name"];
$week_show_check = $row_week["grade_insert_check"];
$week_average = $row_week["week_ave"];

$gradeMeta = [];
$gradeQuery = "SELECT * FROM `junior3_grade_set` WHERE week_ID = '$week_ID' ORDER BY test_ID ASC";
$gradeResult = $conn->query($gradeQuery);
if ($gradeResult) {
    while ($row = $gradeResult->fetch_assoc()) {
        $labelDate = $row["test_date"] === '0000-00-00' ? '' : (new DateTime($row["test_date"]))->format('m/d ');
        $gradeMeta[] = [
            'id' => $row["test_ID"],
            'label' => $labelDate . $row["test_name"],
            'show' => $row["tc_check"],
            'average' => $row["average"],
        ];
    }
}

$studentRow = null;
$studentSql = "SELECT * FROM `$week_ID` WHERE `name`='$name' AND `classNum` = '$classNum' AND `seatNum` = '$seatNum'";
$studentResult = $conn->query($studentSql);
if ($studentResult && $studentResult->num_rows > 0) {
    $studentRow = $studentResult->fetch_assoc();
}

if (!$studentRow) {
    echo "<script>alert('查無個人成績資料');</script>";
    echo "<script>window.location.href = 'result.php';</script>";
    exit;
}

$signature = $studentRow["sign"] ?? '';
$signatureText = "尚未簽名";
if ($signature) {
    if (preg_match('/\\d{14}/', $signature, $matches)) {
        $formatted = DateTime::createFromFormat('YmdHis', $matches[0]);
        $signatureText = $formatted ? $formatted->format('Y/m/d H:i:s') : '已簽名';
    } else {
        $signatureText = "已簽名";
    }
}

$statusLabel = $week_show_check == 1 ? '已結算' : '成績計算中';
$statusClass = $week_show_check == 1 ? 'positive' : 'warning';

// 每日會考戰鬥格言
$dailyMottos = [
    '💪 每一分都算數，今天的努力是明天的榮光！',
    '🚀 考試不是終點，它是你實力的展示台！',
    '✨ 別讓失敗定義你，讓進步激勵你！',
    '⚡ 成績只是數字，你的潛力無限大！',
    '🎯 專注於改善，而不是糾結於分數！',
    '🌟 每次考試都是學習，每個成績都有故事！',
    '💡 困難是機會，挑戰是成長的階梯！',
    '🏆 冠軍不是一次性的，是持續的進步！',
    '🔥 用汗水澆灌夢想的種子！',
    '🎪 考試是遊戲，你是主角，寫下自己的傳奇！',
    '💎 每一個低谷都是下一個高峰的起點！',
    '🌈 失敗只是成功的彩排！',
    '🎬 你的故事才剛開始，最好的還在後頭！',
    '⚙️ 考試是校準，不是判決！',
    '🌱 種子今天播，豐收在明天！',
    '🎓 明天的成功，源於今天的堅持！',
    '💫 希望不會自己來敲門，你得靠堅持去開啟它！',
    '🔑 青春就是用來挑戰的，別讓懶惰偷走你的光芒！',
    '🏔️ 想要登頂，就得一步一腳印地爬！',
    '🚪 第一步最難，但踏出去就不一樣了！',
    '📖 每一道題的掌握，都是你進步的證明！',
    '🎪 別人在努力時你就在休息，怎麼能期待逆轉？',
    '⏰ 短暫的努力，換來長遠的輕鬆！',
    '👑 別低估自己，你的能力遠比想像中強大！',
    '🎢 這是改變人生軌道的機會，別只是路過！',
    '🎭 平時的每一道題，都是為了舞台上的精彩表現！',
    '🎁 握緊你的夢想，堅持到最後一刻！',
    '📝 會考準備就像築堡壘，一塊磚都不能馬虎！',
    '🎯 會考模擬考就是真戰，每次都要全力以赴！',
    '🔄 複習不是重複，而是知識的深化與強化！',
    '💻 解題是技能，而會考就是檢驗你技能的舞台！',
    '🧠 把會考當成打怪升級，每科都是一個副本！',
    '⏳ 會考倒計時，每一分鐘都是寶貴的投資！',
    '📊 錯題本就是你的會考攻略指南！',
    '🎓 會考不是終局，是通往夢想的大門！',
    '🔔 複習計畫一旦定下，就要堅定執行！',
    '🏅 會考考的是知識，更考的是你的心理素質！',
    '🧩 把每一科當成拼圖，到會考時拼出完整的自己！',
    '⚔️ 題海不是苦海，而是成功的必經之路！',
    '🚀 會考時間有限，但你的潛力無限！',
    '💼 複習計畫要精細，執行要堅決！',
    '🎬 每一次月考都是會考的預告片！',
    '🌟 會考準備充分的人，考場上才能心如止水！',
    '🎯 會考倒數，別計算失去的時間，專注剩下的機會！',
    '🔥 你的會考夢想，值得你現在的每一份努力！',
    '📚 把教科書變成你的私人家教，會考會感謝你！',
];

$dailyMotto = $dailyMottos[array_rand($dailyMottos)];

$s_sum = ($studentRow["SUM"] ?? '') ?: '-';
$s_ave = ($studentRow["ave"] ?? '') ?: '-';
$s_gradeRank = ($studentRow["gradeRankPer"] ?? '') ?: '-';
$s_classRankPer = ($studentRow["classRankPer"] ?? '') ?: '-';
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $week_name; ?> - 成績查詢</title>
    <link rel="icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --navy: #0f172a;
            --accent: #3b82f6;
            --positive: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --muted: #64748b;
            --border-light: rgba(148, 163, 184, 0.15);
            --gradient-blue: linear-gradient(135deg, rgba(59, 130, 244, 0.08), rgba(59, 130, 244, 0.04));
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Microsoft JhengHei", sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: 100vh;
            color: var(--navy);
            line-height: 1.6;
        }

        .page {
            animation: fadeInUp 0.6s ease-out;
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 20px;
            display: flex;
            flex-direction: column;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(0.98);
            }
        }

        .topbar {
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.06), rgba(59, 130, 244, 0.02));
            border-radius: 16px;
            padding: 20px 28px;
            margin-bottom: 32px;
            box-shadow: 0 6px 20px rgba(59, 130, 244, 0.12);
            border: 2px solid rgba(59, 130, 244, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideInDown 0.6s ease-out;
            gap: 20px;
            position: relative;
            overflow: hidden;
            order: -11;
        }

        .topbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--accent), #2563eb);
        }

        .brand {
            flex: 1;
        }

        .brand-text h1 {
            font-size: 28px;
            font-weight: 950;
            color: var(--navy);
            margin: 0;
            letter-spacing: -1.2px;
            line-height: 1.2;
        }

        .brand-text p {
            font-size: 13px;
            color: var(--muted);
            margin: 4px 0 0 0;
            font-weight: 600;
        }

        .topbar-meta {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .user-chip {
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.15), rgba(59, 130, 244, 0.08));
            border: 2px solid rgba(59, 130, 244, 0.25);
            border-radius: 11px;
            padding: 11px 16px;
            color: var(--navy);
            font-weight: 700;
            box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.5);
            transition: all 0.2s ease;
            text-align: center;
            min-width: 150px;
        }

        .user-chip:hover {
            border-color: rgba(59, 130, 244, 0.3);
            box-shadow: 0 4px 12px rgba(59, 130, 244, 0.12);
            transform: translateY(-1px);
        }

        .user-chip strong {
            display: block;
            color: var(--navy);
            font-size: 14px;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 3px;
            text-align: center;
        }

        .user-chip span {
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            line-height: 1.2;
            display: block;
            text-align: center;
        }

        .actions {
            display: flex;
            gap: 10px;
            align-items: center;
            width: 100%;
        }

        .btn {
            padding: 10px 16px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .btn-ghost {
            background: rgba(59, 130, 244, 0.1);
            color: var(--accent);
            border: 1.5px solid rgba(59, 130, 244, 0.25);
        }

        .btn-ghost:hover {
            background: rgba(59, 130, 244, 0.18);
            border-color: rgba(59, 130, 244, 0.4);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 244, 0.15);
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1.5px solid rgba(239, 68, 68, 0.25);
        }

        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.18);
            border-color: rgba(239, 68, 68, 0.4);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
        }

        .btn-secondary {
            background: rgba(100, 116, 139, 0.1);
            color: var(--muted);
            border: 1.5px solid rgba(100, 116, 139, 0.25);
        }

        .btn-secondary:hover {
            background: rgba(100, 116, 139, 0.18);
            border-color: rgba(100, 116, 139, 0.4);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(100, 116, 139, 0.15);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-badge.status-positive {
            background: rgba(16, 185, 129, 0.15);
            color: var(--positive);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-badge.status-warning {
            background: rgba(245, 158, 11, 0.15);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .diff-positive {
            color: var(--positive);
            font-weight: 900;
        }

        .diff-negative {
            color: #ef4444;
            font-weight: 900;
        }

        .diff-zero {
            color: var(--muted);
            font-weight: 900;
        }

        .score-cell-bg-personal {
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.04), rgba(59, 130, 244, 0.01));
        }

        .score-cell-bg-avg {
            background: linear-gradient(135deg, rgba(107, 114, 128, 0.04), rgba(107, 114, 128, 0.01));
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #2563eb);
            color: white;
            border: 1.5px solid transparent;
            box-shadow: 0 4px 14px rgba(59, 130, 244, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 244, 0.35);
        }

        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 24px;
            box-shadow: 0 2px 12px rgba(59, 130, 244, 0.08);
            border: 1.5px solid rgba(59, 130, 244, 0.1);
            animation: fadeInUp 0.6s ease-out;
        }

        .card.priority-high {
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.02), rgba(59, 130, 244, 0.01));
            border: 2px solid rgba(59, 130, 244, 0.2);
            box-shadow: 0 4px 16px rgba(59, 130, 244, 0.12);
            order: -10;
        }

        .card.summary-section {
            background: #ffffff;
            border: 1.5px solid rgba(59, 130, 244, 0.1);
            position: relative;
            overflow: hidden;
            order: -10;
            box-shadow: 0 2px 12px rgba(59, 130, 244, 0.08);
            padding: 0;
        }

        .card.summary-section .card-header {
            padding: 28px 28px 16px 28px;
            margin: 0;
            border-bottom: 1.5px solid rgba(59, 130, 244, 0.1);
        }

        .card.table-card {
            order: -5;
            padding: 0;
            overflow: hidden;
        }

        .card.signature-card {
            background: #ffffff;
            border: 1.5px solid rgba(59, 130, 244, 0.1);
            position: relative;
            order: -3;
            box-shadow: 0 2px 12px rgba(59, 130, 244, 0.08);
        }

        .card.signature-card::before {
            display: none;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1.5px solid rgba(59, 130, 244, 0.1);
        }

        .card-header h2 {
            font-size: 24px;
            font-weight: 950;
            color: var(--navy);
            margin-bottom: 2px;
            letter-spacing: -0.8px;
        }

        .card-header h3 {
            font-size: 20px;
            font-weight: 900;
            color: var(--navy);
            margin-bottom: 0px;
            letter-spacing: -0.5px;
        }

        .card-header .muted {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px;
        }

        .pill {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pill.positive {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
            border: 1.5px solid rgba(16, 185, 129, 0.3);
        }

        .pill.warning {
            background: rgba(245, 158, 11, 0.12);
            color: #d97706;
            border: 1.5px solid rgba(245, 158, 11, 0.3);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .info-tile {
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.06), rgba(59, 130, 244, 0.02));
            border: 1.5px solid rgba(59, 130, 244, 0.12);
            border-radius: 12px;
            padding: 14px;
            transition: all 0.2s ease;
            text-align: left;
        }

        .info-tile:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 244, 0.1);
            border-color: rgba(59, 130, 244, 0.2);
        }

        .info-label {
            display: block;
            font-size: 10px;
            font-weight: 800;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 900;
            color: var(--navy);
            display: block;
            line-height: 1.2;
        }

        .table-card {
            overflow: hidden;
            padding: 0;
            background: #ffffff;
        }

        .table-card .card-header {
            padding: 28px 28px 16px 28px;
            margin: 0;
            border-bottom: 1.5px solid rgba(59, 130, 244, 0.1);
        }

        .table-wrapper {
            overflow-x: auto;
            padding: 0 28px 28px 28px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 500px;
        }

        .table thead {
            background: linear-gradient(90deg, rgba(59, 130, 244, 0.08), rgba(59, 130, 244, 0.04));
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table th {
            padding: 14px 12px;
            text-align: center;
            font-size: 12px;
            font-weight: 900;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5px solid rgba(59, 130, 244, 0.15);
        }

        .table th:first-child {
            text-align: left;
        }

        .table td {
            padding: 14px 12px;
            font-size: 14px;
            font-weight: 600;
            color: var(--navy);
            border-bottom: 1px solid rgba(59, 130, 244, 0.08);
            text-align: center;
        }

        .table td:first-child {
            text-align: left;
            font-weight: 700;
        }

        .table tbody tr {
            transition: all 0.15s ease;
            background: #ffffff;
        }

        .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(59, 130, 244, 0.03), rgba(59, 130, 244, 0.01));
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table td.score-highlight {
            color: var(--accent);
            font-weight: 900;
            font-size: 14px;
        }

        .empty-state {
            text-align: center;
            padding: 56px 20px;
            color: var(--muted);
            font-size: 14px;
        }

        .empty-state i {
            font-size: 52px;
            opacity: 0.2;
            margin-bottom: 16px;
            display: block;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .summary-card {
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.08), rgba(59, 130, 244, 0.03));
            border: 2px solid rgba(59, 130, 244, 0.2);
            border-radius: 14px;
            padding: 26px 18px;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--accent), #2563eb);
        }

        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 32px rgba(59, 130, 244, 0.2);
            border-color: rgba(59, 130, 244, 0.35);
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.12), rgba(59, 130, 244, 0.06));
        }

        .summary-label {
            font-size: 13px;
            font-weight: 900;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 8px;
            display: block;
        }

        .summary-value {
            font-size: 32px;
            font-weight: 950;
            color: var(--accent);
            letter-spacing: -1px;
            line-height: 1;
        }

        .daily-motto {
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.08), rgba(59, 130, 244, 0.03));
            border: 1.5px solid rgba(59, 130, 244, 0.15);
            border-radius: 12px;
            padding: 18px 20px;
            margin-top: 20px;
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            text-align: center;
            line-height: 1.6;
            animation: fadeInUp 0.6s ease-out 0.2s backwards;
        }

        .signature-container {
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.06), rgba(59, 130, 244, 0.02));
            border: 1.5px solid rgba(59, 130, 244, 0.15);
            border-radius: 14px;
            padding: 24px;
            margin-top: 16px;
            text-align: center;
        }

        .signature-container h4 {
            font-size: 14px;
            font-weight: 900;
            color: var(--navy);
            margin: 0 0 8px 0;
        }

        .signature-container p {
            font-size: 12px;
            color: var(--muted);
            margin: 0 0 16px 0;
        }

        .signature-btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .signature-status-box {
            background: #f8fafc;
            border: 1.5px solid rgba(59, 130, 244, 0.08);
            border-radius: 12px;
            padding: 22px;
            text-align: center;
        }

        .signature-status-pending {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            padding: 16px 0;
            text-align: left;
        }

        .signature-status-pending i {
            font-size: 36px;
            color: var(--accent);
            flex-shrink: 0;
        }

        .signature-status-confirmed {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            padding: 16px 0;
            text-align: left;
        }

        .signature-status-confirmed i {
            font-size: 36px;
            color: var(--positive);
            flex-shrink: 0;
        }

        .status-text {
            flex: 1;
        }

        .status-text strong {
            display: block;
            font-size: 14px;
            font-weight: 900;
            color: var(--navy);
            margin-bottom: 2px;
        }

        .status-text p {
            font-size: 12px;
            color: var(--muted);
            margin: 0;
        }

        .signature-layer {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .loading-spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid rgba(59, 130, 244, 0.2);
            border-top-color: rgb(59, 130, 244);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .signature-dialog {
            background: #ffffff;
            border-radius: 20px;
            padding: 32px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 24px 48px rgba(15, 23, 42, 0.2);
            border: 1.5px solid rgba(59, 130, 244, 0.15);
            animation: slideInDown 0.3s ease-out;
        }

        .signature-dialog h3 {
            font-size: 22px;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 8px;
        }

        .signature-canvas {
            width: 100%;
            max-width: 500px;
            height: auto;
            aspect-ratio: 500 / 300;
            border: 2px dashed rgba(59, 130, 244, 0.3);
            border-radius: 12px;
            background: rgba(59, 130, 244, 0.02);
            cursor: crosshair;
            margin: 16px auto;
            display: block;
        }

        .signature-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .signature-preview-container {
            background: #f8fafc;
            border: 1.5px solid rgba(59, 130, 244, 0.1);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            margin: 16px 0;
            min-height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .signature-preview-image {
            max-width: 100%;
            max-height: 320px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(59, 130, 244, 0.1);
        }

        /* 響應式設計 */
        @media (max-width: 1024px) {
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .page {
                padding: 20px 14px;
            }

            .table th {
                font-size: 10px;
                padding: 12px 8px;
            }

            .table td {
                font-size: 12px;
                padding: 12px 8px;
            }

            .topbar {
                flex-wrap: wrap;
            }

            .brand-text>div {
                flex-wrap: wrap;
            }

            .status-badge {
                padding: 5px 10px;
                font-size: 11px;
            }
        }

        @media (max-width: 1024px) {
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .page {
                padding: 16px 12px;
            }

            .topbar {
                padding: 14px 18px;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
                margin-bottom: 18px;
            }

            .brand {
                width: 100%;
            }

            .brand-text h1 {
                font-size: 22px;
            }

            .topbar-meta {
                width: 100%;
                gap: 10px;
            }

            .user-chip {
                flex: 1;
                min-width: auto;
                padding: 8px 12px;
            }

            .user-chip strong {
                font-size: 12px;
                margin-bottom: 1px;
            }

            .user-chip span {
                font-size: 9px;
            }

            .actions {
                width: 100%;
            }

            .actions .btn {
                flex: 1;
                justify-content: center;
                padding: 8px 12px;
                font-size: 11px;
            }

            .btn i {
                display: none;
            }

            .card {
                padding: 18px;
                margin-bottom: 16px;
                border-radius: 14px;
            }

            .card-header {
                margin-bottom: 16px;
                padding-bottom: 12px;
            }

            .card-header h2 {
                font-size: 20px;
            }

            .card-header h3 {
                font-size: 18px;
            }

            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .summary-card {
                padding: 16px;
            }

            .summary-label {
                font-size: 10px;
                margin-bottom: 6px;
            }

            .summary-value {
                font-size: 28px;
            }

            .signature-status-box {
                padding: 16px;
            }

            .signature-status-pending,
            .signature-status-confirmed {
                padding: 12px 0;
                flex-direction: column;
                text-align: center;
            }

            .signature-status-pending i,
            .signature-status-confirmed i {
                font-size: 32px;
            }

            .table-wrapper {
                padding: 0 18px 18px 18px;
            }

            .table-card .card-header {
                padding: 18px 18px 12px 18px;
            }

            /* 手機端卡片式表格 */
            .table {
                display: flex;
                flex-direction: column;
                min-width: 100%;
            }

            .table thead {
                display: none;
            }

            .table tbody {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .table tbody tr {
                display: flex;
                flex-direction: column;
                background: linear-gradient(135deg, rgba(59, 130, 244, 0.02), rgba(59, 130, 244, 0.01));
                border: 1.5px solid rgba(59, 130, 244, 0.1);
                border-radius: 12px;
                padding: 14px;
                gap: 8px;
            }

            .table tbody tr:hover {
                background: linear-gradient(135deg, rgba(59, 130, 244, 0.04), rgba(59, 130, 244, 0.02));
            }

            .table tbody tr td {
                display: grid;
                grid-template-columns: 120px 1fr;
                align-items: center;
                border: none;
                padding: 0;
                font-size: 13px;
                gap: 12px;
            }

            .table tbody tr td:first-child {
                grid-column: 1 / -1;
                font-weight: 700;
                display: block;
                border-bottom: 1.5px solid rgba(59, 130, 244, 0.1);
                padding-bottom: 8px;
                margin-bottom: 4px;
                padding: 0;
            }

            .table tbody tr td:not(:first-child)::before {
                content: attr(data-label);
                font-weight: 700;
                color: var(--navy);
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }

            .table tbody tr td:first-child::before {
                content: '';
                display: none;
            }

            .table tbody tr td.score-highlight {
                color: var(--accent);
                font-weight: 900;
            }



            #signatureCanvas {
                width: 100% !important;
                height: auto !important;
                max-width: 100%;
            }
        }

    
    </style>
</head>

<body class="app-shell">
    <main class="page" style="display: flex; flex-direction: column;">
        <header class="topbar">
            <div class="brand">
                <div class="brand-text">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <h1><?php echo $week_name; ?></h1>
                        <span class="status-badge status-<?php echo $statusClass; ?>"><i
                                class="fas fa-<?php echo ($statusClass === 'positive') ? 'check' : 'clock'; ?>"></i>
                            <?php echo $statusLabel; ?></span>
                    </div>
                    <p style="font-size: 12px; color: var(--muted); margin-top: 2px; margin: 0;">
                        <i class="fas fa-calendar"></i> 查看詳細成績
                    </p>
                </div>
            </div>
            <div class="topbar-meta">
                <div class="user-chip">
                    <strong><?php echo $name; ?></strong>
                    <span><?php echo $classNum; ?> 班 · 座號 <?php echo $seatNum; ?></span>
                </div>
                <div class="actions">
                    <a href="result.php" class="btn btn-ghost">
                        <i class="fas fa-arrow-left"></i><span>返回</span>
                    </a>
                    <a href="index.php" class="btn btn-danger">
                        <i class="fas fa-right-from-bracket"></i><span>登出</span>
                    </a>
                </div>
            </div>
        </header>

        <?php if ($week_show_check == 1): ?>
            <section class="card summary-section">
                <div class="card-header">
                    <div>
                        <p class="eyebrow">成績統計</p>
                        <h3 style="margin: 0;">整體成績表現</h3>
                    </div>
                </div>
                <div style="padding: 0 28px 28px 28px;">
                    <div class="summary-grid">
                        <div class="summary-card">
                            <div class="summary-label"><i class="fas fa-calculator"></i> 總分</div>
                            <div class="summary-value"><?php echo $s_sum; ?></div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-label"><i class="fas fa-chart-line"></i> 平均</div>
                            <div class="summary-value"><?php echo $s_ave; ?></div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-label"><i class="fas fa-chart-bar"></i> 年級總平均</div>
                            <div class="summary-value"><?php echo $week_average ?: '-'; ?></div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-label"><i class="fas fa-trophy"></i> 年級百分比</div>
                            <div class="summary-value"><?php echo $s_gradeRank; ?></div>
                        </div>
                    </div>
                    <div class="daily-motto">
                        🎯 <?php echo $dailyMotto; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <section class="card table-card">
            <div class="card-header">
                <div>
                    <p class="eyebrow">科目成績</p>
                    <h3 style="margin: 0;">各測驗詳細成績</h3>
                </div>
            </div>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-book"></i> 考試項目</th>
                            <th><i class="fas fa-star"></i> 個人成績</th>
                            <th><i class="fas fa-chart-line"></i> 年級平均</th>
                            <th><i class="fas fa-percent"></i> 年級百分比</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($gradeMeta) > 0): ?>
                            <?php foreach ($gradeMeta as $meta): ?>
                                <?php
                                $score = $studentRow[$meta['id']] ?? '';
                                $score = $score === '' ? '無成績' : $score;
                                if ($meta['show'] == "11") {
                                    $scorePer = $studentRow["per_" . $meta['id']] ?? '成績整理中';
                                    $scoreAve = $meta['average'] !== '' ? $meta['average'] : '成績整理中';
                                } else {
                                    $scorePer = '成績整理中';
                                    $scoreAve = '成績整理中';
                                }
                                ?>
                                <tr>
                                    <td><?php echo $meta['label']; ?></td>
                                    <td class="score-highlight" data-label="個人成績"><?php echo $score; ?></td>
                                    <td data-label="年級平均"><?php echo $scoreAve; ?></td>
                                    <td data-label="年級百分比"><?php echo $scorePer; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <div style="margin-top: 12px;">尚無科目資料</div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!--
        <section class="card signature-card">
            <div class="card-header">
                <div>
                    <h3 style="margin: 0; font-size: 16px;">簽名確認</h3>
                    <p class="muted" style="margin-top: 4px;">請簽名確認您已檢視本週成績</p>
                </div>
            </div>
            <div style="padding: 0 28px 28px 28px;">
                <div class="signature-status-box">
                    <?php if (!$signature): ?>
                        <div class="signature-status-pending">
                            <i class="fas fa-pen-fancy"></i>
                            <div class="status-text">
                                <strong>尚未簽名</strong>
                                <p>請按下方按鈕進行簽名</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="showSignature()" style="width: 100%; padding: 12px; justify-content: center; font-size: 13px; font-weight: 700;">
                            <i class="fas fa-pen"></i> 開始簽名
                        </button>
                    <?php else: ?>
                        <div class="signature-status-confirmed">
                            <i class="fas fa-check-circle"></i>
                            <div class="status-text">
                                <strong>已簽名確認</strong>
                                <p>已於 <?php echo $signatureText; ?> 簽署成績確認書</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" onclick="showSignaturePreview()" style="width: 100%; padding: 12px; justify-content: center; font-size: 13px; font-weight: 700; margin-top: 16px;">
                            <i class="fas fa-eye"></i> 檢視簽名
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        -->
    </main>

    <div name="signature" id="signature" class="signature-layer">
        <div class="signature-dialog">
            <h3>請在下方簽名確認</h3>
            <p class="muted">簽名後將送出到系統紀錄，請確認後再上傳</p>
            <canvas id="signatureCanvas" width="500" height="300" class="signature-canvas"></canvas>
            <div class="signature-actions">
                <button type="button" class="btn btn-ghost" onclick="hideSignature()">
                    <i class="fas fa-times"></i> 關閉
                </button>
                <button type="button" class="btn btn-danger" onclick="clearCanvas()">
                    <i class="fas fa-eraser"></i> 清空
                </button>
                <button type="button" class="btn btn-primary" onclick="saveSign()">
                    <i class="fas fa-upload"></i> 上傳簽名
                </button>
            </div>
        </div>
    </div>

    <div name="signature-preview" id="signaturePreview" class="signature-layer">
        <div class="signature-dialog">
            <h3>簽名預覽</h3>
            <p class="muted">您的簽名確認</p>
            <div class="signature-preview-container">
                <img id="signaturePreviewImg" src="" alt="簽名預覽" class="signature-preview-image">
            </div>
            <div class="signature-actions">
                <button type="button" class="btn btn-primary" onclick="closeSignaturePreview()">
                    <i class="fas fa-check"></i> 確認
                </button>
            </div>
        </div>
    </div>

    <script>
        const signatureLayer = document.getElementById('signature');
        const canvas = document.getElementById('signatureCanvas');
        let ctx;
        let isDrawing = false;
        let lastX = 0;
        let lastY = 0;

        function showSignature() {
            if (!signatureLayer) {
                console.error('signatureLayer not found');
                return;
            }
            signatureLayer.style.display = 'flex';
            // 確保 canvas 尺寸正確
            if (canvas && ctx) {
                resizeCanvas();
            }
        }

        function hideSignature() {
            if (!signatureLayer) return;
            clearCanvas();
            signatureLayer.style.display = 'none';
        }

        function resizeCanvas() {
            if (!canvas) return;
            const rect = canvas.getBoundingClientRect();
            canvas.width = 500;
            canvas.height = 300;
            if (ctx) {
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#111827';
            }
        }

        function setupCanvas() {
            if (!canvas) {
                console.error('Canvas element not found');
                return;
            }
            ctx = canvas.getContext('2d');
            if (!ctx) {
                console.error('Failed to get canvas context');
                return;
            }
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#111827';

            canvas.addEventListener('mousedown', startDraw);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', endDraw);
            canvas.addEventListener('mouseleave', endDraw);

            canvas.addEventListener('touchstart', startDrawTouch, { passive: false });
            canvas.addEventListener('touchmove', drawTouch, { passive: false });
            canvas.addEventListener('touchend', endDraw, { passive: false });
        }

        function startDraw(e) {
            if (!canvas) return;
            isDrawing = true;
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            lastX = (e.clientX - rect.left) * scaleX;
            lastY = (e.clientY - rect.top) * scaleY;
        }

        function draw(e) {
            if (!isDrawing || !ctx || !canvas) return;
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            const currentX = (e.clientX - rect.left) * scaleX;
            const currentY = (e.clientY - rect.top) * scaleY;
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(currentX, currentY);
            ctx.stroke();
            lastX = currentX;
            lastY = currentY;
        }

        function startDrawTouch(e) {
            if (!canvas) return;
            e.preventDefault();
            isDrawing = true;
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            lastX = (e.touches[0].clientX - rect.left) * scaleX;
            lastY = (e.touches[0].clientY - rect.top) * scaleY;
        }

        function drawTouch(e) {
            if (!isDrawing || !ctx || !canvas) return;
            e.preventDefault();
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            const currentX = (e.touches[0].clientX - rect.left) * scaleX;
            const currentY = (e.touches[0].clientY - rect.top) * scaleY;
            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(currentX, currentY);
            ctx.stroke();
            lastX = currentX;
            lastY = currentY;
        }

        function endDraw() {
            isDrawing = false;
        }

        function clearCanvas() {
            if (!ctx || !canvas) return;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        function hasSignatureData() {
            if (!ctx || !canvas) return false;
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const pixels = imageData.data;
            return Array.prototype.some.call(pixels, function (_, i) {
                return pixels[i + 3] > 0;
            });
        }

        function saveSign() {
            if (!ctx || !canvas) return;
            if (!hasSignatureData()) {
                alert('請先簽名後再上傳！');
                return;
            }

            const dataURL = canvas.toDataURL('image/png');
            fetch('save_signature.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ image: dataURL }),
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || '伺服器錯誤');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        alert('簽名成功！');
                        location.reload();
                    } else {
                        throw new Error(data.message || '未知錯誤');
                    }
                })
                .catch(error => {
                    alert('簽名失敗：' + error.message);
                });
        }

        function showSignaturePreview() {
            const previewLayer = document.getElementById('signaturePreview');
            const previewContainer = document.querySelector('.signature-preview-container');
            if (!previewLayer || !previewContainer) return;

            // 顯示加載狀態
            previewLayer.style.display = 'flex';
            previewContainer.innerHTML = '<div class="loading-spinner"></div>';

            // 從伺服器獲取已保存的簽名
            fetch('get_signature.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('無法獲取簽名');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success' && data.image) {
                        previewContainer.innerHTML = '<img id="signaturePreviewImg" src="' + data.image + '" alt="簽名預覽" class="signature-preview-image">';
                    } else {
                        throw new Error('簽名資料不存在');
                    }
                })
                .catch(error => {
                    previewContainer.innerHTML = '<p style="color: #ef4444; text-align: center;">載入失敗：' + error.message + '</p>';
                });
        }

        function closeSignaturePreview() {
            const previewLayer = document.getElementById('signaturePreview');
            if (!previewLayer) return;
            previewLayer.style.display = 'none';
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                hideSignature();
                closeSignaturePreview();
            }
        });

        setupCanvas();
    </script>
</body>

</html>