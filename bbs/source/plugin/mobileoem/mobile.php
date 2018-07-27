<?php

/**
 *	  [Discuz! X] (C)2001-2099 Comsenz Inc.
 *	  This is NOT a freeware, use is subject to license terms
 *
 *	  $Id: mobile.php 33977 2013-09-11 02:34:45Z nemohou $
 */

define('IN_MOBILE_API', 1);

chdir('../../../');

require './source/class/class_core.php';
$discuz = C::app();
$cachelist = array('plugin');
$discuz->cachelist = $cachelist;
$discuz->init();

if((empty($_G['uid']) || $_GET['formhash'] != FORMHASH)) {
	exit(oemjson(array('status' => -2)));
}

if(in_array($_GET['module'], array('register', 'refreshpush', 'sendtopc', 'pushlist', 'pushtomobile', 'removepush'))) {
	require_once 'source/plugin/mobileoem/api/'.$_GET['module'].'.php';
} else {
	exit(oemjson(array('status' => -3)));
}

echo oemjson($result);
exit;

function oemjson($encode) {
	require_once 'source/plugin/mobileoem/json.class.php';
	return CJSON::encode($encode);
}