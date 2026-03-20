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

//$sql = "SELECT * FROM `junior3_week_set` WHERE grade_insert_check = '1' AND open_time IS NOT NULL AND open_time <> '' AND open_time <> '0000-00-00 00:00:00' AND NOW() > open_time ORDER BY `week_ID` DESC";
$sql = "SELECT * FROM `junior3_week_set` ORDER BY `week_ID` DESC";
$result = $conn->query($sql);
$availableCount = ($result && $result->num_rows > 0) ? $result->num_rows : 0;
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績查詢系統</title>
    <link rel="icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        :root {
            --bg-primary: #f8fafc;
            --bg-secondary: #f1f5ff;
            --bg-tertiary: #e0e7ff;
            --text: #0f172a;
            --muted: #64748b;
            --navy: #0f172a;
            --accent: #3b82f6;
            --accent-dark: #2563eb;
            --accent-light: #dbeafe;
            --positive: #10b981;
            --pending: #f59e0b;
            --card: #ffffff;
            --border: #e2e8f0;
            --shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
            --shadow-lg: 0 20px 40px rgba(15, 23, 42, 0.12);
            font-size: 20px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, "微軟正黑體", "Microsoft JhengHei", sans-serif;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 50%, var(--bg-tertiary) 100%);
            color: var(--text);
            min-height: 100vh;
            position: relative;
            font-size: 15px;
            line-height: 1.6;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(circle at 18% 40%, rgba(59, 130, 244, 0.06) 0%, transparent 42%),
                radial-gradient(circle at 80% 78%, rgba(59, 130, 244, 0.05) 0%, transparent 48%);
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(0deg, transparent 24%, rgba(59, 130, 244, 0.015) 25%, rgba(59, 130, 244, 0.015) 26%, transparent 27%, transparent 74%, rgba(59, 130, 244, 0.015) 75%, rgba(59, 130, 244, 0.015) 76%, transparent 77%, transparent),
                linear-gradient(90deg, transparent 24%, rgba(59, 130, 244, 0.015) 25%, rgba(59, 130, 244, 0.015) 26%, transparent 27%, transparent 74%, rgba(59, 130, 244, 0.015) 75%, rgba(59, 130, 244, 0.015) 76%, transparent 77%, transparent);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: 0;
        }

        .page {
            width: 100%;
            max-width: 1040px;
            margin: 0 auto;
            padding: 32px 20px 56px;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.6s ease-out;
        }

        .topbar {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
            padding: 24px 20px;
            border-radius: 18px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            border: 1px solid rgba(148, 163, 184, 0.3);
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
            text-align: center;
            backdrop-filter: blur(14px);
            margin-bottom: 24px;
            animation: slideInDown 0.6s ease-out;
        }

        .topbar::after {
            content: '';
            position: absolute;
            top: -80px;
            right: -40px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.12) 0%, rgba(255, 255, 255, 0) 65%);
            filter: blur(8px);
            pointer-events: none;
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .brand-text h1 {
            margin: 6px 0 8px 0;
            font-size: 24px;
            color: var(--navy);
            letter-spacing: -0.4px;
            line-height: 1.3;
            font-weight: 800;
        }

        .brand-text .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.12), rgba(59, 130, 246, 0.08));
            color: var(--accent);
            font-size: 12px;
            letter-spacing: 0.6px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0;
            border: 1px solid rgba(37, 99, 235, 0.2);
        }

        .brand-text .muted {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .topbar-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            width: 100%;
            z-index: 1;
        }

        .user-chip {
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.08), rgba(59, 130, 244, 0.04));
            border: 1.5px solid rgba(59, 130, 244, 0.18);
            border-radius: 12px;
            padding: 13px 18px;
            min-width: 220px;
            color: var(--navy);
            font-weight: 700;
            box-shadow: 0 6px 16px rgba(59, 130, 244, 0.08);
            transition: all 0.28s ease;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .user-chip:hover {
            border-color: rgba(59, 130, 244, 0.28);
            box-shadow: 0 10px 24px rgba(59, 130, 244, 0.14);
            transform: translateY(-2px);
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.12), rgba(59, 130, 244, 0.08));
        }

        .user-chip strong {
            display: block;
            color: var(--navy);
            font-size: 15px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .user-chip span {
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
            line-height: 1.3;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            width: auto;
            flex-wrap: wrap;
        }

        .actions form {
            margin: 0;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 11px 16px;
            border-radius: 11px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.25s ease;
            border: 1px solid transparent;
            cursor: pointer;
            color: var(--navy);
            background: transparent;
        }

        .btn:active {
            transform: scale(0.96);
        }

        .btn:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 244, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
            color: #fff;
            border-color: var(--accent);
            box-shadow: 0 4px 12px rgba(59, 130, 244, 0.25);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--accent-dark) 0%, #1e40af 100%);
            border-color: #1e40af;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
            transform: translateY(-1px);
        }

        .btn-ghost {
            background: rgba(59, 130, 244, 0.1);
            color: var(--accent);
            border-color: rgba(59, 130, 244, 0.2);
            transition: all 0.25s ease;
        }

        .btn-ghost:hover {
            background: rgba(59, 130, 244, 0.18);
            border-color: rgba(59, 130, 244, 0.32);
            color: var(--accent-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 244, 0.15);
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow);
            padding: 3%;
            overflow: hidden;
            position: relative;
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out 0.2s backwards;
        }

        .card:hover {
            border-color: rgba(59, 130, 244, 0.15);
            box-shadow: var(--shadow-lg);
        }

        .stats-card {
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.08), rgba(59, 130, 244, 0.04));
            border: 1px solid rgba(59, 130, 244, 0.12);
            animation: fadeInUp 0.6s ease-out 0.1s backwards;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
            padding: 20px;
        }

        .stats-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 12px;
            border: 1px solid rgba(59, 130, 244, 0.08);
            transition: all 0.3s ease;
        }

        .stats-item:hover {
            background: rgba(255, 255, 255, 0.95);
            border-color: rgba(59, 130, 244, 0.15);
            box-shadow: 0 4px 12px rgba(59, 130, 244, 0.1);
            transform: translateY(-1px);
        }

        .stats-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stats-content {
            flex: 1;
        }

        .stats-label {
            margin: 0;
            font-size: 11px;
            color: var(--muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .stats-number {
            margin: 6px 0 0 0;
            font-size: 26px;
            font-weight: 900;
            color: var(--navy);
            line-height: 1.1;
            display: flex;
            align-items: baseline;
            gap: 4px;
        }

        .stats-unit {
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
            gap: 20px;
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.02) 0%, rgba(255, 255, 255, 0) 100%);
        }

        .card-header > div:first-child {
            flex: 1;
        }

        .card-header h2 {
            margin: 0;
            color: var(--navy);
            font-size: 20px;
            line-height: 1.4;
            font-weight: 900;
            letter-spacing: -0.3px;
        }

        .card-header .eyebrow {
            margin: 0;
            font-size: 11px;
            letter-spacing: 1.2px;
            color: var(--muted);
            text-transform: uppercase;
            font-weight: 800;
        }

        .card-header .muted {
            margin: 6px 0 0 0;
            color: var(--muted);
            font-size: 13px;
            font-weight: 500;
        }

        .header-stats {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 12px 16px;
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.1), rgba(59, 130, 244, 0.06));
            border-radius: 11px;
            border: 1px solid rgba(59, 130, 244, 0.15);
            text-align: center;
            min-width: 100px;
            transition: all 0.25s ease;
        }

        .stat-item:hover {
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.15), rgba(59, 130, 244, 0.1));
            border-color: rgba(59, 130, 244, 0.25);
            box-shadow: 0 2px 8px rgba(59, 130, 244, 0.12);
            transform: translateY(-1px);
        }

        .stat-label {
            font-size: 11px;
            color: var(--muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 900;
            color: var(--accent);
            line-height: 1.1;
        }

        .meta-pills {
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-end;
        }

        .week-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            padding: 12px 0 16px 0;
            max-height: 70vh;
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        .week-grid::-webkit-scrollbar {
            width: 8px;
        }

        .week-grid::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .week-grid::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .week-card {
            background: var(--card);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
            display: grid;
            gap: 12px;
            transition: all 0.28s ease;
            animation: fadeInUp 0.5s ease-out;
        }

        .week-card:nth-child(1) { animation-delay: 0.1s; }
        .week-card:nth-child(2) { animation-delay: 0.2s; }
        .week-card:nth-child(3) { animation-delay: 0.3s; }
        .week-card:nth-child(n+4) { animation-delay: 0.4s; }

        .week-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .week-title {
            font-size: 20px;
            font-weight: 900;
            color: var(--navy);
            line-height: 1.3;
            letter-spacing: -0.2px;
        }

        .week-body {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
            display: grid;
            gap: 6px;
        }

        .meta-row {
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .meta-row i {
            color: var(--accent);
            margin-top: 2px;
            flex-shrink: 0;
            font-size: 13px;
        }

        .week-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 4px;
        }

        .week-card:hover {
            border-color: rgba(59, 130, 244, 0.2);
            box-shadow: 0 10px 24px rgba(59, 130, 244, 0.12);
            transform: translateY(-2px);
        }

        .week-card:focus-within {
            border-color: rgba(59, 130, 244, 0.35);
            box-shadow: 0 10px 24px rgba(59, 130, 244, 0.15);
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 10px;
            padding: 7px 12px;
            font-weight: 700;
            font-size: 12px;
            background: rgba(59, 130, 244, 0.1);
            color: var(--accent);
            border: 1px solid rgba(59, 130, 244, 0.22);
            transition: all 0.25s ease;
            white-space: nowrap;
        }

        .pill:hover {
            background: rgba(59, 130, 244, 0.16);
            border-color: rgba(59, 130, 244, 0.32);
        }

        .pill.positive {
            background: rgba(16, 185, 129, 0.1);
            color: var(--positive);
            border-color: rgba(16, 185, 129, 0.22);
        }

        .pill.positive:hover {
            background: rgba(16, 185, 129, 0.16);
            border-color: rgba(16, 185, 129, 0.32);
        }

        .pill.warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--pending);
            border-color: rgba(245, 158, 11, 0.22);
        }

        .pill.warning:hover {
            background: rgba(245, 158, 11, 0.16);
            border-color: rgba(245, 158, 11, 0.32);
        }

        .pill.neutral {
            background: rgba(100, 116, 139, 0.08);
            color: var(--text);
            border-color: rgba(100, 116, 139, 0.15);
        }

        .pill.neutral:hover {
            background: rgba(100, 116, 139, 0.14);
            border-color: rgba(100, 116, 139, 0.25);
        }

        .empty-state {
            padding: 40px 20px;
            text-align: center;
            color: var(--muted);
            font-weight: 600;
            display: grid;
            gap: 12px;
            place-items: center;
        }

        .empty-state .empty-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(59, 130, 244, 0.12), rgba(59, 130, 244, 0.06));
            color: var(--accent);
            display: grid;
            place-items: center;
            font-size: 24px;
            box-shadow: 0 4px 12px rgba(59, 130, 244, 0.1);
        }

        .filter-group {
            padding: 16px 0 14px 0;
            margin-bottom: 0;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            border-bottom: 1px solid var(--border);
        }

        .filter-group {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 9px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.98);
            color: var(--text);
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-btn:hover {
            border-color: rgba(59, 130, 244, 0.3);
            background: rgba(59, 130, 244, 0.06);
            color: var(--accent);
        }

        .filter-btn.active {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
            box-shadow: 0 4px 12px rgba(59, 130, 244, 0.3);
        }

        .filter-btn.active:hover {
            background: var(--accent-dark);
            border-color: var(--accent-dark);
            box-shadow: 0 6px 16px rgba(59, 130, 244, 0.35);
        }

        @media (min-width: 768px) and (max-width: 1024px) {
            .week-grid {
                grid-template-columns: repeat(2, 1fr);
                padding: 0 0 14px 0;
            }

            .week-card {
                padding: 14px;
                gap: 11px;
            }

            .week-title {
                font-size: 18px;
            }

            .week-body {
                font-size: 12px;
            }

            .pill {
                font-size: 12px;
                padding: 6px 10px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
                padding: 16px;
            }

            .header-stats {
                justify-content: flex-start;
            }
        }

        @media (max-width: 720px) {
            .page {
                padding: 18px 12px 36px;
            }

            .topbar {
                gap: 14px;
                padding: 16px;
                margin-bottom: 18px;
            }

            .brand-text h1 {
                font-size: 22px;
                margin: 4px 0 6px 0;
            }

            .brand-text .muted {
                font-size: 13px;
            }

            .user-chip {
                min-width: auto;
                width: 100%;
                text-align: left;
                padding: 13px 14px;
                flex-direction: column;
                gap: 8px;
            }

            .user-chip strong {
                font-size: 14px;
            }

            .user-chip span {
                font-size: 12px;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
                padding: 14px 16px;
            }

            .card-header > div:first-child {
                width: 100%;
            }

            .header-stats {
                width: 100%;
                gap: 10px;
                justify-content: flex-start;
            }

            .stat-item {
                flex: 1;
                flex-direction: column;
                align-items: center;
                gap: 6px;
                padding: 10px 12px;
                border-radius: 10px;
                min-width: auto;
            }

            .stat-label {
                font-size: 11px;
            }

            .stat-value {
                font-size: 18px;
            }

            .week-grid {
                grid-template-columns: 1fr;
                padding: 0 0 12px 0;
                gap: 12px;
                max-height: 68vh;
            }

            .week-card {
                padding: 14px;
                gap: 10px;
                border-radius: 11px;
            }

            .week-title {
                font-size: 16px;
            }

            .week-header {
                flex-wrap: wrap;
                gap: 8px;
            }

            .week-body {
                gap: 5px;
                font-size: 12px;
            }

            .meta-row {
                gap: 6px;
            }

            .meta-row i {
                font-size: 12px;
            }

            .pill {
                font-size: 11px;
                padding: 6px 10px;
                gap: 4px;
            }

            .btn {
                padding: 10px 14px;
                font-size: 13px;
            }

            .actions .btn {
                width: 100%;
                justify-content: center;
            }

            .empty-state {
                padding: 30px 16px;
            }

            .empty-state .empty-icon {
                width: 52px;
                height: 52px;
                font-size: 20px;
            }

            .filter-group {
                padding: 12px 0 12px 0;
                border-bottom: none;
                margin-bottom: 0;
            }

            .filter-btn {
                font-size: 12px;
            }

            .filter-group {
                width: 100%;
                justify-content: space-between;
                gap: 6px;
            }

            .filter-btn {
                flex: 1;
                justify-content: center;
                font-size: 11px;
                padding: 8px 8px;
                gap: 4px;
            }

            .filter-btn i {
                display: none;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
                padding: 14px;
            }

            .stats-item {
                padding: 12px;
                gap: 12px;
            }

            .stats-icon {
                width: 44px;
                height: 44px;
                font-size: 18px;
            }

            .stats-number {
                font-size: 20px;
            }

            .week-actions {
                padding-top: 2px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
                padding: 14px;
            }

            .stats-item {
                padding: 12px;
                gap: 12px;
            }

            .stats-icon {
                width: 44px;
                height: 44px;
                font-size: 18px;
            }

            .stats-number {
                font-size: 18px;
            }
        }
    </style>
</head>

<body class="app-shell">
    <main class="page">
        <header class="topbar">
            <div class="brand">
                <div class="brand-text">
                    <p class="eyebrow">114 學年度</p>
                    <h1>六和高中 國三成績系統</h1>
                    <p class="muted">彙整已開放查詢的週次，輕鬆查看各次考試成績。</p>
                </div>
            </div>
            <div class="topbar-meta">
                <div class="user-chip">
                    <strong><?php echo $name; ?></strong>
                    <span><?php echo $classNum; ?> 班</span>
                    <span>座號 <?php echo $seatNum; ?></span>
                </div>
                <div class="actions">
                    <form id="logout-form" action="logout.php" method="post">
                        <button type="submit" class="btn btn-ghost" id="logout-btn" aria-label="登出系統">
                            <i class="fas fa-right-from-bracket"></i><span>登出</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <section class="card table-card">
            <div class="card-header">
                <div style="flex: 1;">
                    <p class="eyebrow">週次列表</p>
                    <h2>可檢視成績</h2>
                    <p class="muted">點擊週次卡片查看該次考試成績</p>
                </div>
                <div class="header-stats">
                    <div class="stat-item">
                        <span class="stat-label">總共開放</span>
                        <span class="stat-value"><?php echo $availableCount; ?> 週</span>
                    </div>
                </div>
            </div>

            <div class="filter-group" role="group" aria-label="篩選成績狀態">
                <button type="button" class="filter-btn active" data-filter="all" aria-pressed="true">
                    <i class="fas fa-layer-group"></i> 全部
                </button>
                <button type="button" class="filter-btn" data-filter="completed" aria-pressed="false">
                    <i class="fas fa-check-circle"></i> 已結算
                </button>
                <button type="button" class="filter-btn" data-filter="pending" aria-pressed="false">
                    <i class="fas fa-hourglass-half"></i> 計算中
                </button>
            </div>

            <div class="week-grid" id="weekGrid">
                <?php if ($result && $result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <?php
                        $week_ID = $row["week_ID"];
                        $week_name = $row["week_name"];
                        $isClosed = $row["grade_insert_check"] === "1";
                        $open_time = $row["open_time"] ?? '';
                        $openText = $open_time && $open_time !== '0000-00-00 00:00:00'
                            ? date('Y/m/d H:i', strtotime($open_time))
                            : '未提供';
                        $statusClass = $isClosed ? 'completed' : 'pending';
                        ?>
                        <article class="week-card" data-week-id="<?php echo $week_ID; ?>" data-status="<?php echo $statusClass; ?>" data-name="<?php echo htmlspecialchars($week_name); ?>" tabindex="0" role="button">
                            <div class="week-header">
                                <div class="week-title"><?php echo $week_name; ?></div>
                                <span class="pill <?php echo $isClosed ? 'positive' : 'warning'; ?>">
                                    <?php echo $isClosed ? '<i class="fas fa-check-circle"></i> 已結算' : '<i class="fas fa-hourglass-half"></i> 成績計算中'; ?>
                                </span>
                            </div>
                            <div class="week-body">
                                <div class="meta-row"><i class="fas fa-clock"></i> 開放時間：<?php echo $openText; ?></div>
                                <div class="meta-row"><i class="fas fa-info-circle"></i>
                                    <?php echo $isClosed ? '成績已完成計算，可直接查看。' : '成績計算中，開放後即可查看，必要時可洽導師。'; ?>
                                </div>
                            </div>
                            <div class="week-actions">
                                <?php if ($isClosed) : ?>
                                    <a href="grade.php?week=<?php echo $week_ID; ?>" class="btn btn-primary" onclick="event.stopPropagation();">檢視成績</a>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php else : ?>
                    <div style="grid-column: 1/-1;">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                            <div>目前尚未開放任何週次</div>
                            <div class="muted">稍後再回來查看，或洽導師確認開放時間</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
        (function () {
            // ============ 登出功能 ============
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function (event) {
                    if (!confirm('確定要登出嗎？')) {
                        event.preventDefault();
                    }
                });
            }

            // ============ 篩選功能 ============
            const filterBtns = document.querySelectorAll('.filter-btn');
            const weekCards = document.querySelectorAll('.week-card[data-status]');
            const weekSearch = document.getElementById('weekSearch');

            let currentFilter = 'all';

            // 篩選按鈕
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    filterBtns.forEach(b => {
                        b.classList.remove('active');
                        b.setAttribute('aria-pressed', 'false');
                    });
                    this.classList.add('active');
                    this.setAttribute('aria-pressed', 'true');
                    currentFilter = this.dataset.filter;
                    filterWeeks();
                });
            });

            // 篩選邏輯
            function filterWeeks() {
                let visibleCount = 0;

                weekCards.forEach(card => {
                    const status = card.dataset.status;
                    
                    // 檢查篩選條件
                    const statusMatch = currentFilter === 'all' || currentFilter === status;
                    
                    if (statusMatch) {
                        card.style.display = '';
                        visibleCount++;
                        // 動畫效果
                        card.style.animation = 'none';
                        setTimeout(() => {
                            card.style.animation = '';
                        }, 10);
                    } else {
                        card.style.display = 'none';
                    }
                });

                // 顯示無結果信息
                showEmptyState(visibleCount === 0);
            }

            function showEmptyState(show) {
                const grid = document.getElementById('weekGrid');
                if (!grid) return;

                const placeholders = Array.from(grid.children).filter(child => {
                    const isDynamic = child.dataset && child.dataset.emptyState === 'dynamic';
                    const hasEmptyState = child.querySelector && child.querySelector('.empty-state');
                    const isGridSpan = child.style && child.style.gridColumn === '1/-1';
                    return isDynamic || (hasEmptyState && isGridSpan);
                });

                if (show) {
                    if (placeholders.length === 0) {
                        const emptyState = document.createElement('div');
                        emptyState.dataset.emptyState = 'dynamic';
                        emptyState.style.gridColumn = '1/-1';
                        emptyState.innerHTML = `
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-search"></i></div>
                                <div>找不到符合條件的週次</div>
                                <div class="muted">試試調整搜尋條件或篩選選項</div>
                            </div>
                        `;
                        grid.appendChild(emptyState);
                    }
                } else {
                    placeholders.forEach(node => node.remove());
                }
            }

            // ============ 鍵盤導航 ============
            weekCards.forEach((card, index) => {
                card.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        const link = this.querySelector('.btn-primary');
                        if (link) link.click();
                    }
                });
            });

            // ============ 卡片點擊導航 ============
            weekCards.forEach(card => {
                card.addEventListener('click', function (e) {
                    if (!e.target.closest('.week-actions')) {
                        const link = this.querySelector('.btn-primary');
                        if (link) {
                            // 添加點擊動畫
                            this.style.animation = 'pulse 0.3s ease-in-out';
                            setTimeout(() => {
                                link.click();
                            }, 150);
                        }
                    }
                });
            });

            // ============ 記住搜尋狀態（可選） ============
            // 從 localStorage 恢復搜尋狀態
            if (weekSearch && localStorage.getItem('weekSearchTerm')) {
                weekSearch.value = localStorage.getItem('weekSearchTerm');
                filterWeeks();
            }

            // 保存搜尋狀態
            if (weekSearch) {
                weekSearch.addEventListener('input', function () {
                    localStorage.setItem('weekSearchTerm', this.value);
                });
            }

        })();
    </script>
</body>

</html>
