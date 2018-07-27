<?php

/**
 *	  [Discuz! X] (C)2001-2099 Comsenz Inc.
 *	  This is NOT a freeware, use is subject to license terms
 *
 *	  $Id: refreshpush.php 33800 2013-08-15 03:18:51Z kamichen $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


$check = C::t('#mobileoem#mobileoem_member')->fetch($_G['uid']);
if($check['newpush']) {
	C::t('#mobileoem#mobileoem_member')->update($_G['uid'], array('newpush' => 0));
	$threadinfo = C::t('forum_thread')->fetch($check['newpush']);
	$result = array('tid' => $check['newpush'], 'subject' => $threadinfo['subject'], 'status' => 1);
} else {
	$result = array('tid' => -1, 'subject' => '', 'status' => 1);
}