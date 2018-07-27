<?php if ( ! defined('IN_WILDFIRE') ) die('The blaze was put out.');
/**
 * Created by JetBrains PhpStorm.
 * User: WYG
 * Date: 23/05/13
 * Time: 11:22 PM
 * To change this template use File | Settings | File Templates.
 */
$method = wf_get('m');

#根据 URL 的传递参数决定所要显示的语种，默认为“中文”
if ( isset( $_GET['lang'] ) ) {
    $lang_setting = $_GET['lang'];
} elseif ( isset( $_POST['lang'] ) ) {
    $lang_setting = $_POST['lang'];
} else {
    $lang_setting = '';
}
$lang = in_array( $lang_setting, array('zh', 'en') ) ? $lang_setting : 'zh';

$_LANG['zh'] = array(
    'btn_back'      => '返回',
    'btn_ok'        => '确定',
    'msg_success'   => '报名成功，请等待我们与您取得联系！',
    'msg_recaptcha' => '您填写的验证码不正确，请再次尝试！'
);
$_LANG['en'] = array(
    'btn_back'      => 'Back',
    'btn_ok'        => 'OK',
    'msg_success'   => 'Your application has been submitted. <br />We will contact you as soon as possible.',
    'msg_recaptcha' => "The reCAPTCHA wasn't entered correctly. Go back and try it again."
);

switch ($method) {
    //info@flcsontario.ca & marketing@51.ca
    case 'submit':

        $model_baoming = new WF_Model('baoming', $_G['db_setting']);
        $data = $model_baoming->getPost($_POST);

        #如果启用了 Google 验证码
        if ( $WF_CONFIG['enable_reCAPTCHA'] ) {
            require_once(WF_ROOT . '/thirdpart/recaptcha/recaptchalib.php');

            $privatekey = & $WF_CONFIG['reCAPTCHA_KEY_PRIVATE'];

            $resp = recaptcha_check_answer ($privatekey,
                $_SERVER["REMOTE_ADDR"],
                $_POST["recaptcha_challenge_field"],
                $_POST["recaptcha_response_field"]);

            if (!$resp->is_valid) {
                $output = array(
                    'message' => $_LANG[$lang]['msg_recaptcha'],
                    'buttons' => "<a href='" .wf_url( 'baoming', '', array('lang' => $lang) ). "'>{$_LANG[$lang]['btn_ok']}</a>"
                );

                $smarty->assign($output);
                $smarty->display('baoming_successful.tpl');
                die();
            }
        }

        #服务器端验证，如果未通过验证则显示错误提示并终止程序运行。
        if (! is_array($data) ) {
            $button = "<a href='" .wf_url(). "'>{$_LANG[$lang]['btn_back']}</a>";
            wf_message($data, $button, 'baoming_successful.tpl');
        }

        $data['time']   = time();
        $data['advice'] = mb_strlen($data['advice'], 'UTF-8') > 0 ? nl2br($data['advice']) : 'N/A';
        $data['lang']   = & $lang;

        #存入数据库
        $model_baoming->save('mj_baoming', $data);

        #发送 email
        wf_loadHelper('mail');

        $arr_receivers = array('info@flcsontario.ca', 'marketing@51.ca', 'max@vip.51.ca', 'sarah@vip.51.ca');
        //$arr_receivers = array('max@vip.51.ca');
        $arr_levels    = array('', '会打国标麻将', '不会打国标麻将，会自己家乡的麻将', '完全不会');
        $arr_sites     = array('', 'Scarborough', 'Markham', 'Richmond Hill', 'Vaughan');

        $level_for_human = $arr_levels[ $data['level'] ];
        $site_for_human  = $arr_sites[ $data['site'] ];

        $email_time    = date('Y-m-d H:m:s');
        $email_subject = "【麻将大赛】报名者：{$data['name']}（{$email_time}）";
        $email_content = "[姓名] {$data['name']}\n"
                       . "[年龄] {$data['age']}\n"
                       . "[电话] {$data['phone']}\n"
                       . "[邮件] {$data['email']}\n"
                       . "[地址] {$data['address']}\n"
                       . "[水平] {$level_for_human}\n"
                       . "[地点] {$site_for_human}\n"
                       . "[建议] {$data['advice']}\n"
                       . "[语种] {$data['lang']}\n";

        foreach ($arr_receivers as $receiver) {
            wf_mail($receiver, $email_subject, $email_content);
        }

        wf_redirect( wf_url( 'baoming', 'successful', array('lang' => $data['lang']) ) );

        break;
    case 'successful':

        $output = array(
            'message' => $_LANG[$lang]['msg_success'],
            'buttons' => "<a href='" .wf_url( 'baoming', '', array('lang' => $lang) ). "'>{$_LANG[$lang]['btn_ok']}</a>",
            'lang'    => $lang
        );

        $smarty->assign($output);
        $smarty->display('baoming_successful.tpl');
        break;

    default:

        $form_action = wf_url('baoming', 'submit');

        #根据语言决定模板名称
        $template_name = "baoming.tpl";
        if ($lang !== 'zh') {
            $template_name = "baoming_{$lang}.tpl";
        }

        #是否启用 Google 验证码
        if ( $WF_CONFIG['enable_reCAPTCHA'] ) {
            require_once(WF_ROOT . '/thirdpart/recaptcha/recaptchalib.php');

            $publickey = & $WF_CONFIG['reCAPTCHA_KEY_PUBLIC'];
            $google_recaptcha = recaptcha_get_html($publickey);
        } else {
            $google_recaptcha = false;
        }

        $smarty->assign('google_recaptcha', $google_recaptcha);
        $smarty->assign('form_action', $form_action);
        $smarty->assign('lang', $lang);
        $smarty->display($template_name);
}
