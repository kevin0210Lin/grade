<?php
session_start();
ini_set('display_errors', 1);
$_SESSION['login_check'] = "F";
$loginError = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';
unset($_SESSION['login_error']);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>六和高中 成績查詢系統</title>
    <link rel="icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="app-shell" id="app-body">
    <!-- 錯誤提示 -->
    <div id="error-message" class="error-message"></div>

    <main class="page">
        <div class="container">
            <!-- Logo Section -->
            <div class="header-section">
                <h1 class="school-name">六和高中</h1>
                <p class="subtitle">國三學生成績查詢</p>
            </div>

            <!-- Login Form Section -->
            <div class="card">
                <form action="login_check.php" method="post" id="login-form">
                    <div class="form-group">
                        <div class="label-with-hint">
                            <label for="password">密碼</label>
                            <span class="hint-badge">班級+座號+身份字號末4碼</span>
                        </div>
                        <div class="password-input-wrapper">
                            <input 
                                required 
                                type="password" 
                                class="input" 
                                name="password" 
                                id="password" 
                                placeholder="例: 901481234"
                                autocomplete="off"
                            >
                            <button type="button" class="password-toggle" id="password-toggle" title="顯示/隱藏密碼">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="validation-message" id="validation-message"></div>
                    </div>

                    <button type="submit" class="btn-primary" id="submit-btn">
                        <span class="btn-text">登入</span>
                        <span class="btn-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </form>

                <p class="help-text">如有問題，請洽詢導師</p>
            </div>

            <!-- Countdown Section -->
            <div class="countdown-card">
                <p class="countdown-title">115年會考倒數</p>
                <div class="countdown-display">
                    <div class="countdown-unit">
                        <div class="countdown-value" id="days">0</div>
                        <div class="countdown-label">天</div>
                    </div>
                    <div class="countdown-separator">：</div>
                    <div class="countdown-unit">
                        <div class="countdown-value" id="hours">0</div>
                        <div class="countdown-label">時</div>
                    </div>
                    <div class="countdown-separator">：</div>
                    <div class="countdown-unit">
                        <div class="countdown-value" id="minutes">0</div>
                        <div class="countdown-label">分</div>
                    </div>
                    <div class="countdown-separator">：</div>
                    <div class="countdown-unit">
                        <div class="countdown-value" id="seconds">0</div>
                        <div class="countdown-label">秒</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style id="theme-style">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #f5f7fa;
            --bg-secondary: #f0f4fb;
            --bg-tertiary: #eef2f8;
            --card-bg: #ffffff;
            --text-primary: #0f172a;
            --text-secondary: #1f2937;
            --text-tertiary: #6b7280;
            --text-muted: #8b95a5;
            --border-light: #e5e7eb;
            --border-subtle: rgba(59, 130, 244, 0.1);
            --blue-primary: #3b82f6;
            --blue-dark: #2563eb;
            --blue-light: rgba(59, 130, 244, 0.08);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, "微軟正黑體", "Microsoft JhengHei", sans-serif;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 50%, var(--bg-tertiary) 100%);
            min-height: 100vh;
            color: var(--text-secondary);
            line-height: 1.6;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(59, 130, 244, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(100, 150, 255, 0.04) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
            transition: background-image 0.5s ease;
        }

        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                linear-gradient(0deg, transparent 24%, rgba(59, 130, 244, 0.015) 25%, rgba(59, 130, 244, 0.015) 26%, transparent 27%, transparent 74%, rgba(59, 130, 244, 0.015) 75%, rgba(59, 130, 244, 0.015) 76%, transparent 77%, transparent),
                linear-gradient(90deg, transparent 24%, rgba(59, 130, 244, 0.015) 25%, rgba(59, 130, 244, 0.015) 26%, transparent 27%, transparent 74%, rgba(59, 130, 244, 0.015) 75%, rgba(59, 130, 244, 0.015) 76%, transparent 77%, transparent);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: 0;
        }

        body.dark-mode::before {
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(59, 130, 244, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(100, 150, 255, 0.06) 0%, transparent 50%);
        }

        .app-shell {
            min-height: 100vh;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .page {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            z-index: 1;
            overflow-y: auto;
        }

        .container {
            width: 100%;
            max-width: 440px;
            text-align: center;
            padding-bottom: 60px;
        }

        /* 進場動畫 */
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

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header Section */
        .header-section {
            margin-bottom: 64px;
            animation: slideInDown 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .school-name {
            font-size: 48px;
            font-weight: 900;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--text-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 14px;
            letter-spacing: -2px;
            line-height: 1;
            word-spacing: 0.05em;
        }

        .subtitle {
            font-size: 22px;
            color: var(--text-tertiary);
            margin: 0;
            font-weight: 700;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            word-spacing: 0.12em;
        }

        /* 深色模式切換按鈕 */
        .theme-toggle {
            position: fixed;
            top: 24px;
            right: 24px;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--card-bg);
            border: 1.5px solid var(--border-subtle);
            color: var(--blue-primary);
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .theme-toggle:hover {
            background: var(--blue-primary);
            color: white;
            transform: scale(1.05) rotate(20deg);
        }

        .theme-toggle:active {
            transform: scale(0.95) rotate(20deg);
        }

        /* 錯誤提示 */
        .error-message {
            position: fixed;
            top: 20px;
            left: 20px;
            right: 20px;
            background: #ef4444;
            color: white;
            padding: 16px 20px;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.3);
            opacity: 0;
            transform: translateY(-20px);
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
        }

        .error-message.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* Card Section */
        .card {
            background: var(--card-bg);
            border-radius: 18px;
            padding: 52px 44px;
            margin-bottom: 28px;
            box-shadow: 
                0 20px 40px rgba(15, 23, 42, 0.08),
                0 8px 20px var(--blue-light),
                0 2px 4px rgba(0, 0, 0, 0.02);
            border: 1px solid var(--border-subtle);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(8px);
            animation: slideInUp 0.7s cubic-bezier(0.4, 0, 0.2, 1) 0.1s both;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--blue-primary) 0%, #6366f1 50%, var(--blue-primary) 100%);
            border-radius: 18px 18px 0 0;
        }

        .card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, rgba(255,255,255,0) 50%);
            border-radius: 18px;
            pointer-events: none;
        }

        .card:hover {
            box-shadow: 
                0 28px 48px rgba(15, 23, 42, 0.12),
                0 12px 24px var(--blue-light),
                0 4px 8px rgba(0, 0, 0, 0.04);
            transform: translateY(-6px);
            border-color: rgba(59, 130, 244, 0.15);
        }

        form {
            width: 100%;
            position: relative;
            z-index: 2;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 36px;
            text-align: left;
        }

        label {
            display: block;
            font-size: 16px;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 12px;
            letter-spacing: 0.2px;
            line-height: 1.4;
            transition: all 0.3s ease;
        }

        .label-with-hint {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            gap: 12px;
        }

        .label-with-hint label {
            margin-bottom: 0;
            flex: 1;
        }

        .hint-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            color: var(--blue-primary);
            border: 1.5px solid rgba(59, 130, 244, 0.5);
            padding: 6px 14px;
            border-radius: 8px;
            letter-spacing: 0.3px;
            background: var(--blue-light);
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .hint-badge:hover {
            background: rgba(59, 130, 244, 0.15);
            border-color: var(--blue-primary);
        }

        .password-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input {
            width: 100%;
            padding: 16px 18px;
            padding-right: 48px;
            border: 1.5px solid var(--border-light);
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--card-bg);
            color: var(--text-secondary);
            letter-spacing: -0.1px;
            font-weight: 500;
        }

        .input:focus {
            outline: none;
            background: linear-gradient(135deg, var(--card-bg) 0%, var(--blue-light) 100%);
            border-color: var(--blue-primary);
            box-shadow: 
                0 0 0 5px rgba(59, 130, 244, 0.08),
                0 8px 20px rgba(59, 130, 244, 0.12);
        }

        .input::placeholder {
            color: var(--text-muted);
            font-weight: 500;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            background: none;
            border: none;
            color: var(--text-tertiary);
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            color: var(--blue-primary);
            transform: scale(1.1);
        }

        /* 驗證提示 */
        .validation-message {
            font-size: 12px;
            color: #ef4444;
            margin-top: 6px;
            min-height: 18px;
            transition: all 0.3s ease;
            opacity: 0;
        }

        .validation-message.show {
            opacity: 1;
        }

        .validation-message.success {
            color: #10b981;
        }

        /* Button */
        .btn-primary {
            width: 100%;
            padding: 16px 32px;
            background: linear-gradient(135deg, var(--blue-primary) 0%, var(--blue-dark) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 32px;
            letter-spacing: 0.4px;
            box-shadow: 0 8px 20px rgba(59, 130, 244, 0.3);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, var(--blue-dark) 0%, #1d4ed8 100%);
            box-shadow: 0 12px 28px rgba(59, 130, 244, 0.4);
            transform: translateY(-3px);
        }

        .btn-primary:hover:not(:disabled)::before {
            left: 100%;
        }

        .btn-primary:active:not(:disabled) {
            transform: translateY(-1px);
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-loading {
            display: none;
            font-size: 18px;
        }

        .btn-primary.loading .btn-text {
            display: none;
        }

        .btn-primary.loading .btn-loading {
            display: inline-block;
        }

        /* Help Text */
        .help-text {
            font-size: 14px;
            color: var(--text-tertiary);
            margin-top: 20px;
            margin-bottom: 0;
            line-height: 1.8;
            letter-spacing: 0.1px;
            font-weight: 500;
        }

        /* Countdown Card */
        .countdown-card {
            background: transparent;
            border-radius: 0;
            padding: 18px 0 6px;
            margin-top: 20px;
            border: none;
            box-shadow: none;
            backdrop-filter: none;
            transition: none;
            animation: none;
        }

        .countdown-card:hover {
            border-color: transparent;
            transform: none;
            box-shadow: none;
        }

        .countdown-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--blue-primary);
            margin: 0 0 18px 0;
            letter-spacing: 1.2px;
            text-align: center;
        }

        .countdown-display {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .countdown-unit {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            min-width: 64px;
        }

        .countdown-value {
            font-size: 34px;
            font-weight: 900;
            color: var(--blue-dark);
            background: transparent;
            border: none;
            min-width: 48px;
            text-align: center;
            letter-spacing: -0.8px;
            padding: 6px 8px;
            transition: none;
        }

        .countdown-label {
            font-size: 11px;
            font-weight: 800;
            color: var(--text-tertiary);
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        .countdown-separator {
            font-size: 28px;
            font-weight: 900;
            color: rgba(59, 130, 244, 0.55);
            margin: 0 -2px;
            margin-bottom: 10px;
            animation: none;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .theme-toggle {
                width: 44px;
                height: 44px;
                font-size: 16px;
            }

            .header-section {
                margin-bottom: 54px;
            }

            .countdown-card {
                padding: 28px 32px;
                margin-top: 32px;
                border-radius: 14px;
            }

            .countdown-title {
                font-size: 13px;
                margin-bottom: 18px;
            }

            .countdown-value {
                font-size: 28px;
                min-width: 45px;
                height: 40px;
                line-height: 40px;
            }

            .countdown-label {
                font-size: 10px;
            }

            .countdown-separator {
                font-size: 24px;
                margin-bottom: 14px;
            }

            .page {
                padding: 16px;
            }

            .container {
                max-width: 100%;
                padding-bottom: 50px;
            }

            .school-name {
                font-size: 56px;
                margin-bottom: 12px;
                letter-spacing: -1.5px;
            }

            .subtitle {
                font-size: 13px;
                letter-spacing: 1.5px;
            }

            .card {
                padding: 44px 36px;
                margin-bottom: 24px;
                border-radius: 16px;
            }

            .card::before {
                border-radius: 16px 16px 0 0;
                height: 2.5px;
            }

            .form-group {
                margin-bottom: 32px;
            }

            .input {
                padding: 14px 16px;
                font-size: 16px;
                border-radius: 10px;
            }

            label {
                font-size: 15px;
                margin-bottom: 11px;
            }

            .hint-badge {
                font-size: 10px;
                padding: 5px 12px;
            }

            .btn-primary {
                padding: 14px 28px;
                margin-top: 28px;
                border-radius: 10px;
                font-size: 15px;
            }
        }

        @media (max-width: 400px) {
            .theme-toggle {
                width: 40px;
                height: 40px;
                font-size: 14px;
                top: 12px;
                right: 12px;
            }

            .countdown-card {
                padding: 24px 28px;
                margin-top: 28px;
                border-radius: 12px;
            }

            .countdown-title {
                font-size: 12px;
                margin-bottom: 16px;
            }

            .countdown-value {
                font-size: 24px;
                min-width: 40px;
                height: 35px;
                line-height: 35px;
            }

            .countdown-label {
                font-size: 9px;
            }

            .countdown-separator {
                font-size: 20px;
                margin-bottom: 12px;
            }

            .school-name {
                font-size: 48px;
                letter-spacing: -1.2px;
            }

            .subtitle {
                font-size: 12px;
                letter-spacing: 1.2px;
            }

            .card {
                padding: 36px 28px;
                border-radius: 14px;
            }

            .card::before {
                border-radius: 14px 14px 0 0;
                height: 2px;
            }

            .form-group {
                margin-bottom: 28px;
            }

            label {
                font-size: 14px;
                margin-bottom: 10px;
            }

            .hint-badge {
                font-size: 9px;
                padding: 5px 10px;
            }

            .btn-primary {
                padding: 13px 24px;
                font-size: 14px;
                margin-top: 24px;
                border-radius: 9px;
            }
        }
    </style>

    <script>
        // 密碼顯示/隱藏切換
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('password-toggle');

        passwordToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            passwordToggle.innerHTML = type === 'password' 
                ? '<i class="fas fa-eye"></i>' 
                : '<i class="fas fa-eye-slash"></i>';
        });

        // 輸入驗證
        const validationMessage = document.getElementById('validation-message');
        const passwordPattern = /^[0-9]{9}$/;

        passwordInput.addEventListener('input', () => {
            const value = passwordInput.value;
            
            if (value.length === 0) {
                validationMessage.classList.remove('show', 'success');
            } else if (passwordPattern.test(value)) {
                validationMessage.textContent = '✓ 格式正確';
                validationMessage.classList.add('show', 'success');
            } else {
                validationMessage.textContent = '✗ 請輸入班級(1)+座號(2)+身分證末4碼(4)，共9位數字';
                validationMessage.classList.add('show');
                validationMessage.classList.remove('success');
            }
        });

        // Enter鍵提交
        passwordInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                document.getElementById('login-form').submit();
            }
        });

        // 自動聚焦密碼輸入框
        window.addEventListener('load', () => {
            passwordInput.focus();
        });

        // 登入按鈕loading狀態
        const loginForm = document.getElementById('login-form');
        const submitBtn = document.getElementById('submit-btn');

        loginForm.addEventListener('submit', (e) => {
            if (!passwordPattern.test(passwordInput.value)) {
                e.preventDefault();
                showError('密碼格式不正確，請輸入班級+座號+末4碼');
                return;
            }
            
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });

        // 顯示PHP傳回的錯誤信息
        const phpError = '<?php echo htmlspecialchars($loginError, ENT_QUOTES); ?>';
        if (phpError) {
            showError(phpError);
        }

        // 錯誤提示函數
        function showError(message) {
            const errorElement = document.getElementById('error-message');
            errorElement.textContent = message;
            errorElement.classList.add('show');
            setTimeout(() => {
                errorElement.classList.remove('show');
            }, 4000);
        }

        // 115年會考倒數（2026年5月16日）
        function updateCountdown() {
            const examDate = new Date('2026-05-16T00:00:00').getTime();
            const now = new Date().getTime();
            const distance = examDate - now;

            const updateValue = (id, newValue) => {
                const element = document.getElementById(id);
                const paddedValue = String(newValue).padStart(2, '0');
                if (element.textContent !== paddedValue) {
                    element.textContent = paddedValue;
                }
            };

            if (distance > 0) {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                updateValue('days', days);
                updateValue('hours', hours);
                updateValue('minutes', minutes);
                updateValue('seconds', seconds);
            } else {
                document.getElementById('days').textContent = '00';
                document.getElementById('hours').textContent = '00';
                document.getElementById('minutes').textContent = '00';
                document.getElementById('seconds').textContent = '00';
            }
        }

        // 初始化計數器
        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
</body>

</html>