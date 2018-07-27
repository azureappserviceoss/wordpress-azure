<?php

/**
 *	  [Discuz! X] (C)2001-2099 Comsenz Inc.
 *	  This is NOT a freeware, use is subject to license terms
 *
 *	  $Id: pushtomobile.php 34003 2013-09-18 04:31:14Z nemohou $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$tid = dintval($_GET['tid']);
$pushuid = C::t('#mobileoem#mobileoem_member')->fetch($_G['uid']);

$qrfile = DISCUZ_ROOT.'./data/cache/mobileoem_siteqrcode.png';
if(!file_exists($qrfile) || $_G['adminid'] == 1) {
	loadcache('mobileoem_data');
	require_once DISCUZ_ROOT.'source/plugin/mobileoem/qrcode.class.php';
	$cloud = Cloud::loadClass('Service_Client_OEM');
	$url = $cloud->getDownloadUrl();
	if(!$url) {
		dexit();
	}
	QRcode::png($url, $qrfile, 6, 6);
}

if($pushuid) {
	C::t('#mobileoem#mobileoem_member')->update($_G['uid'], array('newpush' => $tid));
	C::t('#mobileoem#mobileoem_pushthreads')->insert(array(
		'uid' => $_G['uid'],
		'tid' => $tid,
		'dateline' => TIMESTAMP,
		'type' => 0
	), false, true);
	$noticeService = Cloud::loadClass('Service_Client_Notification');
	$extra = array('tId' => $tid, 'notekey' => 'mobileoem_pushtomobile');
	$noticeService->add($_G['uid'], 0, 'post', 0, '', 0, '', '', 0, TIMESTAMP, $extra);
	showmessage('mobileoem:pushsuccess', '', array(), array('alert'=> 'right', 'closetime' => true, 'locationtime' => true, 'showdialog' => 1));
} else {
	require template('mobileoem:downloadguide');
}

dexit();