<?php
// Language Configuration for Student Login Management System
// 學生登入管理系統 - 語言配置

// Detect language from browser or session
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'zh-TW';
if (isset($_GET['lang'])) {
    $_SESSION['language'] = $_GET['lang'];
    $language = $_GET['lang'];
}

// Language pack definitions
$lang = array();

// === Traditional Chinese (繁體中文) ===
$lang['zh-TW'] = array(
    // Page titles
    'page_title' => '學生登入管理系統',
    'loading_title' => '更新處理中...',
    
    // Header
    'school_name' => '六和高中 國三成績系統',
    'hello' => '您好',
    'logout' => '登出',
    'back_to_home' => '返回主頁面',
    'grade_management' => '成績管理',
    
    // Table headers and labels
    'student_login_management' => '學生登入帳號管理',
    'class' => '班級',
    'seat_number' => '座號',
    'name' => '姓名',
    'login_password' => '登入密碼',
    'save_changes' => '儲存變更',
    
    // Notifications
    'not_logged_in' => '您尚未登入',
    'admin_cannot_modify' => '管理員身分不能更改帳號密碼！',
    'update_in_progress' => '正在更新資料...',
    'please_wait' => '請稍候，系統正在處理您的請求',
    'changes_saved' => '修正已儲存',
    
    // Buttons
    'confirm_button' => '確認',
    'cancel_button' => '取消',
    'save_button' => '儲存',
);

// === English ===
$lang['en-US'] = array(
    // Page titles
    'page_title' => 'Student Login Management System',
    'loading_title' => 'Processing Update...',
    
    // Header
    'school_name' => 'Liuhe Senior High School Grade 9 System',
    'hello' => 'Welcome',
    'logout' => 'Logout',
    'back_to_home' => 'Back to Home',
    'grade_management' => 'Grade Management',
    
    // Table headers and labels
    'student_login_management' => 'Student Login Account Management',
    'class' => 'Class',
    'seat_number' => 'Seat #',
    'name' => 'Name',
    'login_password' => 'Login Password',
    'save_changes' => 'Save Changes',
    
    // Notifications
    'not_logged_in' => 'You are not logged in',
    'admin_cannot_modify' => 'Admin cannot modify account passwords!',
    'update_in_progress' => 'Updating data...',
    'please_wait' => 'Please wait, the system is processing your request',
    'changes_saved' => 'Changes have been saved',
    
    // Buttons
    'confirm_button' => 'Confirm',
    'cancel_button' => 'Cancel',
    'save_button' => 'Save',
);

// === Simplified Chinese (簡體中文) ===
$lang['zh-CN'] = array(
    // Page titles
    'page_title' => '学生登入管理系统',
    'loading_title' => '更新处理中...',
    
    // Header
    'school_name' => '六和高中 国三成绩系统',
    'hello' => '您好',
    'logout' => '登出',
    'back_to_home' => '返回主页面',
    'grade_management' => '成绩管理',
    
    // Table headers and labels
    'student_login_management' => '学生登入账号管理',
    'class' => '班级',
    'seat_number' => '座号',
    'name' => '姓名',
    'login_password' => '登入密码',
    'save_changes' => '保存变更',
    
    // Notifications
    'not_logged_in' => '您尚未登入',
    'admin_cannot_modify' => '管理员身份不能更改账号密码！',
    'update_in_progress' => '正在更新数据...',
    'please_wait' => '请稍候，系统正在处理您的请求',
    'changes_saved' => '修正已保存',
    
    // Buttons
    'confirm_button' => '确认',
    'cancel_button' => '取消',
    'save_button' => '保存',
);

// Helper function to get language string
function _lang($key) {
    global $lang, $language;
    if (isset($lang[$language][$key])) {
        return $lang[$language][$key];
    }
    // Fallback to Traditional Chinese if key not found
    if (isset($lang['zh-TW'][$key])) {
        return $lang['zh-TW'][$key];
    }
    return $key; // Return key name if translation not found
}
?>
