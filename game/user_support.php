<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 25/10/13
 * Time: 5:41 PM
 */
require './inc/init.inc.php';
require './inc/attach_mailer_class.php';

if (! $uid) { header('Location:./index.php'); }

$op = $_GET['op'];

$found_error = false;
$message = '';

switch ($op) {
    case 'send':
        //防止过快提交反馈信息
        //靠 cookie 来进行限制是不可靠的，稍后应该改为通过 SESSION 或在数据库中记录用户的 IP 和提交时间来进行判定
        if ( isset( $_COOKIE['cma_support_sent'] ) ) {
            die("Don't submit your feedback so quickly!");
        }

        $_POST = Ca51_StringTools::trimPost($_POST);

        extract($_POST);

        //附件的上传
        if ( strlen( $_FILES['attachment']['name'] ) ) {
            #上传目录
            $upload_dir = './upload';

            #上传体积限制
            $upload_limit_size = 500 * 1000;

            #上传类型限制
            $upload_limit_types = array('image/jpeg', 'image/gif', 'image/png');

            $file_name = $_FILES['attachment']['name'];
            $file_name_tmp = $_FILES['attachment']['tmp_name'];
            $file_type = $_FILES['attachment']['type'];
            $file_size = $_FILES['attachment']['size'];
            $file_error = $_FILES['attachment']['error'];
            $file_full_path = "{$upload_dir}/{$file_name}";

            if ($file_size > $upload_limit_size) {
                $found_error = true;
                $message = '附件体积超过限制，请检查！';
            }

            if ( in_array($file_type, $upload_limit_types) === false ) {
                $found_error = true;
                $message = '不支持的附件类型，请检查！';
            }

            if (! $found_error) {
                @move_uploaded_file($file_name_tmp, $file_full_path);
            }
        }

        //邮件本体的发送
        if (! $found_error) {
            $target_email = 'support@mahjongcamp.com';
            //$target_email = 'max@vip.51.ca';
            $sender_email = 'noreply@51.ca';
            $user_email = & $_G['member']['email'];

            if ($subject != '' && $content != '') {
                $subject = mb_substr($subject, 0, 50, 'UTF-8');
                $content = mb_substr($content, 0, 1000, 'UTF-8');

                $additional_info = "桌号：{$table}\n"
                                 . "牌号：{$hand}\n"
                                 . "用户名：{$username}\n"
                                 . "用户ID：{$uid}\n"
                                 . "邮箱：{$email}\n";

                $content .= "\n\n------------------ 以下为附加信息 ------------------\n" . $additional_info;

                $mail = new attach_mailer('CMA', $sender_email, $target_email, '', '', $subject, $content);
                $mail->create_attachment_part($file_full_path, 'attachment', $file_type);
                $mail->process_mail();

                #移除发送后的附件
                @unlink($file_full_path);
            } else {
                $found_error = true;
                $message = '请填写“主题”和“内容”！';
            }
        }

        if (! $found_error) {
            $message = '您的意见和反馈已经成功发送，感谢支持！';

            #邮件成功发送后，在 SESSION 中做一个标记，持续30秒钟；以避免用户通过刷新页面来重复发送邮件
            setcookie( 'cma_support_sent', time(), time() + 30 );
        }

        include('./templates/support.tpl.php');
        break;

    default:
        $user_id = & $_G['uid'];
        $username = & $_G['username'];

        include('./templates/support.tpl.php');
}