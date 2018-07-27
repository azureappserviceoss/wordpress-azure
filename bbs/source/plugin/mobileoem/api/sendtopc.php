<?php

/**
 *	  [Discuz! X] (C)2001-2099 Comsenz Inc.
 *	  This is NOT a freeware, use is subject to license terms
 *
 *	  $Id: sendtopc.php 33976 2013-09-11 02:12:57Z nemohou $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$tid = dintval($_GET['tid']);
$thread = C::t('forum_thread')->fetch($tid);

notification_add($_G['uid'], "system", 'mobileoem:sendtopcnotice', array(
	'tid' => $tid,
	'subject' => $thread['subject']
), 1);

C::t('#mobileoem#mobileoem_pushthreads')->insert(array(
	'uid' => $_G['uid'],
	'tid' => $tid,
	'dateline' => TIMESTAMP,
	'type' => 1
), false, true);

$result = array('status' => 1);