<?php

/**
 *	  [Discuz! X] (C)2001-2099 Comsenz Inc.
 *	  This is NOT a freeware, use is subject to license terms
 *
 *	  $Id: register.php 33778 2013-08-12 09:11:34Z kamichen $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


if(!C::t('#mobileoem#mobileoem_member')->fetch($_G['uid'])) {
	C::t('#mobileoem#mobileoem_member')->insert(array(
		'uid' => $_G['uid']
	), false, true);
}
$result = array('status' => 1);