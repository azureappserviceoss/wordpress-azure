<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: search.php 26313 2011-12-08 09:12:56Z yangli $
 */

define('APPTYPEID', 0);
define('CURSCRIPT', 'search');

require './source/class/class_core.php';

$discuz = C::app();

$modarray = array('my', 'user', 'curforum', 'newthread');

$cachelist = $slist = array();
$mod = '';
$discuz->cachelist = $cachelist;
$discuz->init();

if(in_array($discuz->var['mod'], $modarray) || !empty($_G['setting']['search'][$discuz->var['mod']]['status'])) {
	$mod = $discuz->var['mod'];
} else {
	foreach($_G['setting']['search'] as $mod => $value) {
		if(!empty($value['status'])) {
			break;
		}
	}
}
if(empty($mod)) {
	showmessage('search_closed');
}
define('CURMODULE', $mod);


runhooks();

require_once libfile('function/search');


$navtitle = lang('core', 'title_search');

if($mod == 'curforum') {
	$mod = 'forum';
	$_GET['srchfid'] = array($_GET['srhfid']);
} elseif($mod == 'forum') {
	$_GET['srhfid'] = 0;
}

require DISCUZ_ROOT.'./source/module/search/search_'.$mod.'.php';

?>