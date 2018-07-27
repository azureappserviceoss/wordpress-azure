<?php

/**
 *	  [Discuz! X] (C)2001-2099 Comsenz Inc.
 *	  This is NOT a freeware, use is subject to license terms
 *
 *	  $Id: pushlist.php 33776 2013-08-12 08:33:59Z kamichen $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


$page = $_GET['page'] ? $_GET['page'] : 1;
$perpage = ($_GET['perpage'] && $_GET['perpage'] <= 100) ? $_GET['perpage'] :  10;
$start = ($page-1) * $perpage;
$pushlist = C::t('#mobileoem#mobileoem_pushthreads')->fetch_all_by_uid($_G['uid'], 0, $start, $perpage);
$tids = array_keys($pushlist);
$threads = C::t('forum_thread')->fetch_all($tids);
$result = array();
foreach($pushlist as $key=>$term) {
	$result[] = array(
		'tid' => $threads[$key]['tid'],
		'type' => $pushlist[$key]['type'],
		'subject' => $threads[$key]['subject'],
		'dateline' => $threads[$key]['dateline'],
		'author' => $threads[$key]['author'],
		'authorid' => $threads[$key]['authorid']
	);
}
$result = array('threads' => $result, 'status' => 1);