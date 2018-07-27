<?php
/**
 * Created by JetBrains PhpStorm.
 * User: WYG
 * Date: 21/05/13
 * Time: 10:40 PM
 * To change this template use File | Settings | File Templates.
 */
define('IN_WILDFIRE', true);
define('WF_ROOT', dirname(__FILE__));

include_once(WF_ROOT . '/config/wf_config_base.php');
include_once(WF_ROOT . '/core/wf_function_common.php');
require_once(WF_ROOT . '/thirdpart/smarty/libs/Smarty.class.php');

if ( $WF_CONFIG['debug'] ) error_reporting(0);

$_G = array(); //全局变量数组


//启用 SESSION
if ( $WF_CONFIG['enable_session'] ) { session_start(); }

//设置时区
if ( isset($WF_CONFIG['default_time_zone']) ) {
    date_default_timezone_set($WF_CONFIG['default_time_zone']);
} else {
    date_default_timezone_set('America/Toronto');
}

//初始化数据库
if ( $WF_CONFIG['enable_database'] ) {
    include_once(WF_ROOT . '/config/wf_config_database.php');
    include_once(WF_ROOT . '/core/class/wf_class_database.php');
    include_once(WF_ROOT . '/core/class/wf_class_model.php');

    $_G['db_setting'] = array(
        'db_host' => $WF_CONFIG['db_host'],
        'db_user' => $WF_CONFIG['db_user'],
        'db_pass' => $WF_CONFIG['db_pass'],
        'db_name' => $WF_CONFIG['db_name']
    );

    $_G['db'] = new WF_Database($_G['db_setting']);
}

//初始化 Smarty 模板
$smarty = new Smarty();
$smarty->setTemplateDir($WF_CONFIG['smarty_tpl']);
$smarty->setCompileDir($WF_CONFIG['smarty_tpl_c']);
$smarty->setConfigDir($WF_CONFIG['smarty_conf']);
$smarty->setCacheDir($WF_CONFIG['smarty_cache']);

$_G['smarty'] = $smarty;