<?php if ( ! defined('IN_WILDFIRE') ) die('The blaze was put out.');
/**
 * Created by JetBrains PhpStorm.
 * User: WYG
 * Date: 23/05/13
 * Time: 11:22 PM
 * 用于批量导出报名者的 Email 地址或其他信息
 */
$method = wf_get('m');

#管理员密码，需要时可修改为任意字符串
define('PASSWORD', 'TuI2004I');

#导出程序首页
define('DUMP_INDEX', wf_url('dump') );

switch ($method) {

    /**
     * 处理数据导出的请求
     */
    case 'doit':

        $date_y   = wf_post('Date_Year');
        $date_m   = wf_post('Date_Month');
        $date_d   = wf_post('Date_Day');
        $password = wf_post('password');

        if ( md5($password) == md5(PASSWORD) ) {
            //检查提交的日期是否合法
            if ( checkdate($date_m, $date_d, $date_y) ) {
                $timestamp = mktime(0, 0, 0, $date_m, $date_d, $date_y);
                $date = date('Y-m-d', $timestamp);
            } else {
                $date = date('Y-m-d');
            }

            //构建查询语句
            $sql = "SELECT `email` FROM `mj_baoming`\n"
                 . "WHERE FROM_UNIXTIME(`time`, '%Y-%m-%d') >= '{$date}'\n"
                 . "ORDER BY `time`";
            $data = $_G['db']->fetchAll($sql);

            //导出数据为文本文件
            if ($data === false) {
                echo "抱歉，没有符合要求的数据，请尝试其他的日期。<a href='javascript:window.history.back();'>返回</a>";
            } else {
                $txt_content = '';
                foreach ($data as $val) {
                    $txt_content .= $val['email'] . "\n";
                }

                #下载
                $filename = "majiang_email_" . date('Ymd', $timestamp) . ".txt";
                header("Content-Type:text/plain");
                header('Content-Disposition:attachment;filename=' . $filename);
                header('Content-Transfer-Encodeing: binary');
                echo $txt_content;
            }
        } else {
            wf_redirect(DUMP_INDEX);
        }

        break;

    /**
     * 数据导出首页
     */
    default:

        $form_action = wf_url('dump', 'doit');

        $smarty->assign('form_action', $form_action);
        $smarty->display('dump.tpl');
}
