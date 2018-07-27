<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: function_blog.php 34105 2013-10-11 05:38:37Z hypowang $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function blog_post($POST, $olds=array()) {
	global $_G, $space;

	$isself = 1;
	if(!empty($olds['uid']) && $olds['uid'] != $_G['uid']) {
		$isself = 0;
		$__G = $_G;
		$_G['uid'] = $olds['uid'];
		$_G['username'] = addslashes($olds['username']);
	}

	$POST['subject'] = getstr(trim($POST['subject']), 80);
	$POST['subject'] = censor($POST['subject']);
	if(strlen($POST['subject'])<1) $POST['subject'] = dgmdate($_G['timestamp'], 'Y-m-d');
	$POST['friend'] = intval($POST['friend']);

	$POST['target_ids'] = '';
	if($POST['friend'] == 2) {
		$uids = array();
		$names = empty($_POST['target_names'])?array():explode(',', preg_replace("/(\s+)/s", ',', $_POST['target_names']));
		if($names) {
			$uids = C::t('common_member')->fetch_all_uid_by_username($names);
		}
		if(empty($uids)) {
			$POST['friend'] = 3;
		} else {
			$POST['target_ids'] = implode(',', $uids);
		}
	} elseif($POST['friend'] == 4) {
		$POST['password'] = trim($POST['password']);
		if($POST['password'] == '') $POST['friend'] = 0;
	}
	if($POST['friend'] !== 2) {
		$POST['target_ids'] = '';
	}
	if($POST['friend'] !== 4) {
		$POST['password'] == '';
	}

	$POST['tag'] = dhtmlspecialchars(trim($POST['tag']));
	$POST['tag'] = getstr($POST['tag'], 500);
	$POST['tag'] = censor($POST['tag']);

	$POST['message'] = checkhtml($POST['message']);
	if($_G['mobile']) {
		$POST['message'] = getstr($POST['message'], 0, 0, 0, 1);
		$POST['message'] = censor($POST['message']);
	} else {
		$POST['message'] = getstr($POST['message'], 0, 0, 0, 0, 1);
		$POST['message'] = censor($POST['message']);
		$POST['message'] = preg_replace(array(
			"/\<div\>\<\/div\>/i",
			"/\<a\s+href\=\"([^\>]+?)\"\>/i"
		), array(
			'',
			'<a href="\\1" target="_blank">'
		), $POST['message']);
	}
	$message = $POST['message'];
	if(censormod($message) || censormod($POST['subject']) || $_G['group']['allowblogmod']) {
		$blog_status = 1;
	} else {
		$blog_status = 0;
	}

	if(empty($olds['classid']) || $POST['classid'] != $olds['classid']) {
		if(!empty($POST['classid']) && substr($POST['classid'], 0, 4) == 'new:') {
			$classname = dhtmlspecialchars(trim(substr($POST['classid'], 4)));
			$classname = getstr($classname);
			$classname = censor($classname);
			if(empty($classname)) {
				$classid = 0;
			} else {
				$classid = C::t('home_class')->fetch_classid_by_uid_classname($_G['uid'], $classname);
				if(empty($classid)) {
					$setarr = array(
						'classname' => $classname,
						'uid' => $_G['uid'],
						'dateline' => $_G['timestamp']
					);
					$classid = C::t('home_class')->insert($setarr, true);
				}
			}
		} else {
			$classid = intval($POST['classid']);

		}
	} else {
		$classid = $olds['classid'];
	}
	if($classid && empty($classname)) {
		$query = C::t('home_class')->fetch($classid);
		$classname = ($query['uid'] == $_G['uid']) ? $query['classname'] : '';
		if(empty($classname)) $classid = 0;
	}

	$blogarr = array(
		'subject' => $POST['subject'],
		'classid' => $classid,
		'friend' => $POST['friend'],
		'password' => $POST['password'],
		'noreply' => empty($POST['noreply'])?0:1,
		'catid' => intval($POST['catid']),
		'status' => $blog_status,
	);

	$titlepic = '';

	$uploads = array();
	if(!empty($POST['picids'])) {
		$picids = array_keys($POST['picids']);
		$query = C::t('home_pic')->fetch_all_by_uid($_G['uid'], 0, 0, $picids);
		foreach($query as $value) {
			if(empty($titlepic) && $value['thumb']) {
				$titlepic = getimgthumbname($value['filepath']);
				$blogarr['picflag'] = $value['remote']?2:1;
			}
			$picurl = pic_get($value['filepath'], 'album', $value['thumb'], $value['remote'], 0);
			$uploads[md5($picurl)] = $value;
		}
		if(empty($titlepic) && $value) {
			$titlepic = $value['filepath'];
			$blogarr['picflag'] = $value['remote']?2:1;
		}
	}

	if($uploads) {
		$albumid = 0;
		if($POST['savealbumid'] < 0 && !empty($POST['newalbum'])) {
			$albumname = addslashes(dhtmlspecialchars(trim($POST['newalbum'])));
			if(empty($albumname)) $albumname = dgmdate($_G['timestamp'],'Ymd');
			$albumarr = array('albumname' => $albumname);
			$albumid = album_creat($albumarr);
		} else {
			$albumid = $POST['savealbumid'] < 0 ? 0 : intval($POST['savealbumid']);
		}
		if($albumid) {
			C::t('home_pic')->update_for_uid($_G['uid'], $picids, array('albumid' => $albumid));
			album_update_pic($albumid);
		}
		preg_match_all("/\s*\<img src=\"(.+?)\".*?\>\s*/is", $message, $mathes);
		if(!empty($mathes[1])) {
			foreach ($mathes[1] as $key => $value) {
				$urlmd5 = md5($value);
				if(!empty($uploads[$urlmd5])) {
					unset($uploads[$urlmd5]);
				}
			}
		}
		foreach ($uploads as $value) {
			$picurl = pic_get($value['filepath'], 'album', $value['thumb'], $value['remote'], 0);
			$message .= "<div class=\"uchome-message-pic\"><img src=\"$picurl\"><p>$value[title]</p></div>";
		}
	}

	$ckmessage = preg_replace("/(\<div\>|\<\/div\>|\s|\&nbsp\;|\<br\>|\<p\>|\<\/p\>)+/is", '', $message);
	if(empty($ckmessage)) {
		return false;
	}


	if(checkperm('manageblog')) {
		$blogarr['hot'] = intval($POST['hot']);
	}

	if($olds['blogid']) {

		if($blogarr['catid'] != $olds['catid']) {
			if($olds['catid']) {
				C::t('home_blog_category')->update_num_by_catid(-1, $olds['catid'], true, true);
			}
			if($blogarr['catid']) {
				C::t('home_blog_category')->update_num_by_catid(1, $blogarr['catid']);
			}
		}

		$blogid = $olds['blogid'];
		C::t('home_blog')->update($blogid, $blogarr);

		$fuids = array();

		$blogarr['uid'] = $olds['uid'];
		$blogarr['username'] = $olds['username'];
	} else {

		if($blogarr['catid']) {
			C::t('home_blog_category')->update_num_by_catid(1, $blogarr['catid']);
		}

		$blogarr['uid'] = $_G['uid'];
		$blogarr['username'] = $_G['username'];
		$blogarr['dateline'] = empty($POST['dateline'])?$_G['timestamp']:$POST['dateline'];
		$blogid = C::t('home_blog')->insert($blogarr, true);

		C::t('common_member_status')->update($_G['uid'], array('lastpost' => $_G['timestamp']));
		C::t('common_member_field_home')->update($_G['uid'], array('recentnote'=>$POST['subject']));
	}

	$blogarr['blogid'] = $blogid;
	$class_tag = new tag();
	$POST['tag'] = $olds ? $class_tag->update_field($POST['tag'], $blogid, 'blogid') : $class_tag->add_tag($POST['tag'], $blogid, 'blogid');
	$fieldarr = array(
		'message' => $message,
		'postip' => $_G['clientip'],
		'port' => $_G['remoteport'],
		'target_ids' => $POST['target_ids'],
		'tag' => $POST['tag']
	);

	if(!empty($titlepic)) {
		$fieldarr['pic'] = $titlepic;
	}

	if($olds) {
		C::t('home_blogfield')->update($blogid, $fieldarr);
	} else {
		$fieldarr['blogid'] = $blogid;
		$fieldarr['uid'] = $blogarr['uid'];
		C::t('home_blogfield')->insert($fieldarr);
	}

	if($isself && !$olds && $blog_status == 0) {
		updatecreditbyaction('publishblog', 0, array('blogs' => 1));

		include_once libfile('function/stat');
		updatestat('blog');
	}

	if($olds['blogid'] && $blog_status == 1) {
		updatecreditbyaction('publishblog', 0, array('blogs' => -1), '', -1);
		include_once libfile('function/stat');
		updatestat('blog');
	}

	if($POST['makefeed'] && $blog_status == 0) {
		include_once libfile('function/feed');
		feed_publish($blogid, 'blogid', $olds?0:1);
	}

	if(!empty($__G)) $_G = $__G;
	if($blog_status == 1) {
		updatemoderate('blogid', $blogid);
		manage_addnotify('verifyblog');
	}
	return $blogarr;
}

function checkhtml($html) {
	if(!checkperm('allowhtml')) {

		preg_match_all("/\<([^\<]+)\>/is", $html, $ms);

		$searchs[] = '<';
		$replaces[] = '&lt;';
		$searchs[] = '>';
		$replaces[] = '&gt;';

		if($ms[1]) {
			$allowtags = 'img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote';
			$ms[1] = array_unique($ms[1]);
			foreach ($ms[1] as $value) {
				$searchs[] = "&lt;".$value."&gt;";

				$value = str_replace('&amp;', '_uch_tmp_str_', $value);
				$value = dhtmlspecialchars($value);
				$value = str_replace('_uch_tmp_str_', '&amp;', $value);

				$value = str_replace(array('\\','/*'), array('.','/.'), $value);
				$skipkeys = array('onabort','onactivate','onafterprint','onafterupdate','onbeforeactivate','onbeforecopy','onbeforecut','onbeforedeactivate',
						'onbeforeeditfocus','onbeforepaste','onbeforeprint','onbeforeunload','onbeforeupdate','onblur','onbounce','oncellchange','onchange',
						'onclick','oncontextmenu','oncontrolselect','oncopy','oncut','ondataavailable','ondatasetchanged','ondatasetcomplete','ondblclick',
						'ondeactivate','ondrag','ondragend','ondragenter','ondragleave','ondragover','ondragstart','ondrop','onerror','onerrorupdate',
						'onfilterchange','onfinish','onfocus','onfocusin','onfocusout','onhelp','onkeydown','onkeypress','onkeyup','onlayoutcomplete',
						'onload','onlosecapture','onmousedown','onmouseenter','onmouseleave','onmousemove','onmouseout','onmouseover','onmouseup','onmousewheel',
						'onmove','onmoveend','onmovestart','onpaste','onpropertychange','onreadystatechange','onreset','onresize','onresizeend','onresizestart',
						'onrowenter','onrowexit','onrowsdelete','onrowsinserted','onscroll','onselect','onselectionchange','onselectstart','onstart','onstop',
						'onsubmit','onunload','javascript','script','eval','behaviour','expression','style','class');
				$skipstr = implode('|', $skipkeys);
				$value = preg_replace(array("/($skipstr)/i"), '.', $value);
				if(!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
					$value = '';
				}
				$replaces[] = empty($value)?'':"<".str_replace('&quot;', '"', $value).">";
			}
		}
		$html = str_replace($searchs, $replaces, $html);
	}

	return $html;
}

function blog_bbcode($message) {
	$message = preg_replace("/\[flash\=?(media|real|mp3)*\](.+?)\[\/flash\]/ie", "blog_flash('\\2', '\\1')", $message);
	return $message;
}
function blog_flash($swf_url, $type='') {
	$width = '520';
	$height = '390';
	preg_match("/((https?|ftp|gopher|news|telnet|rtsp|mms|callto|bctp|thunder|qqdl|synacast){1}:\/\/|www\.)[^\[\"']+/i", $swf_url, $matches);
	$swf_url = $matches[0];
	if ($type == 'media') {
		$html = '<object classid="clsid:6bf52a52-394a-11d3-b153-00c04f79faa6" width="'.$width.'" height="'.$height.'">
			<param name="autostart" value="0">
			<param name="url" value="'.$swf_url.'">
			<embed autostart="false" src="'.$swf_url.'" type="video/x-ms-wmv" width="'.$width.'" height="'.$height.'" controls="imagewindow" console="cons"></embed>
			</object>';
	} elseif ($type == 'real') {
		$html = '<object classid="clsid:cfcdaa03-8be4-11cf-b84b-0020afbbccfa" width="'.$width.'" height="'.$height.'">
			<param name="autostart" value="0">
			<param name="src" value="'.$swf_url.'">
			<param name="controls" value="Imagewindow,controlpanel">
			<param name="console" value="cons">
			<embed autostart="false" src="'.$swf_url.'" type="audio/x-pn-realaudio-plugin" width="'.$width.'" height="'.$height.'" controls="controlpanel" console="cons"></embed>
			</object>';
	} elseif ($type == 'mp3') {
		$swf_url = urlencode(str_replace('&amp;', '&', $swf_url));
		$html = '<object id="audioplayer_SHAREID" height="24" width="290" data="'.STATICURL.'image/common/player.swf" type="application/x-shockwave-flash">
			<param value="'.STATICURL.'image/common/player.swf" name="movie"/>
			<param value="autostart=yes&bg=0xCDDFF3&leftbg=0x357DCE&lefticon=0xF2F2F2&rightbg=0xF06A51&rightbghover=0xAF2910&righticon=0xF2F2F2&righticonhover=0xFFFFFF&text=0x357DCE&slider=0x357DCE&track=0xFFFFFF&border=0xFFFFFF&loader=0xAF2910&soundFile='.$swf_url.'" name="FlashVars"/>
			<param value="high" name="quality"/>
			<param value="false" name="menu"/>
			<param name="allowscriptaccess" value="never">
			<param name="allowNetworking" value="internal">
			<param value="#FFFFFF" name="bgcolor"/>
	    	</object>';

	} else {
		$extname = substr($swf_url, strrpos($swf_url, '.')+1);
		$randomid = 'swf_'.random(3);
		if($extname == 'swf') {
			$html = '<span id="'.$randomid.'"></span><script type="text/javascript" reload="1">$(\''.$randomid.'\').innerHTML=AC_FL_RunContent(\'width\', \''.$width.'\', \'height\', \''.$height.'\', \'allowNetworking\', \'internal\', \'allowScriptAccess\', \'never\', \'src\', encodeURI(\''.$swf_url.'\'), \'quality\', \'high\', \'bgcolor\', \'#ffffff\', \'wmode\', \'transparent\', \'allowfullscreen\', \'true\');</script>';
		} else {
			$html = '<span id="'.$randomid.'"></span><script type="text/javascript" reload="1">$(\''.$randomid.'\').innerHTML=AC_FL_RunContent(\'width\', \''.$width.'\', \'height\', \''.$height.'\', \'allowNetworking\', \'internal\', \'allowScriptAccess\', \'never\', \'src\', \''.STATICURL.'image/common/flvplayer.swf\', \'flashvars\', \'file='.rawurlencode($swf_url).'\', \'quality\', \'high\', \'wmode\', \'transparent\', \'allowfullscreen\', \'true\');</script>';
		}
	}
	return $html;
}
?>