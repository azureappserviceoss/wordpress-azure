<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: mobileoem.class.php 34006 2013-09-18 05:38:59Z nemohou $
 */

class plugin_mobileoem{
	function plugin_mobileoem() {

	}

	function checkCloud() {
		global $_G;

		loadcache('mobileoem_data');
		if(!$_G['cache']['mobileoem_data'] || (TIMESTAMP - $_G['cache']['mobileoem_data']['timestamp']) > 86400) {
			try {
				$cloud = Cloud::loadClass('Service_Client_OEM');
				$clouddata = $cloud->checkApp();
			} catch (Exception $e) {
				$clouddata = array('errCode' => 1);
			}
			if(!$clouddata['errCode']) {
				if(!$clouddata['isDone']) {
					$_G['cache']['mobileoem_data'] = array();
				} else {
					$_G['cache']['mobileoem_data'] = $clouddata;
				}
			}
			$_G['cache']['mobileoem_data']['timestamp'] = TIMESTAMP;
			savecache('mobileoem_data', $_G['cache']['mobileoem_data']);
		}

		$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'iphone') !== false || strpos($useragent, 'ios') !== false) {
			$this->platform = '&platform=ios';
		} elseif(strpos($useragent, 'android') !== false) {
			$this->platform = '&platform=android';
		} elseif(strpos($useragent, 'windows phone') !== false) {
			$this->platform = '&platform=windowsphone';
		} else {
			$this->platform = '';
		}
	}
}

class mobileplugin_mobileoem{
	function global_footer_mobile() {
		global $_G;
		loadcache('mobileoem_data');
	}
}

class plugin_mobileoem_forum extends plugin_mobileoem {
	function viewthread_useraction() {
		global $_G;
		$this->checkCloud();
		if(!$_G['cache']['mobileoem_data']['iframeUrl']) {
			return '';
		}
		include_once template('mobileoem:module');
		return tpl_mobileoem_viewthread_useraction($_G['tid']);
	}
}

class mobileplugin_mobileoem_forum extends plugin_mobileoem {
	function viewthread_fastpost_button_mobile_output() {
		global $_G;
		$this->checkCloud();
		if($_G['cache']['mobileoem_data']['iframeUrl']) {
			return '&nbsp;&nbsp;<a href="'.$_G['cache']['mobileoem_data']['iframeUrl'].$this->platform.'" style="color: #A5A5A5;">'.lang('plugin/mobileoem', 'touch_tipsa').'</a>';
		} else {
			return '';
		}
	}

	function post_bottom_mobile_output() {
		global $_G;
		$this->checkCloud();
		if($_G['cache']['mobileoem_data']['iframeUrl']) {
			return '<div style="text-align:center;width:100%;"><br/><a href="'.$_G['cache']['mobileoem_data']['iframeUrl'].$this->platform.'" style="color: #A5A5A5;">'.lang('plugin/mobileoem', 'touch_tipsa').'</a></div>';
		} else {
			return '';
		}
	}
}
class mobileplugin_mobileoem_member extends plugin_mobileoem {
	function logging_bottom_mobile_output() {
		global $_G;
		$this->checkCloud();
		if($_G['cache']['mobileoem_data']['iframeUrl']) {
			return '<div style="text-align:center;width:100%;"><br/><a href="'.$_G['cache']['mobileoem_data']['iframeUrl'].$this->platform.'" style="color: #A5A5A5;">'.lang('plugin/mobileoem', 'touch_tipsb').'</a></div>';
		} else {
			return '';
		}
	}
}