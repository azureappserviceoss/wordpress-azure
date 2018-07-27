<?php

/**

 * Created by PhpStorm.

 * User: WYG

 * Date: 27/10/13

 * Time: 5:11 PM

 */

error_reporting(E_ALL);



require './inc/game_config.inc.php';

require './inc/uc_config.inc.php';

require '../bbs/source/class/class_core.php';

require './uc_client/client.php';



//初始化 Discuz 核心

$discuz = & discuz_core::instance();

$discuz->init();



extract($_GET);



//包含 API 的查询结果

$result = false;



//计算传入的时间戳和当前时间戳的差，如果超过30秒，则视为非法请求

$diff_timestamp = time() - $ts;

$valid_timestamp = $diff_timestamp > 30 ? false : true;



//比对校验码是否无误

$valid_code = ( md5($uid . $op. $ts. KEY) == $code ) ? true :false;



//对通过 URL 传入 API 的参数进行校验，校验用的字符串的构成方法是 md5(用户id + session_id + 查询类型 + 密钥)

if ( $valid_code && $valid_timestamp ) {

    switch ($op) {



        //返回指定用户的基础信息（用户id，登陆名，登录状态）

        case 'profile':

            $data = uc_get_user($uid, 1);

            if ($data !== 0) {

                $result = array(

                    'uid' => $data[0],

                    'display_name' => $data[1]

                );



                #通过接收到的 session_id 和 $uid 来查询对应的记录是否存在于 Discuz 的 session 数据表中，来判断该用户是否已经登录

                $sql_session = "SELECT COUNT(*) AS `count` FROM `pre_common_session`\n"

                             . "WHERE `uid` = '{$uid}' AND `sid` = '{$sid}'";

                $query = DB::query($sql_session);

                $data_session = DB::fetch($query);



                #将查询结果作为登录状态的“状态码”返回

                $result['online'] = $data_session['count'];

            } else {

                $result = false;

            }



            header('Content-type: application/json');

            echo json_encode($result);

            break;



        //返回指定用户的头像 URL

        case 'avatar':

            $avatar_url = "http://www.mahjong-org.azurewebsites.net/bbs/uc_server/avatar.php?uid={$uid}&size=small";



            $ch = curl_init($avatar_url);

            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

            curl_exec($ch);

            $result = curl_getinfo($ch);

            curl_close($ch);



            header("Content-Type: text/plain");

            if ($result !== false) {

                echo $result['url'];

            } else {

                echo '0';

            }



            break;



        //返回指定用户的“好友列表”

        case 'friends':

            $data = uc_friend_ls($uid, $page = 1, $pagesize = 100, $totalnum = 100, 0);

            if ( count($data) ) {

                $tmp_arr = array();

                $tmp_row = array();

                foreach ($data as $v) {

                    $tmp_row['name'] = $v['fusername'];

                    $tmp_row['id'] = $v['fuid'];

                    $tmp_arr[] = $tmp_row;

                }

            }

            $result['data'] = $tmp_arr;



            header('Content-type: application/json');

            echo json_encode($result);

            break;



        case 'support':

            $user_id = & $_G['uid'];

            $username = & $_G['username'];



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



            //输出

            include('./templates/support.tpl.php');

            break;

    }

} else {

    die('Unauthorized access!');

}