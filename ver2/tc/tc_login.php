<?php
ini_set('display_errors', 1);
session_start();
$old_id = $_SESSION["old_id"];
$old_pass = $_SESSION["old_pass"];

if (isset($_SESSION['login_check'])) {
    if ($_SESSION['login_check'] != "T") {
        echo "<script>alert('您尚未登入');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    }
} else {
    echo "<script>alert('您尚未登入');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <title>密碼變更 - 六和高中國三成績系統</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="tc-shared-styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Microsoft JhengHei', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            width: 100%;
        }

        .card-modern {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header-modern {
            background: #3b82f6;
            padding: 40px 30px;
            text-align: center;
        }

        .card-title {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .card-title i {
            font-size: 2.2rem;
        }

        .card-body-modern {
            padding: 40px 30px;
        }

        .form-group-modern {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .form-label i {
            color: #3b82f6;
        }

        .form-control-modern {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control-modern::placeholder {
            color: #aaa;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            background: #3b82f6;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }

        .btn-submit:hover {
            background: #2563eb;
        }

        .info-text {
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .info-text i {
            color: #3b82f6;
            margin-right: 5px;
        }

        /* 響應式設計 */
        @media (max-width: 576px) {
            .card-title {
                font-size: 1.5rem;
            }

            .card-header-modern,
            .card-body-modern {
                padding: 30px 20px;
            }

            .form-control-modern {
                padding: 12px 15px;
            }

            .btn-submit {
                padding: 12px;
                font-size: 1rem;
            }
        }

        /* 密碼顯示切換 */
        .password-toggle {
            position: relative;
        }

        .password-toggle-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            transition: color 0.3s ease;
        }

        .password-toggle-icon:hover {
            color: #3b82f6;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card-modern">
            <div class="card-header-modern">
                <h1 class="card-title">
                    <i class="fas fa-key"></i>
                    密碼變更
                </h1>
            </div>
            
            <div class="card-body-modern">
                <form action="tc_login_update.php" method="post" id="passwordForm">
                    
                    <div class="form-group-modern">
                        <label class="form-label">
                            <i class="fas fa-lock"></i>
                            新密碼
                        </label>
                        <div class="password-toggle">
                            <input type="password" 
                                   required 
                                   placeholder="請輸入新密碼 (請勿與預設密碼相同)"
                                   class="form-control-modern" 
                                   name="new_pass"
                                   id="newPassword"
                                   minlength="4">
                            <i class="fas fa-eye password-toggle-icon" id="togglePassword1"></i>
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label">
                            <i class="fas fa-lock-open"></i>
                            確認新密碼
                        </label>
                        <div class="password-toggle">
                            <input type="password" 
                                   required 
                                   placeholder="請再次輸入新密碼"
                                   class="form-control-modern" 
                                   name="new_pass_check"
                                   id="confirmPassword"
                                   minlength="4">
                            <i class="fas fa-eye password-toggle-icon" id="togglePassword2"></i>
                        </div>
                    </div>

                    <input type="hidden" name="old_id" value="<?= htmlspecialchars($old_id) ?>">
                    <input type="hidden" name="old_pass" value="<?= htmlspecialchars($old_pass) ?>">

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        儲存變更
                    </button>

                    <p class="info-text">
                        <i class="fas fa-info-circle"></i>
                        請妥善保管您的新密碼
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        // 密碼顯示切換功能
        document.getElementById('togglePassword1').addEventListener('click', function() {
            const passwordInput = document.getElementById('newPassword');
            const icon = this;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        document.getElementById('togglePassword2').addEventListener('click', function() {
            const passwordInput = document.getElementById('confirmPassword');
            const icon = this;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // 表單驗證
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('兩次輸入的密碼不一致，請重新輸入！');
                return false;
            }
            
            if (newPassword.length < 4) {
                e.preventDefault();
                alert('密碼長度至少需要 4 個字元！');
                return false;
            }
        });
    </script>
</body>

</html>