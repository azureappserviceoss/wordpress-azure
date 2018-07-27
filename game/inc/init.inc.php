<?php
/**
 * Created by PhpStorm.
 * User: WYG
 * Date: 25/10/13
 * Time: 10:26 PM
 */
require './inc/functions.inc.php';
require './inc/uc_config.inc.php';
require './inc/game_config.inc.php';
require '../bbs/source/class/class_core.php';
require '../bbs/config/config_global.php';
require './uc_client/client.php';
require './inc/ca51_stringtools.class.php';


//初始化论坛的核心文件，获取已登录用户的 uid 来判断用户是否已经登录
$discuz = & discuz_core::instance();
$discuz->init();
$uid = & $_G['uid'];

//读取对应的界面语言包
require './inc/lang.inc.php';

if ( isset( $_GET['lang'] ) ) {
    $lang = $_GET['lang'];

    #将语言选项保存在 cookie 中
    $cookie_expired = time() + 3600 * 24 * 30;
    setcookie('mahjoing_game_lang', $lang, $cookie_expired);

} else {
    if ( isset( $_COOKIE['mahjoing_game_lang'] ) ) {
        $lang = $_COOKIE['mahjoing_game_lang'];
    } else {
        $lang = 'zh';
    }
}

if ( in_array($lang, array('en', 'zh')) === false ) { $lang = 'zh'; }
$l = $MJ_LANG[$lang];



