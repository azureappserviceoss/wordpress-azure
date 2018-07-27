<?php

/**

 * Created by PhpStorm.

 * User: Max

 * Date: 25/10/13

 * Time: 5:41 PM

 */

require './inc/init.inc.php';



if (! $uid) { header('Location:./index.php'); }



extract( $_POST );



//如果语言选项已经存在于 cookie 中，则跳过从表单提交过来的值；改为从 cookie 直接提取

if ( isset( $_COOKIE['mahjoing_game_lang'] ) ) {

    $user_lang = & $_COOKIE['mahjoing_game_lang'];

    $user_country = & $_COOKIE['mahjoing_user_country'];

    $user_id = & $uid;

}



//登录成功后，更新 Discuz 的 session 数据表，否则无法获取固定的 session_id

discuz_session::updatesession();

$user_session_id = $_G['session']['sid'];





//通过配置文件中的 cookie 前缀，获取当前浏览器中保存的登录用的 sid（即 session_id）

/*

$c_pre = $_config['cookie']['cookiepre'].substr(md5($_config['cookie']['cookiepath'].'|'.$_config['cookie']['cookiedomain']), 0, 4).'_';

$user_session_id = $_COOKIE[$c_pre.'sid'];

*/



//校验语言选项

if ( in_array( $user_lang, array('zh', 'en') ) === false ) {

    $user_lang = 'zh';

}



//时间戳

$timestamp = time();

$timestamp_hash = md5($timestamp . KEY);



//用户名

$username = & $_G['username'];



//通过密钥组合加密生成校验码($uid.$country.$lang.$sid.$ts.$key)

$code = md5($user_id . $user_country . $user_lang . $user_session_id. $username . $timestamp . KEY);



//调用游戏的URL

$url = "https://www.mahjongcamp.com/cma/?uid={$user_id}&country={$user_country}&lang={$user_lang}&sid={$user_session_id}&name={$username}&ts={$timestamp}&code={$code}";



//调试用URL

$op_api = 'friends';

$code_api = md5($user_id . $op_api . $timestamp . KEY);

$url_api = "http://www.mahjong-ca.org/game/api.php?uid={$user_id}&op={$op_api}&ts={$timestamp}&code={$code_api}";

//echo $url_api;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title><?php echo $l['game_name']; ?> - <?php echo $l['official_website_title']; ?></title>

    <link href="style/style.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="js/jquery-1.8.3.js"></script>

    <script type="text/javascript" src="js/mahjonggame.js"></script>

</head>



<body>

    <div class="topnav"><?php include('./templates/top_nav.tpl.php'); ?></div>



    <div class="gamemainbox clearfix">

        <iframe id="mainiframe" src="<?php echo $url; ?>" width="100%" frameborder="0"></iframe>

    </div>



    <!-- 用户反馈 Begin -->

    <div id="user-support-wrap" style="display: none;">

        <div id="user-support-form">

            <div>

                <label>主题</label>

                <input type="text" name="user-support-subject" />

            </div>

            <div>

                <label>内容</label>

                <textarea rows="5" cols="30" name="user-support-content"></textarea>

            </div>

            <div>

                <label>您的Email地址</label>

                <input type="text" name="user-support-email" />

            </div>

            <div>

                <button type="button" id="user-support-button" onclick="sendUserSupport()">提交</button>

            </div>

            <p>注：以上内容将提交给游戏开发者，而不是“国标麻将协会”。</p>



            <input type="hidden" name="user-support-uid" value="<?php echo $user_id; ?>" />

            <input type="hidden" name="user-support-username" value="<?php echo $username; ?>" />

            <input type="hidden" name="user-support-time" value="<?php echo $timestamp; ?>" />

            <input type="hidden" name="user-support-time-hash" value="<?php echo $timestamp_hash; ?>" />

        </div><!--/#user-support-form-->



        <div id="user-support-message" style="display: none;">

            <p></p>

            <button type="button" onclick="confirmUserSupportMessage(false)">确定</button>

        </div><!--/#user-support-message-->

    </div>

    <!-- 用户反馈 End -->



</body>

</html>