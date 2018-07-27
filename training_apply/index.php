<?php
/**
 * Created by JetBrains PhpStorm.
 * User: WYG
 * Date: 21/05/13
 * Time: 10:58 PM
 * To change this template use File | Settings | File Templates.
 */
include_once('./wildfire.php');

$controller = wf_get('c');

//如果没有在 URL 中指定 c（controller），那么采用默认的 controller
if ( ! isset($controller) || trim($controller) == '') {
    $controller = $WF_CONFIG['default_controller'];
}

$controller_file = WF_ROOT . "/controller/wf_controller_{$controller}.php";
if ( is_file($controller_file) ) {
    include_once($controller_file);
} else {
    echo 'Controller file cannot be found.';
}

