<?php

/**
 *	  [Discuz! X] (C)2001-2099 Comsenz Inc.
 *	  This is NOT a freeware, use is subject to license terms
 *
 *	  $Id: pushlist.php 33721 2013-08-07 06:18:21Z kamichen $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$tid = dintval($_GET['tid']);
C::t('#mobileoem#mobileoem_pushthreads')->delete_by_uid_tid_type($_G['uid'], $tid, 0);

$result = array(
	'tid' => $tid,
	'status' => 1
);