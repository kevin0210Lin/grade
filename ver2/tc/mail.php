<?php
// filepath: /home/linonlin/domains/j3test.linonlinedata.com/public_html/J3_tc/mail.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/home/linonlin/PHPMailer/src/Exception.php';
require '/home/linonlin/PHPMailer/src/PHPMailer.php';
require '/home/linonlin/PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // 設定郵件伺服器
    $mail->isSMTP();
    $mail->Host = 'mail.linonlinedata.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'notice@linonlinedata.com';
    $mail->Password = 'Kevin0210@@@';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->CharSet = 'UTF-8';     

    // 收件人
    $mail->setFrom('notice@linonlinedata.com', '國三複習考成績管理系統');
    $mail->addAddress('212027@s.lhvs.tyc.edu.tw', '林楷恩');
    $mail->addReplyTo('linonlin@linonlinedata.com', '管理員');


    // 內容
    $mail->isHTML(true);
    $mail->Subject = '成績統整完畢通知~';
    $mail->Body    = '老師您好，第X週次的成績已於114/8/1 23:59:59結算完成，特此通知~';
    $mail->AltBody = '';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
