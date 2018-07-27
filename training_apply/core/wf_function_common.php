<?php if ( ! defined('IN_WILDFIRE') ) die('The blaze was put out.');
/**
 * Created by JetBrains PhpStorm.
 * User: WYG
 * Date: 21/05/13
 * Time: 10:46 PM
 * To change this template use File | Settings | File Templates.
 */
function wf_version() {
    echo 'Wild Fire v1.0';
}

/**
 * 用于获取 $_GET 中的参数
 * @param string $index          URL中的参数名
 * @param string $default_value  默认值
 * @return bool|string
 */
function wf_get($index, $default_value = null) {
    if ( isset($_GET[$index]) ) {
        return $_GET[$index];
    } elseif ($default_value != null) {
        return $default_value;
    } else {
        return false;
    }
}

/**
 * 用于获取 $_POST 中的参数
 * @param string $index          form中的字段名
 * @param string $default_value  默认值
 * @return bool|string
 */
function wf_post($index, $default_value = null) {
    if ( isset($_POST[$index]) ) {
        return $_POST[$index];
    } elseif ($default_value != null) {
        return $default_value;
    } else {
        return false;
    }
}

/**
 * 加载一个或多个 helper 函数库
 * @param $helper_name  需要加载的 helper 函数库的名称，如果需要同时加载多个，用半角“逗号”分割。
 */
function wf_loadHelper($helper_name) {
    $arr_helper_names = array();

    if ( stripos($helper_name, ',') >= 0 ) {
        $arr_helper_names = explode(',', $helper_name);
    } else {
        $arr_helper_names[0] = $helper_name;
    }

    foreach ($arr_helper_names as $name) {
        $helper_file = WF_ROOT . "/core/helper/wf_helper_" . trim($name) . ".php";
        if ( is_file($helper_file) ) {
            include_once($helper_file);
        }
    }
}

function wf_redirect($target_url, $arr_params = null) {
    $params_in_url = '';

    if ( ! is_null($arr_params) ) {
        $tmp = '';
        foreach($arr_params as $param_key => $param_val) {
            $tmp .= "{$param_key}={$param_val}&";
        }
        $tmp = trim($tmp, '&');
        $params_in_url = '?' . $tmp;
    }
    header("Location:{$target_url}{$params_in_url}");
}

function wf_url($controller = '', $method = '', $param = '') {
    global $WF_CONFIG;

    $entrance_file = $WF_CONFIG['default_entrance'];
    $site_url      = $WF_CONFIG['site_url'];

    if ($controller == '' && $method == '') {
        return 'javascript:history.back();';
    }

    #处理所需传递的额外参数
    $url_params = '';
    if ( is_array($param) ) {
        $tmp_array = array();
        foreach ($param as $key => $val) {
            $tmp_array[] = $key .'='. urlencode($val);
        }
        $url_params = implode('&', $tmp_array);
    } else {
        $url_params = & $param;
    }

    if ($controller == '') {
        return "{$site_url}/{$entrance_file}&{$url_params}";
    } elseif ($method == '') {
        return "{$site_url}/{$entrance_file}?c={$controller}&{$url_params}";
    } else {
        return "{$site_url}/{$entrance_file}?c={$controller}&m={$method}&{$url_params}";
    }
}

/**
 * 以一种简易的方式显示提示信息
 * @param $message   提示信息内容
 * @param $buttons   提示信息下方的按钮，以数组形式存储。
 *                   例如：array( array('label' => '确定', 'url' => '/index.php'), array('label => '返回', 'url' => '/back.php') );
 * @param $template  模板名称
 */
function wf_message($message, $buttons, $template) {
    global $_G;

    $buttons_html = '';

    if ( is_array($buttons) ) {
        foreach ($buttons as $b) {
            $buttons_html .= "<li><a href='{$b['url']}'>{$b['label']}</a></li>";
        }
        $buttons_html = "<ul>{$buttons_html}</ul>";
    } else {
        $buttons_html = $buttons;
    }

    $_G['smarty']->assign('message', $message);
    $_G['smarty']->assign('buttons', $buttons_html);
    $_G['smarty']->display($template);
    die();
}