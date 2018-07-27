<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: secure.php 33682 2013-08-01 06:37:41Z nemohou $
 */

if(!defined('IN_MOBILE_API')) {
	exit('Access Denied');
}

include_once 'misc.php';

class mobile_api {

	function common() {
		global $_G;
		list($seccodecheck, $secqaacheck) = seccheck($_GET['type']);
		$sechash = random(8);
		if($seccodecheck || $secqaacheck) {
			$variable = array('sechash' => $sechash);
			if($seccodecheck) {
				$variable['seccode'] = $_G['siteurl'].'api/mobile/index.php?module=seccode&sechash='.$sechash.'&version='.(empty($_GET['secversion']) ? '1' : $_GET['secversion']);
			}
			if($secqaacheck) {
				$variable['secqaa'] = make_secqaa();
			}
		}
		mobile_core::result(mobile_core::variable($variable));
	}

	function output() {}

}

?>