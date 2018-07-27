<?php if ( ! defined('IN_WILDFIRE') ) die('The blaze was put out.');
/**
 * Created by JetBrains PhpStorm.
 * User: WYG
 * Date: 23/05/13
 * Time: 11:04 PM
 * To change this template use File | Settings | File Templates.
 */
function wf_mail($to, $subject, $message) {
    @mail($to, $subject, $message);
}
