<?php
// Language Switcher
// 語言切換器 - 可在頁面中使用

function get_language_selector() {
    global $language;
    
    $languages = array(
        'zh-TW' => '繁體中文',
        'zh-CN' => '簡體中文',
        'en-US' => 'English'
    );
    
    $html = '<div class="language-selector" style="text-align: center; margin: 10px 0; padding: 10px; background: #f0f0f0; border-radius: 5px;">';
    $html .= '<small style="color: #666;">語言 | Language: </small>';
    
    foreach ($languages as $lang_code => $lang_name) {
        $active_class = ($language === $lang_code) ? 'style="font-weight: bold; color: #3b82f6;"' : '';
        $html .= ' <a href="?lang=' . $lang_code . '" ' . $active_class . '>' . $lang_name . '</a> ';
    }
    
    $html .= '</div>';
    
    return $html;
}

// 在 index.php 的 header-card 中添加此行即可顯示語言切換器:
// <?php echo get_language_selector(); ?>
?>
