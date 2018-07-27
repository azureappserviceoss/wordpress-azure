<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_favorite.php 33786 2013-08-13 05:56:56Z nemohou $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$_GET['type'] = in_array($_GET['type'], array("thread", "forum", "group", "blog", "album", "article", "all")) ? $_GET['type'] : 'all';
if($_GET['op'] == 'delete') {

	if($_GET['checkall']) {
		if($_GET['favorite']) {
			$deletecounter = array();
			$data = C::t('home_favorite')->fetch_all($_GET['favorite']);
			foreach($data as $dataone) {
				switch($dataone['idtype']) {
					case 'fid':
						$deletecounter['fids'][] = $dataone['id'];
						break;
					default:
						break;
				}
			}
			if($deletecounter['fids']) {
				C::t('forum_forum')->update_forum_counter($deletecounter['fids'], 0, 0, 0, 0, -1);
			}
			C::t('home_favorite')->delete($_GET['favorite'], false, $_G['uid']);
			if($_G['setting']['cloud_status']) {
				$favoriteService = Cloud::loadClass('Service_Client_Favorite');
				$favoriteService->remove($_G['uid'], $_GET['favorite'], TIMESTAMP);
			}
		}
		showmessage('favorite_delete_succeed', 'home.php?mod=space&uid='.$_G['uid'].'&do=favorite&view=me&type='.$_GET['type'].'&quickforward=1');
	} else {
		$favid = intval($_GET['favid']);
		$thevalue = C::t('home_favorite')->fetch($favid);
		if(empty($thevalue) || $thevalue['uid'] != $_G['uid']) {
			showmessage('favorite_does_not_exist');
		}

		if(submitcheck('deletesubmit')) {
			switch($thevalue['idtype']) {
				case 'fid':
					C::t('forum_forum')->update_forum_counter($thevalue['id'], 0, 0, 0, 0, -1);
					break;
				default:
					break;
			}
			C::t('home_favorite')->delete($favid);
			if($_G['setting']['cloud_status']) {
				$favoriteService = Cloud::loadClass('Service_Client_Favorite');
				$favoriteService->remove($_G['uid'], $favid);
			}
			showmessage('do_success', 'home.php?mod=space&uid='.$_G['uid'].'&do=favorite&view=me&type='.$_GET['type'].'&quickforward=1', array('favid' => $favid, 'id' => $thevalue['id']), array('showdialog'=>1, 'showmsg' => true, 'closetime' => true, 'locationtime' => 3));
		}
	}

} else {


	cknewuser();

	$type = empty($_GET['type']) ? '' : $_GET['type'];
	$id = empty($_GET['id']) ? 0 : intval($_GET['id']);
	$spaceuid = empty($_GET['spaceuid']) ? 0 : intval($_GET['spaceuid']);
	$idtype = $title = $icon = '';
	switch($type) {
		case 'thread':
			$idtype = 'tid';
			$thread = C::t('forum_thread')->fetch($id);
			$title = $thread['subject'];
			$icon = '<img src="static/image/feed/thread.gif" alt="thread" class="vm" /> ';
			break;
		case 'forum':
			$idtype = 'fid';
			$foruminfo = C::t('forum_forum')->fetch($id);
			loadcache('forums');
			$forum = $_G['cache']['forums'][$id];
			if(!$forum['viewperm'] || ($forum['viewperm'] && forumperm($forum['viewperm'])) || strstr($forum['users'], "\t$_G[uid]\t")) {
				$title = $foruminfo['status'] != 3 ? $foruminfo['name'] : '';
				$icon = '<img src="static/image/feed/discuz.gif" alt="forum" class="vm" /> ';
			}
			break;
		case 'blog';
			$idtype = 'blogid';
			$bloginfo = C::t('home_blog')->fetch($id);
			$title = ($bloginfo['uid'] == $spaceuid) ? $bloginfo['subject'] : '';
			$icon = '<img src="static/image/feed/blog.gif" alt="blog" class="vm" /> ';
			break;
		case 'group';
			$idtype = 'gid';
			$foruminfo = C::t('forum_forum')->fetch($id);
			$title = $foruminfo['status'] == 3 ? $foruminfo['name'] : '';
			$icon = '<img src="static/image/feed/group.gif" alt="group" class="vm" /> ';
			break;
		case 'album';
			$idtype = 'albumid';
			$result = C::t('home_album')->fetch($id, $spaceuid);
			$title = $result['albumname'];
			$icon = '<img src="static/image/feed/album.gif" alt="album" class="vm" /> ';
			break;
		case 'space';
			$idtype = 'uid';
			$_member = getuserbyuid($id);
			$title = $_member['username'];
			$unset($_member);
			$icon = '<img src="static/image/feed/profile.gif" alt="space" class="vm" /> ';
			break;
		case 'article';
			$idtype = 'aid';
			$article = C::t('portal_article_title')->fetch($id);
			$title = $article['title'];
			$icon = '<img src="static/image/feed/article.gif" alt="article" class="vm" /> ';
			break;
	}
	if(empty($idtype) || empty($title)) {
		showmessage('favorite_cannot_favorite');
	}

	$fav = C::t('home_favorite')->fetch_by_id_idtype($id, $idtype, $_G['uid']);
	if($fav) {
		showmessage('favorite_repeat');
	}
	$description = $extrajs = '';
	$description_show = nl2br($description);

	$fav_count = C::t('home_favorite')->count_by_id_idtype($id, $idtype);
	if(submitcheck('favoritesubmit') || ($type == 'forum' || $type == 'group' || $type == 'thread') && $_GET['formhash'] == FORMHASH) {
		$arr = array(
			'uid' => intval($_G['uid']),
			'idtype' => $idtype,
			'id' => $id,
			'spaceuid' => $spaceuid,
			'title' => getstr($title, 255),
			'description' => getstr($_POST['description'], '', 0, 0, 1),
			'dateline' => TIMESTAMP
		);
		$favid = C::t('home_favorite')->insert($arr, true);
		if($_G['setting']['cloud_status']) {
			$favoriteService = Cloud::loadClass('Service_Client_Favorite');
			$favoriteService->add($arr['uid'], $favid, $arr['id'], $arr['idtype'], $arr['title'], $arr['description'], TIMESTAMP);
		}
		switch($type) {
			case 'thread':
				C::t('forum_thread')->increase($id, array('favtimes'=>1));
				require_once libfile('function/forum');
				update_threadpartake($id);
				break;
			case 'forum':
				C::t('forum_forum')->update_forum_counter($id, 0, 0, 0, 0, 1);
				$extrajs = '<script type="text/javascript">$("number_favorite_num").innerHTML = parseInt($("number_favorite_num").innerHTML)+1;$("number_favorite").style.display="";</script>';
				dsetcookie('nofavfid', '', -1);
				break;
			case 'blog':
				C::t('home_blog')->increase($id, $spaceuid, array('favtimes' => 1));
				break;
			case 'group':
				C::t('forum_forum')->update_forum_counter($id, 0, 0, 0, 0, 1);
				break;
			case 'album':
				C::t('home_album')->update_num_by_albumid($id, 1, 'favtimes', $spaceuid);
				break;
			case 'space':
				C::t('common_member_status')->increase($id, array('favtimes' => 1));
				break;
			case 'article':
				C::t('portal_article_count')->increase($id, array('favtimes' => 1));
				break;
		}
		showmessage('favorite_do_success', dreferer(), array('id' => $id, 'favid' => $favid), array('showdialog' => true, 'closetime' => true, 'extrajs' => $extrajs));
	}
}

include template('home/spacecp_favorite');


?>