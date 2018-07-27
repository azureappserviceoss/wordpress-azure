<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: install.php 33974 2013-09-10 09:30:47Z nemohou $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$installlang = lang('forum/misc');

$sql = <<<EOF

DROP TABLE IF EXISTS pre_mobileoem_member;
CREATE TABLE IF NOT EXISTS `pre_mobileoem_member` (
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `newpush` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS pre_mobileoem_pushthreads;
CREATE TABLE IF NOT EXISTS `pre_mobileoem_pushthreads` (
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`type`,`tid`),
  KEY `uid` (`uid`,`type`,`dateline`)
) ENGINE=MyISAM;

INSERT INTO pre_common_credit_rule VALUES ('', '$installlang[mobileoem_creditrule]','mobileoemdaylogin','1','0','1','0','0','2','0','0','0','0','0','0','');

EOF;

runquery($sql);

$finish = TRUE;
?>