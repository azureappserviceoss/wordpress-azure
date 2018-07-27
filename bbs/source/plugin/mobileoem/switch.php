<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: switch.php 34012 2013-09-18 08:17:45Z nemohou $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$appIdentifier = 'mobileOem';
$pluginid = intval($_GET['pluginid']);

require_once libfile('class/cloudregister');

if($operation == 'enable') {

	new Cloud_Register($appIdentifier, $pluginid, 'appOpenFormView');
	$_G['setting']['plugins']['available'][] = 'mobileoem';
	$_G['setting']['plugins']['version']['mobileoem'] = $pluginarray['plugin']['version'];

} elseif($operation == 'disable') {

	new Cloud_Register($appIdentifier, $pluginid, 'appCloseReasonsView');
	foreach($_G['setting']['plugins']['available'] as $_k => $_v) {
		if($_v == 'mobileoem') {
			unset($_G['setting']['plugins']['available'][$_k]);
		}
	}

}

updatecache('mobile:mobile');