<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: function_plugin.php 34010 2013-09-18 07:45:59Z nemohou $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('function/cloudaddons');

function plugininstall($pluginarray, $installtype = '', $available = 0) {
	if(!$pluginarray || !$pluginarray['plugin']['identifier']) {
		return false;
	}
	$plugin = C::t('common_plugin')->fetch_by_identifier($pluginarray['plugin']['identifier']);
	if($plugin) {
		return false;
	}

	$pluginarray['plugin']['modules'] = dunserialize($pluginarray['plugin']['modules']);
	$pluginarray['plugin']['modules']['extra']['installtype'] = $installtype;
	if(updatepluginlanguage($pluginarray)) {
		$pluginarray['plugin']['modules']['extra']['langexists'] = 1;
	}
	if(!empty($pluginarray['intro'])) {
		if(!empty($pluginarray['intro'])) {
			require_once libfile('function/discuzcode');
			$pluginarray['plugin']['modules']['extra']['intro'] = discuzcode(strip_tags($pluginarray['intro']), 1, 0);
		}
	}
	if(!empty($pluginarray['uninstallfile'])) {
		$pluginarray['plugin']['modules']['extra']['uninstallfile'] = $pluginarray['uninstallfile'];
	}
	if(!empty($pluginarray['checkfile'])) {
		$pluginarray['plugin']['modules']['extra']['checkfile'] = $pluginarray['checkfile'];
	}
	if(!empty($pluginarray['enablefile'])) {
		$pluginarray['plugin']['modules']['extra']['enablefile'] = $pluginarray['enablefile'];
	}
	if(!empty($pluginarray['disablefile'])) {
		$pluginarray['plugin']['modules']['extra']['disablefile'] = $pluginarray['disablefile'];
	}

	$pluginarray['plugin']['modules'] = serialize($pluginarray['plugin']['modules']);

	$data = array();
	foreach($pluginarray['plugin'] as $key => $val) {
		if($key == 'directory') {
			$val .= (!empty($val) && substr($val, -1) != '/') ? '/' : '';
		} elseif($key == 'available') {
			$val = $available;
		}
		$data[$key] = $val;
	}

	$pluginid = C::t('common_plugin')->insert($data, true);

	if(is_array($pluginarray['var'])) {
		foreach($pluginarray['var'] as $config) {
			$data = array('pluginid' => $pluginid);
			foreach($config as $key => $val) {
				$data[$key] = $val;
			}
			C::t('common_pluginvar')->insert($data);
		}
	}

	if(!empty($dir) && !empty($pluginarray['importfile'])) {
		require_once libfile('function/importdata');
		foreach($pluginarray['importfile'] as $importtype => $file) {
			if(in_array($importtype, array('smilies', 'styles'))) {
				$files = explode(',', $file);
				foreach($files as $file) {
					if(file_exists($file = DISCUZ_ROOT.'./source/plugin/'.$dir.'/'.$file)) {
						$importtxt = @implode('', file($file));
						$imporfun = 'import_'.$importtype;
						$imporfun();
					}
				}
			}
		}
	}

	cloudaddons_installlog($pluginarray['plugin']['identifier'].'.plugin');
	cron_create($pluginarray['plugin']['identifier']);
	updatecache(array('plugin', 'setting', 'styles'));
	cleartemplatecache();
	dsetcookie('addoncheck_plugin', '', -1);
	return $pluginid;
}

function pluginupgrade($pluginarray, $installtype) {
	if(!$pluginarray || !$pluginarray['plugin']['identifier']) {
		return false;
	}
	$plugin = C::t('common_plugin')->fetch_by_identifier($pluginarray['plugin']['identifier']);
	if(!$plugin) {
		return false;
	}
	if(is_array($pluginarray['var'])) {
		$pluginvars = $pluginvarsnew = array();
		foreach(C::t('common_pluginvar')->fetch_all_by_pluginid($plugin['pluginid']) as $pluginvar) {
			$pluginvars[] = $pluginvar['variable'];
		}
		foreach($pluginarray['var'] as $config) {
			if(!in_array($config['variable'], $pluginvars)) {
				$data = array('pluginid' => $plugin[pluginid]);
				foreach($config as $key => $val) {
					$data[$key] = $val;
				}
				C::t('common_pluginvar')->insert($data);
			} else {
				$data = array();
				foreach($config as $key => $val) {
					if($key != 'value') {
						$data[$key] = $val;
					}
				}
				if($data) {
					C::t('common_pluginvar')->update_by_variable($plugin['pluginid'], $config['variable'], $data);
				}
			}
			$pluginvarsnew[] = $config['variable'];
		}
		$pluginvardiff = array_diff($pluginvars, $pluginvarsnew);
		if($pluginvardiff) {
			C::t('common_pluginvar')->delete_by_variable($plugin['pluginid'], $pluginvardiff);
		}
	}

	$langexists = updatepluginlanguage($pluginarray);

	$pluginarray['plugin']['modules'] = dunserialize($pluginarray['plugin']['modules']);
	$plugin['modules'] = dunserialize($plugin['modules']);
	if(!empty($plugin['modules']['system'])) {
		$pluginarray['plugin']['modules']['system'] = $plugin['modules']['system'];
	}
	$plugin['modules']['extra']['installtype'] = $installtype;
	$pluginarray['plugin']['modules']['extra'] = $plugin['modules']['extra'];
	if(!empty($pluginarray['intro']) || $langexists) {
		if(!empty($pluginarray['intro'])) {
			require_once libfile('function/discuzcode');
			$pluginarray['plugin']['modules']['extra']['intro'] = discuzcode(strip_tags($pluginarray['intro']), 1, 0);
		}
		$langexists && $pluginarray['plugin']['modules']['extra']['langexists'] = 1;
	}
	if(!empty($pluginarray['uninstallfile'])) {
		$pluginarray['plugin']['modules']['extra']['uninstallfile'] = $pluginarray['uninstallfile'];
	}
	if(!empty($pluginarray['checkfile'])) {
		$pluginarray['plugin']['modules']['extra']['checkfile'] = $pluginarray['checkfile'];
	}
	if(!empty($pluginarray['enablefile'])) {
		$pluginarray['plugin']['modules']['extra']['enablefile'] = $pluginarray['enablefile'];
	}
	if(!empty($pluginarray['disablefile'])) {
		$pluginarray['plugin']['modules']['extra']['disablefile'] = $pluginarray['disablefile'];
	}
	$pluginarray['plugin']['modules'] = serialize($pluginarray['plugin']['modules']);

	C::t('common_plugin')->update($plugin['pluginid'], array('version' => $pluginarray['plugin']['version'], 'modules' => $pluginarray['plugin']['modules']));

	cloudaddons_installlog($pluginarray['plugin']['identifier'].'.plugin');
	cron_create($pluginarray['plugin']['identifier']);
	updatecache(array('plugin', 'setting', 'styles'));
	cleartemplatecache();
	dsetcookie('addoncheck_plugin', '', -1);
	return true;
}

function modulecmp($a, $b) {
	return $a['displayorder'] > $b['displayorder'] ? 1 : -1;
}

function updatepluginlanguage($pluginarray) {
	global $_G;
	if(!$pluginarray['language']) {
		return false;
	}
	foreach(array('script', 'template', 'install', 'system') as $type) {
		loadcache('pluginlanguage_'.$type, 1);
		if($type != 'system') {
			if(!empty($pluginarray['language'][$type.'lang'])) {
				$_G['cache']['pluginlanguage_'.$type][$pluginarray['plugin']['identifier']] = $pluginarray['language'][$type.'lang'];
			}
		} else {
			if(!empty($_G['config']['plugindeveloper']) && @include(DISCUZ_ROOT.'./data/plugindata/'.$pluginarray['plugin']['identifier'].'.lang.php')) {
				if(!empty($systemlang[$pluginarray['plugin']['identifier']])) {
					$pluginarray['language']['systemlang'] = $systemlang[$pluginarray['plugin']['identifier']];
				}
			}
			foreach($pluginarray['language']['systemlang'] as $file => $vars) {
				foreach($vars as $key => $var) {
					$_G['cache']['pluginlanguage_system'][$file][$key] = $var;
				}
			}
		}
		savecache('pluginlanguage_'.$type, $_G['cache']['pluginlanguage_'.$type]);
	}
	return true;
}

function runquery($sql) {
	global $_G;
	$tablepre = $_G['config']['db'][1]['tablepre'];
	$dbcharset = $_G['config']['db'][1]['dbcharset'];

	$sql = str_replace(array(' cdb_', ' `cdb_', ' pre_', ' `pre_'), array(' {tablepre}', ' `{tablepre}', ' {tablepre}', ' `{tablepre}'), $sql);
	$sql = str_replace("\r", "\n", str_replace(array(' {tablepre}', ' `{tablepre}'), array(' '.$tablepre, ' `'.$tablepre), $sql));

	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query;
		}
		$num++;
	}
	unset($sql);

	foreach($ret as $query) {
		$query = trim($query);
		if($query) {

			if(substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $query);
				DB::query(createtable($query, $dbcharset));

			} else {
				DB::query($query);
			}

		}
	}
}

function createtable($sql, $dbcharset) {
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
	(DB::$db->version() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
}

function cron_create($pluginid, $filename, $name, $weekday, $day, $hour, $minute) {
	if(!ispluginkey($pluginid)) {
		return false;
	}
	$dir = DISCUZ_ROOT.'./source/plugin/'.$pluginid.'/cron';
	if(!file_exists($dir)) {
		return false;
	}
	$crondir = dir($dir);
	while($filename = $crondir->read()) {
		if(!in_array($filename, array('.', '..')) && preg_match("/^cron\_[\w\.]+$/", $filename)) {
			$content = file_get_contents($dir.'/'.$filename);
			preg_match("/cronname\:(.+?)\n/", $content, $r);$name = lang('plugin/'.$pluginid, trim($r[1]));
			preg_match("/week\:(.+?)\n/", $content, $r);$weekday = trim($r[1]) ? intval($r[1]) : -1;
			preg_match("/day\:(.+?)\n/", $content, $r);$day = trim($r[1]) ? intval($r[1]) : -1;
			preg_match("/hour\:(.+?)\n/", $content, $r);$hour = trim($r[1]) ? intval($r[1]) : -1;
			preg_match("/minute\:(.+?)\n/", $content, $r);$minute = trim($r[1]) ? trim($r[1]) : 0;
			$minutenew = explode(',', $minute);
			foreach($minutenew as $key => $val) {
				$minutenew[$key] = $val = intval($val);
				if($val < 0 || $var > 59) {
					unset($minutenew[$key]);
				}
			}
			$minutenew = array_slice(array_unique($minutenew), 0, 12);
			$minutenew = implode("\t", $minutenew);
			$filename = $pluginid.':'.$filename;
			$cronid = C::t('common_cron')->get_cronid_by_filename($filename);
			if(!$cronid) {
				return C::t('common_cron')->insert(array(
					'available' => 1,
					'type' => 'plugin',
					'name' => $name,
					'filename' => $filename,
					'weekday' => $weekday,
					'day' => $day,
					'hour' => $hour,
					'minute' => $minutenew,
				), true);
			} else {
				C::t('common_cron')->update($cronid, array(
					'name' => $name,
					'weekday' => $weekday,
					'day' => $day,
					'hour' => $hour,
					'minute' => $minutenew,
				));
				return $cronid;
			}
		}
	}
}

function cron_delete($pluginid) {
	if(!ispluginkey($pluginid)) {
		return false;
	}
	$dir = DISCUZ_ROOT.'./source/plugin/'.$pluginid.'/cron';
	if(!file_exists($dir)) {
		return false;
	}
	$crondir = dir($dir);
	$count = 0;
	while($filename = $crondir->read()) {
		if(!in_array($filename, array('.', '..')) && preg_match("/^cron\_[\w\.]+$/", $filename)) {
			$filename = $pluginid.':'.$filename;
			$cronid = C::t('common_cron')->get_cronid_by_filename($filename);
			C::t('common_cron')->delete($cronid);
			$count++;
		}
	}
	return $count;
}

?>