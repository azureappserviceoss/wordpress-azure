<?php if ( ! defined('IN_WILDFIRE') ) die('The blaze was put out.');

/**

 * Created by JetBrains PhpStorm.

 * User: WYG

 * Date: 21/05/13

 * Time: 10:34 PM

 * To change this template use File | Settings | File Templates.

 */

$WF_CONFIG['enable_session'] = true; //是否启用 SESSION



$WF_CONFIG['default_time_zone'] = 'America/Toronto'; //默认时区，参考 <http://www.php.net/manual/en/timezones.php>



$WF_CONFIG['site_url'] = 'http://mahjong-org.azurewebsites.net/training_apply';



$WF_CONFIG['debug'] = false;



$WF_CONFIG['enable_database'] = true;



$WF_CONFIG['default_entrance'] = 'index.php';



$WF_CONFIG['default_controller'] = 'baoming';



//Smarty 模板配置

$WF_CONFIG['smarty_tpl']   = WF_ROOT . '/view/';

$WF_CONFIG['smarty_tpl_c'] = WF_ROOT . '/data/templates_c/';

$WF_CONFIG['smarty_conf']  = WF_ROOT . '/config/';

$WF_CONFIG['smarty_cache'] = WF_ROOT . '/data/cache/';