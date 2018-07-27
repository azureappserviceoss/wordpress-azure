<?php if ( ! defined('IN_WILDFIRE') ) die('The blaze was put out.');

/**

 * Created by JetBrains PhpStorm.

 * User: WYG

 * Date: 21/05/13

 * Time: 10:59 PM

 * To change this template use File | Settings | File Templates.

 */

//数据库配置

define('WF_REMOTE_DATABASE', true); //设置这里来决定使用“远程”或“本地”哪一个数据库服务器



if (WF_REMOTE_DATABASE) {

    //远程
    $WF_CONFIG['db_host'] = 'mahjongsite.mysql.database.azure.com'; //max ma test
   // $WF_CONFIG['db_host'] = 'localhost';

    $WF_CONFIG['db_user'] = 'mahjong_51max';

    $WF_CONFIG['db_pass'] = 'E7Tlb@?GQK6Q';

    $WF_CONFIG['db_name'] = 'mahjong_baoming';

} else {

    //本地

    $WF_CONFIG['db_host'] = 'localhost';

    $WF_CONFIG['db_user'] = 'root';

    $WF_CONFIG['db_pass'] = '123456';

    $WF_CONFIG['db_name'] = 'majiang_baoming';

}