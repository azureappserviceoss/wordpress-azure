<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_mobileoem_member.php 33721 2013-08-07 06:18:21Z kamichen $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_mobileoem_member extends discuz_table
{
	public function __construct() {
		$this->_table = 'mobileoem_member';
		$this->_pk    = 'uid';
	}

}

?>