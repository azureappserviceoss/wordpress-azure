<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_mobileoem_pushthreads.php 33740 2013-08-09 02:32:46Z kamichen $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_mobileoem_pushthreads extends discuz_table
{
	public function __construct() {
		$this->_table = 'mobileoem_pushthreads';
		$this->_pk    = 'uid';
	}

	public function fetch_all_by_uid($uid, $type = 0, $start = 0, $limit = 20) {
		if(!$uid) {
			return false;
		}
		return DB::fetch_all('SELECT * FROM %t WHERE uid=%d AND type=%d ORDER BY dateline DESC '.DB::limit($start, $limit), array($this->_table, $uid, $type), 'tid');
	}

	public function delete_by_uid_tid_type($uid, $tid, $type = 0) {
		if(!$uid || !$tid) {
			return false;
		}
		return DB::query('DELETE FROM %t WHERE uid=%d AND type=%d AND tid=%d', array($this->_table, $uid, $type, $tid));
	}
}

?>