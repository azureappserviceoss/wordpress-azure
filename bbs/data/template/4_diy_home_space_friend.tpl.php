<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('space_friend');
0
|| checktplrefresh('./template/mahjong/home/space_friend.htm', './template/mahjong/home/space_header.htm', 1510757556, 'diy', './data/template/4_diy_home_space_friend.tpl.php', './template/mahjong', 'home/space_friend')
|| checktplrefresh('./template/mahjong/home/space_friend.htm', './template/mahjong/home/space_friend_nav.htm', 1510757556, 'diy', './data/template/4_diy_home_space_friend.tpl.php', './template/mahjong', 'home/space_friend')
|| checktplrefresh('./template/mahjong/home/space_friend.htm', './template/mahjong/home/space_userabout.htm', 1510757556, 'diy', './data/template/4_diy_home_space_friend.tpl.php', './template/mahjong', 'home/space_friend')
|| checktplrefresh('./template/mahjong/home/space_friend.htm', './template/mahjong/common/header_common.htm', 1510757556, 'diy', './data/template/4_diy_home_space_friend.tpl.php', './template/mahjong', 'home/space_friend')
|| checktplrefresh('./template/mahjong/home/space_friend.htm', './template/mahjong/home/space_diy.htm', 1510757556, 'diy', './data/template/4_diy_home_space_friend.tpl.php', './template/mahjong', 'home/space_friend')
|| checktplrefresh('./template/mahjong/home/space_friend.htm', './template/mahjong/home/space_header_personalnv.htm', 1510757556, 'diy', './data/template/4_diy_home_space_friend.tpl.php', './template/mahjong', 'home/space_friend')
;?>
<?php if(empty($diymode)) { include template('common/header'); ?><div id="pt" class="bm cl">
<div class="z">
<a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em>
<a href="home.php?mod=space&amp;do=friend">好友</a> <em>&rsaquo;</em>
好友列表
</div>
</div>
<style id="diy_style" type="text/css"></style>
<div class="wp">
<!--[diy=diy1]--><div id="diy1" class="area"></div><!--[/diy]-->
</div>
<div id="ct" class="ct2_a wp cl">
<div class="mn">
<!--[diy=diycontenttop]--><div id="diycontenttop" class="area"></div><!--[/diy]-->
<div class="bm bw0">
<?php if($space['self']) { ?>
<h1 class="mt"><img alt="friend" src="<?php echo STATICURL;?>image/feed/friend.gif" class="vm" /> 好友列表</h1>
<ul class="tb cl">
<li<?php echo $a_actives['me'];?>><a href="home.php?mod=space&amp;do=friend">全部好友列表</a></li>
<?php if(empty($_G['setting']['sessionclose'])) { ?>
<li<?php echo $a_actives['onlinefriend'];?>><a href="home.php?mod=space&amp;do=friend&amp;view=online&amp;type=friend">当前在线的好友</a></li>
<li<?php echo $a_actives['onlinemember'];?>><a href="home.php?mod=space&amp;do=friend&amp;view=online&amp;type=member">在线成员</a></li>
<?php } ?>
<li<?php echo $a_actives['onlinenear'];?>><a href="home.php?mod=space&amp;do=friend&amp;view=online&amp;type=near">就在我附近的朋友</a></li>
<li<?php echo $a_actives['visitor'];?>><a href="home.php?mod=space&amp;do=friend&amp;view=visitor">我的访客</a></li>
<li<?php echo $a_actives['trace'];?>><a href="home.php?mod=space&amp;do=friend&amp;view=trace">我的足迹</a></li>
<li<?php echo $a_actives['blacklist'];?>><a href="home.php?mod=space&amp;do=friend&amp;view=blacklist">我的黑名单</a></li>
</ul>
<div class="tbmu cl">
<?php if($_GET['view']=='blacklist') { ?>
加入到黑名单的用户，将会从您的好友列表中删除。同时，对方将不能进行与您相关的打招呼、踩日志、加好友、评论、留言、短消息等互动行为 
<?php } elseif($_GET['view']=='me') { ?>
<p class="y">
当前共有 <span class="xw1"><?php echo $count;?></span> 个好友
<?php if($maxfriendnum) { ?>
(最多可以添加 <?php echo $maxfriendnum;?> 个好友)
<?php if($_G['setting']['magicstatus'] && $_G['setting']['magics']['friendnum']) { ?>
<img src="<?php echo STATICURL;?>image/magic/friendnum.small.gif" alt="friendnum" class="vm" />
<a id="a_magic_friendnum" href="home.php?mod=magic&amp;mid=friendnum" onmousemove="showTip(this)" tip="您可以购买道具“<?php echo $_G['setting']['magics']['friendnum'];?>”来扩容，让自己可以添加更多的好友 " onclick="showWindow('magics', this.href, 'get', 0);return false;">我要扩容</a>
<?php } } ?>
</p>
<p class="z">
按照<a href="home.php?mod=space&amp;do=friend&amp;order=num" class="xw1" onmouseover="showTip(this)" tip="好友热度是系统根据您与好友之间交互的动作自动累计的一个参考值，数值越大，表示您与这位好友互动越频繁">好友热度</a>排序
</p>
<?php } elseif($_GET['view']=='online') { if($_GET['type'] == 'friend') { ?>
这些好友当前在线，赶快去拜访一下吧
<?php } elseif($_GET['type']=='near') { ?>
通过系统匹配，这些朋友就在您附近，您可能认识他们
<?php } else { ?>
显示当前全部在线的用户
<?php } } elseif($_GET['view']=='visitor') { ?>
他们拜访过您，回访一下吧
<?php } elseif($_GET['view']=='trace') { ?>
您曾经拜访过的用户列表
<?php } ?>
</div>

<?php if($_GET['view']=='blacklist') { ?>
<h2 class="mtw xs2">添加黑名单用户</h2>
<div class="bm bmn mtm cl">
<form method="post" autocomplete="off" name="blackform" action="home.php?mod=spacecp&amp;ac=friend&amp;op=blacklist&amp;start=<?php echo $_GET['start'];?>">
<table cellpadding="0" cellspacing="0" class="tfm">
<tr>
<th>用户名</th>
<td>
<input type="text" name="username" value="" size="15" class="px vm" />
<button type="submit" name="blacklistsubmit_btn" id="moodsubmit_btn" value="true" class="pn vm"><em>添加</em></button>
</td>
</tr>
</table>
<input type="hidden" name="blacklistsubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
</form>
</div>
<?php } } } else { if($_G['setting']['homepagestyle']) { $_G[cookie][extstyle] = false;?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>" />
<?php if($_G['config']['output']['iecompatible']) { ?><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE<?php echo $_G['config']['output']['iecompatible'];?>" /><?php } ?>
<title><?php if(!empty($navtitle)) { ?><?php echo $navtitle;?> - <?php } if(empty($nobbname)) { ?> <?php echo $_G['setting']['bbname'];?> - <?php } ?> Powered by Discuz!</title>
<?php echo $_G['setting']['seohead'];?>

<meta name="keywords" content="<?php if(!empty($metakeywords)) { echo dhtmlspecialchars($metakeywords); } ?>" />
<meta name="description" content="<?php if(!empty($metadescription)) { echo dhtmlspecialchars($metadescription); ?> <?php } if(empty($nobbname)) { ?>,<?php echo $_G['setting']['bbname'];?><?php } ?>" />
<meta name="generator" content="Discuz! <?php echo $_G['setting']['version'];?>" />
<meta name="author" content="Discuz! Team and Comsenz UI Team" />
<meta name="copyright" content="2001-2013 Comsenz Inc." />
<meta name="MSSmartTagsPreventParsing" content="True" />
<meta http-equiv="MSThemeCompatible" content="Yes" />
<base href="<?php echo $_G['siteurl'];?>" /><link rel="stylesheet" type="text/css" href="data/cache/style_<?php echo STYLEID;?>_common.css?<?php echo VERHASH;?>" /><link rel="stylesheet" type="text/css" href="data/cache/style_<?php echo STYLEID;?>_home_space.css?<?php echo VERHASH;?>" /><?php if($_G['uid'] && isset($_G['cookie']['extstyle']) && strpos($_G['cookie']['extstyle'], TPLDIR) !== false) { ?><link rel="stylesheet" id="css_extstyle" type="text/css" href="<?php echo $_G['cookie']['extstyle'];?>/style.css" /><?php } elseif($_G['style']['defaultextstyle']) { ?><link rel="stylesheet" id="css_extstyle" type="text/css" href="<?php echo $_G['style']['defaultextstyle'];?>/style.css" /><?php } ?><script type="text/javascript">var STYLEID = '<?php echo STYLEID;?>', STATICURL = '<?php echo STATICURL;?>', IMGDIR = '<?php echo IMGDIR;?>', VERHASH = '<?php echo VERHASH;?>', charset = '<?php echo CHARSET;?>', discuz_uid = '<?php echo $_G['uid'];?>', cookiepre = '<?php echo $_G['config']['cookie']['cookiepre'];?>', cookiedomain = '<?php echo $_G['config']['cookie']['cookiedomain'];?>', cookiepath = '<?php echo $_G['config']['cookie']['cookiepath'];?>', showusercard = '<?php echo $_G['setting']['showusercard'];?>', attackevasive = '<?php echo $_G['config']['security']['attackevasive'];?>', disallowfloat = '<?php echo $_G['setting']['disallowfloat'];?>', creditnotice = '<?php if($_G['setting']['creditnotice']) { ?><?php echo $_G['setting']['creditnames'];?><?php } ?>', defaultstyle = '<?php echo $_G['style']['defaultextstyle'];?>', REPORTURL = '<?php echo $_G['currenturl_encode'];?>', SITEURL = '<?php echo $_G['siteurl'];?>', JSPATH = '<?php echo $_G['setting']['jspath'];?>', DYNAMICURL = '<?php echo $_G['dynamicurl'];?>';</script>
<script src="<?php echo $_G['setting']['jspath'];?>common.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<?php if(empty($_GET['diy'])) { $_GET['diy'] = '';?><?php } if(!isset($topic)) { $topic = array();?><?php } ?>
<script src="<?php echo $_G['setting']['jspath'];?>home.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $_G['setting']['csspath'];?>data/cache/style_<?php echo STYLEID;?>_css_space.css?<?php echo VERHASH;?>" />
<link id="style_css" rel="stylesheet" type="text/css" href="<?php echo STATICURL;?>space/<?php if($space['theme']) { ?><?php echo $space['theme'];?><?php } else { ?>t1<?php } ?>/style.css?<?php echo VERHASH;?>">
<style id="diy_style"><?php echo $space['spacecss'];?></style>
</head>

<body id="space" onkeydown="if(event.keyCode==27) return false;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>

<?php if($space['self'] && $_GET['diy'] == 'yes' && $do == 'index' ) { ?>
<link rel="stylesheet" type="text/css" href="<?php echo $_G['setting']['csspath'];?>data/cache/style_<?php echo STYLEID;?>_css_diy.css?<?php echo VERHASH;?>" /><div id="controlpanel" class="cl">
<div id="controlheader" class="cl">
<p class="y">
<span id="navcancel"><a href="javascript:;" onclick="spaceDiy.cancel();return false;">关闭</a></span>
<span id="navsave"><a href="javascript:;" onclick="javascript:spaceDiy.save();return false;">保存</a></span>
<span id="button_redo" class="unusable"><a href="javascript:;" onclick="spaceDiy.redo();return false;" title="重做" onfocus="this.blur();">重做</a></span>
<span id="button_undo" class="unusable"><a href="javascript:;" onclick="spaceDiy.undo();return false;" title="撤销" onfocus="this.blur();">撤销</a></span>
</p>
<ul id="controlnav">
<li id="navstart" class="current"><a href="javascript:" onclick="spaceDiy.getdiy('start');this.blur();return false;">开始</a></li>
<li id="navlayout"><a href="javascript:;" onclick="spaceDiy.getdiy('layout');this.blur();return false;">版式/布局</a></li>
<li id="navstyle"><a href="javascript:;" onclick="spaceDiy.getdiy('style');this.blur();return false;">风格</a></li>
<li id="navblock"><a href="javascript:;" onclick="spaceDiy.getdiy('block');this.blur();return false;">模块</a></li>
<li id="navdiy"><a href="javascript:;" onclick="spaceDiy.getdiy('diy');this.blur();return false;">自定义装扮</a></li>
</ul>
</div>
<div id="controlcontent" class="cl">
<ul id="contentstart" class="content">
  <li><a href="javascript:;" onclick="spaceDiy.getdiy('layout');return false;"><img src="<?php echo STATICURL;?>image/diy/layout.png" />版式</a></li>
  <li><a href="javascript:;" onclick="spaceDiy.getdiy('style');return false;"><img src="<?php echo STATICURL;?>image/diy/style.png" />风格</a></li>
  <li><a href="javascript:;" onclick="spaceDiy.getdiy('block');return false;"><img src="<?php echo STATICURL;?>image/diy/module.png" />添加模块</a></li>
  <li><a href="javascript:;" onclick="spaceDiy.getdiy('diy', 'topicid', '<?php echo $topic['topicid'];?>');return false;"><img src="<?php echo STATICURL;?>image/diy/diy.png" />自定义</a></li>
</ul>
</div>
<div id="cpfooter"><table cellpadding="0" cellspacing="0" width="100%"><tr><td class="l">&nbsp;</td><td class="c">&nbsp;</td><td class="r">&nbsp;</td></tr></table></div>
</div>
<form method="post" autocomplete="off" name="diyform" action="home.php?mod=spacecp&amp;ac=index">
<input type="hidden" name="spacecss" value="" />
<input type="hidden" name="style" value="<?php echo $space['theme'];?>" />
<input type="hidden" name="layoutdata" value="" />
<input type="hidden" name="currentlayout" value="<?php echo $userdiy['currentlayout'];?>" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="diysubmit" value="true"/>
</form><?php } ?>

<div id="toptb" class="cl">
<?php if($_G['uid']) { ?>
<div class="y">
<a href="home.php?mod=space&amp;uid=<?php echo $_G['uid'];?>" class="xw1" target="_blank" title="访问我的空间"><?php echo $_G['member']['username'];?></a>
<a href="javascript:;" id="myspace" class="showmenu cur1" onmouseover="showMenu(this.id);">快捷导航</a>
<?php if(!empty($_G['setting']['pluginhooks']['global_usernav_extra1'])) echo $_G['setting']['pluginhooks']['global_usernav_extra1'];?>
<a href="home.php?mod=spacecp">设置</a>
<a href="home.php?mod=space&amp;do=pm" id="pm_ntc" target="_blank"<?php if($_G['member']['newpm']) { ?> class="new"<?php } ?>>消息<?php if($_G['member']['newpm']) { ?>(<?php echo $_G['member']['newpm'];?>)<?php } ?></a>
<a href="home.php?mod=space&amp;do=notice" id="myprompt" target="_blank"<?php if($_G['member']['newprompt']) { ?> class="new"<?php } ?>>提醒<?php if($_G['member']['newprompt']) { ?>(<?php echo $_G['member']['newprompt'];?>)<?php } ?></a><span id="myprompt_check"></span>
<?php if($_G['group']['allowmanagearticle'] || $_G['group']['allowdiy']|| getstatus($_G['member']['allowadmincp'], 4) || getstatus($_G['member']['allowadmincp'], 2) || getstatus($_G['member']['allowadmincp'], 3) || in_array($_G['uid'], $_G['setting']['ext_portalmanager'])) { ?><a href="portal.php?mod=portalcp">门户管理</a><?php } if($_G['uid'] && $_G['group']['radminid'] > 1) { ?><a href="forum.php?mod=modcp&amp;fid=<?php echo $_G['fid'];?>" target="_blank"><?php echo $_G['setting']['navs']['2']['navname'];?>管理</a><?php } if($_G['uid'] && ($_G['group']['radminid'] == 1 || getstatus($_G['member']['allowadmincp'], 1))) { ?><a href="admin.php" target="_blank">管理中心</a><?php } ?>
<?php if(!empty($_G['setting']['pluginhooks']['global_usernav_extra2'])) echo $_G['setting']['pluginhooks']['global_usernav_extra2'];?>
<a href="member.php?mod=logging&amp;action=logout&amp;formhash=<?php echo FORMHASH;?>">退出</a>
<?php if($space['self'] && $do == 'index') { ?><a id="diy-tg" href="javascript:openDiy();" title="装扮空间">DIY</a><?php } ?>
</div>
<?php } elseif(!empty($_G['cookie']['loginuser'])) { ?>
<div class="y">
<a id="loginuser" class="xw1"><?php echo $_G['cookie']['loginuser'];?></a>
<a href="member.php?mod=logging&amp;action=login" onclick="showWindow('login', this.href)">激活</a>
<a href="member.php?mod=logging&amp;action=logout&amp;formhash=<?php echo FORMHASH;?>">退出</a>
</div>
<?php } elseif($_G['connectguest']) { ?>
<div class="y">
<a href="member.php?mod=connect" target="_blank">完善帐号信息</a> <a href="member.php?mod=connect&amp;ac=bind" target="_blank">绑定已有帐号</a>
</div>
<?php } else { ?>
<div class="y">
<a href="member.php?mod=<?php echo $_G['setting']['regname'];?>"><?php echo $_G['setting']['reglinkname'];?></a>
<a href="member.php?mod=logging&amp;action=login" onclick="showWindow('login', this.href)">登录</a>
</div>
<?php } ?>
<div class="z">
<a href="./" title="<?php echo $_G['setting']['bbname'];?>" class="xw1"><?php echo $_G['setting']['bbname'];?></a>
<a href="home.php?mod=space&amp;do=home" id="navs" class="showmenu" onmouseover="showMenu(this.id);">返回首页</a>
</div>
</div>
<?php if($space['status'] == -1 && $_G['adminid'] == 1 ) { ?>
<p class="ptw xw1 xi1 hm"><img src="<?php echo IMGDIR;?>/locked.gif" alt="Locked" class="vm" /> 提示: 作者被禁止或删除 内容自动屏蔽，只有管理员可见</p>
<?php } ?>
<div id="hd" class="wp cl">

<h2 id="spaceinfoshow"><?php space_merge($space, 'field_home'); $space[domainurl] = space_domain($space);getuserdiydata($space);$personalnv = isset($_G['blockposition']['nv']) ? $_G['blockposition']['nv'] : '';?><strong id="spacename" class="mbn">
<?php if($space['spacename']) { ?><?php echo $space['spacename'];?><?php } else { ?><?php echo $space['username'];?>的个人空间<?php } ?>
</strong>
<span class="xs0 xw0">
<a id="domainurl" href="<?php echo $space['domainurl'];?>" onclick="setCopy('<?php echo $space['domainurl'];?>', '空间地址复制成功');return false;"><?php echo $space['domainurl'];?></a>
<a href="javascript:;" onclick="addFavorite(location.href, document.title)">[收藏]</a>
<a id="domainurl" href="<?php echo $space['domainurl'];?>" onclick="setCopy('<?php echo $space['domainurl'];?>', '空间地址复制成功');return false;">[复制]</a>
<?php if(!$space['self']) { if(helper_access::check_module('share')) { ?>
<a id="share_space" href="home.php?mod=spacecp&amp;ac=share&amp;type=space&amp;id=<?php echo $space['uid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);">[分享]</a>
<?php } ?>
<a href="home.php?mod=rss&amp;uid=<?php echo $space['uid'];?>">[RSS]</a>
<?php } ?>
</span>
<span id="spacedescription" class="xs1 xw0 mtn"><?php echo $space['spacedescription'];?></span>
</h2><?php if($_G['adminid'] == 1 && empty($space['self'])) { $personalnv['items'] = array(); $personalnv['banitems'] = array(); $personalnv['nvhidden'] = 0;?><?php } $nvclass = !empty($personalnv['nvhidden']) ? ' class="mininv"' : '';?><div id="nv">
<ul<?php echo $nvclass;?>>
<?php if(empty($personalnv['nvhidden'])) { if(empty($personalnv['banitems']['index'])) { if($_G['adminid'] == 1 && $_G['setting']['allowquickviewprofile'] == 1) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=index&amp;view=admin"><?php if(!empty($personalnv['items']['index'])) { ?><?php echo $personalnv['items']['index'];?><?php } else { ?>空间首页<?php } ?></a></li>
<?php } else { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=index"><?php if(!empty($personalnv['items']['index'])) { ?><?php echo $personalnv['items']['index'];?><?php } else { ?>空间首页<?php } ?></a></li>
<?php } } if(empty($personalnv['banitems']['feed']) && helper_access::check_module('feed')) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=home&amp;view=me&amp;from=space"><?php if(!empty($personalnv['items']['feed'])) { ?><?php echo $personalnv['items']['feed'];?><?php } else { ?>动态<?php } ?></a></li>
<?php } if(empty($personalnv['banitems']['doing']) && helper_access::check_module('doing')) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=doing&amp;view=me&amp;from=space"><?php if(!empty($personalnv['items']['doing'])) { ?><?php echo $personalnv['items']['doing'];?><?php } else { ?>记录<?php } ?></a></li>
<?php } if(empty($personalnv['banitems']['blog']) && helper_access::check_module('blog')) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=blog&amp;view=me&amp;from=space"><?php if(!empty($personalnv['items']['blog'])) { ?><?php echo $personalnv['items']['blog'];?><?php } else { ?>日志<?php } ?></a></li>
<?php } if(empty($personalnv['banitems']['album']) && helper_access::check_module('album')) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=album&amp;view=me&amp;from=space"><?php if(!empty($personalnv['items']['album'])) { ?><?php echo $personalnv['items']['album'];?><?php } else { ?>相册<?php } ?></a></li>
<?php } if(empty($personalnv['banitems']['follow']) && helper_access::check_module('follow')) { ?>
<li><a href="home.php?mod=follow&amp;uid=<?php echo $space['uid'];?>&amp;do=view"><?php if(!empty($personalnv['items']['follow'])) { ?><?php echo $personalnv['items']['follow'];?><?php } else { ?>广播<?php } ?></a></li>
<?php } if($_G['setting']['allowviewuserthread'] !== false && (empty($personalnv['banitems']['topic']))) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=thread&amp;view=me&amp;from=space"><?php if(!empty($personalnv['items']['topic'])) { ?><?php echo $personalnv['items']['topic'];?><?php } else { ?>主题<?php } ?></a></li>
<?php } if(empty($personalnv['banitems']['share']) && helper_access::check_module('share')) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=share&amp;view=me&amp;from=space"><?php if(!empty($personalnv['items']['share'])) { ?><?php echo $personalnv['items']['share'];?><?php } else { ?>分享<?php } ?></a></li>
<?php } if(empty($personalnv['banitems']['wall']) && helper_access::check_module('wall')) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=wall"><?php if(!empty($personalnv['items']['wall'])) { ?><?php echo $personalnv['items']['wall'];?><?php } else { ?>留言板<?php } ?></a></li>
<?php } if(empty($personalnv['banitems']['profile'])) { ?>
<li><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=profile"><?php if(!empty($personalnv['items']['profile'])) { ?><?php echo $personalnv['items']['profile'];?><?php } else { ?>个人资料<?php } ?></a></li>
<?php } } ?>
</ul>
</div></div>

<?php if(!empty($_G['setting']['plugins']['jsmenu'])) { ?>
<ul class="p_pop h_pop" id="plugin_menu" style="display: none"><?php if(is_array($_G['setting']['plugins']['jsmenu'])) foreach($_G['setting']['plugins']['jsmenu'] as $module) { ?>     <?php if(!$module['adminid'] || ($module['adminid'] && $_G['adminid'] > 0 && $module['adminid'] >= $_G['adminid'])) { ?>
     <li><?php echo $module['url'];?></li>
     <?php } } ?>
</ul>
<?php } ?>
<?php echo $_G['setting']['menunavs'];?><?php $mnid = getcurrentnav();?><ul id="navs_menu" class="p_pop topnav_pop" style="display:none;"><?php if(is_array($_G['setting']['navs'])) foreach($_G['setting']['navs'] as $nav) { $nav_showmenu = strpos($nav['nav'], 'onmouseover="showMenu(');?>    <?php $nav_navshow = strpos($nav['nav'], 'onmouseover="navShow(')?>    <?php if($nav_hidden !== false || $nav_navshow !== false) { $nav['nav'] = preg_replace("/onmouseover\=\"(.*?)\"/i", '',$nav['nav'])?>    <?php } ?>
    <?php if($nav['available'] && (!$nav['level'] || ($nav['level'] == 1 && $_G['uid']) || ($nav['level'] == 2 && $_G['adminid'] > 0) || ($nav['level'] == 3 && $_G['adminid'] == 1))) { ?><li <?php echo $nav['nav'];?>></li><?php } } ?>
</ul>
<ul id="myspace_menu" class="p_pop" style="display:none;">
    <li><a href="home.php?mod=space">我的空间</a></li><?php if(is_array($_G['setting']['mynavs'])) foreach($_G['setting']['mynavs'] as $nav) { if($nav['available'] && (!$nav['level'] || ($nav['level'] == 1 && $_G['uid']) || ($nav['level'] == 2 && $_G['adminid'] > 0) || ($nav['level'] == 3 && $_G['adminid'] == 1))) { ?>
<li><?php echo $nav['code'];?></li>
<?php } } ?>
</ul>
<div id="ct" class="ct2 wp cl">
<div class="mn">
<div class="bm">
<div class="bm_h">
<h1 class="mt">好友</h1>
</div>
<div class="bm_c">
<?php } else { include template('common/header'); ?><div id="pt" class="bm cl">
<div class="z">
<a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em>
<a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>"><?php echo $space['username'];?></a> <em>&rsaquo;</em>
<a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=friend&amp;view=me">好友</a>
</div>
</div>
<style id="diy_style" type="text/css"></style>
<div class="wp">
<!--[diy=diy1]--><div id="diy1" class="area"></div><!--[/diy]-->
</div><?php include template('home/space_menu'); ?><div id="ct" class="ct1 wp cl">
<div class="mn">
<!--[diy=diycontenttop]--><div id="diycontenttop" class="area"></div><!--[/diy]-->
<div class="bm bw0">
<div class="bm_c">
<?php } } if($space['self']) { if($groups) { ?>
<div class="cl" style="padding-right: 263px;">
<div style="float: right; margin-right: -263px; width: 263px; display: inline;">
<div class="bm mtm">
<div class="bm_h">
<h3>查找好友</h3>
</div>
<div class="bm_c">
<form method="get" autocomplete="off" action="home.php" class="pns">
<input type="hidden" name="mod" value="space" />
<input type="hidden" name="do" value="friend" />
<input type="hidden" name="searchmode" value="1" />
<input type="hidden" name="searchsubmit" value="1" />
<input type="text" name="searchkey" class="px vm" size="20" value="" onclick="if(this.value=='查找好友')this.value='';" />&nbsp;<button type="submit" class="pn vm"><em>搜索</em></button>
</form>
</div>
</div>

<?php if($_G['setting']['my_app_status']) { ?>
<script type="text/javascript">
function my_sync_tip(msg, close_time) {;
showDialog(msg, 'notice');
}
function my_sync_friend() {
my_sync_tip('正在同步好友信息...', 0);
var my_scri = document.createElement("script");
document.getElementsByTagName("head")[0].appendChild(my_scri);
my_scri.charset = "UTF-8";
var url = "http://uchome.manyou.com/user/syncFriends";
my_scri.src = url + "?sId=<?php echo $_G['setting']['my_siteid'];?>&uUchId=<?php echo $space['uid'];?>&ts=<?php echo $_G['timestamp'];?>&key=<?php echo md5($_G['setting'][my_siteid] . $_G['setting'][my_sitekey] . $space[uid] . $_G[timestamp]);?>";
}
</script>
<div class="bm mtm">
<div class="bm_h cl">
<h3>在游戏中找不到自己的好友？</h3>
</div>
<div class="bm_c">
<p>请点击下面的的按钮，将好友信息同步到里面 </p>
<p class="pns mtm hm"><button type="button" onclick="my_sync_friend(); return false;" title="将好友关系同步至Manyou平台，以便在应用里看到他们" class="pn"><em>刷新好友信息</em></button></p>
</div>
</div>
<?php } ?>

<div class="bm mtm">
<div class="bm_h cl">
<a href="home.php?mod=spacecp&amp;ac=friend&amp;op=group" class="y xi2">批量分组</a>
<h3>好友分组</h3>
</div>
<div class="bm_c">
<ul class="buddy_group">
<li<?php if($_GET['group'] == -1) { ?> class="a"<?php } ?>><a href="home.php?mod=space&amp;do=friend&amp;group=-1">全部好友</a></li><?php if(is_array($groups)) foreach($groups as $key => $value) { ?><li<?php echo $groupselect[$key];?>>
<a href="home.php?mod=spacecp&amp;ac=friend&amp;op=groupignore&amp;group=<?php echo $key;?>&amp;handlekey=ignorehk_<?php echo $key;?>" id="c_ignore_<?php echo $key;?>" onclick="showWindow('ignoregroup', this.href, 'get', 0);" title="屏蔽用户组动态" class="b"><?php if(isset($space['privacy']['filter_gid'][$key])) { ?>取消<?php } else { ?>屏蔽<?php } ?></a>
<?php if($key) { ?>
<a href="home.php?mod=spacecp&amp;ac=friend&amp;op=groupname&amp;group=<?php echo $key;?>&amp;handlekey=edithk_<?php echo $key;?>" id="c_edit_<?php echo $key;?>" onclick="showWindow(this.id, this.href, 'get', 0);" title="编辑用户组名" class="o">编辑</a>
<?php } ?>
<a href="home.php?mod=space&amp;do=friend&amp;group=<?php echo $key;?>" id="friend_groupname_<?php echo $key;?>" groupname="<?php echo $value;?>"><?php if(isset($space['privacy']['filter_gid'][$key])) { ?><span class="xg1">[屏蔽]</span><?php } ?><?php echo $value;?></a>
</li>
<?php } ?>
</ul>
</div>
</div>
<script type="text/javascript">
function succeedhandle_ignoregroup(url, msg, values) {
var liObj = $('friend_groupname_'+values['group']);
var prefix = '';
if(values['ignore']) {
prefix = '<span class="xg1">[屏蔽]</span>';
}
$('c_ignore_'+values['group']).innerHTML = values['ignore'] ? '取消' : '屏蔽';
liObj.innerHTML = prefix + liObj.getAttribute('groupname');
}
</script>
</div>
<div>
<?php } if($list) { ?>
<div id="friend_ul">
<ul class="buddy cl"><?php if(is_array($list)) foreach($list as $key => $value) { ?><li id="friend_<?php echo $value['uid'];?>_li">
<?php if($value['username'] == '') { ?>
<div class="avt"><img src="<?php echo STATICURL;?>image/magic/hidden.gif" alt="匿名" /></div>
<h4>匿名</h4>
<?php } else { ?>
<div class="avt">
<a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" c="1">
<?php if($ols[$value['uid']]) { ?><em class="gol" title="在线 <?php echo dgmdate($ols[$value[uid]], 'H:i');?>"></em><?php } ?><?php echo avatar($value[uid],small);?></a>
</div>
<h4>
<span class="xg1 xw0 y">
<?php if($_GET['view'] == 'blacklist') { ?>
<a href="home.php?mod=spacecp&amp;ac=friend&amp;op=blacklist&amp;subop=delete&amp;uid=<?php echo $value['uid'];?>&amp;start=<?php echo $_GET['start'];?>">黑名单除名</a>
<?php } elseif($_GET['view'] == 'visitor' || $_GET['view'] == 'trace') { ?><?php echo dgmdate($value[dateline], 'n月j日');?><?php } elseif($_GET['view'] == 'online') { ?><?php echo dgmdate($ols[$value[uid]], 'H:i');?><?php } else { ?>
<a href="home.php?mod=spacecp&amp;ac=friend&amp;op=changenum&amp;uid=<?php echo $value['uid'];?>&amp;handlekey=hotuserhk_<?php echo $value['uid'];?>" id="friendnum_<?php echo $value['uid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);" title="热度">热度(<span id="spannum_<?php echo $value['uid'];?>"><?php echo $value['num'];?></span>)</a>
<?php } ?>
</span>
<a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>"<?php g_color($value[groupid]);?>><?php echo $value['username'];?></a><?php g_icon($value[groupid]);?><?php if($value['videostatus']) { ?>
<img src="<?php echo IMGDIR;?>/videophoto.gif" alt="videophoto" class="vm" />
<?php } if($space['self']) { ?>
<span id="friend_note_<?php echo $value['uid'];?>" class="note xw0" title="<?php echo $value['note'];?>"><?php echo $value['note'];?></span>
<?php } ?>
</h4>
<p class="maxh"><?php echo $value['recentnote'];?></p>
<?php } ?>
<div class="xg1">
<?php if(isset($value['follow']) && $key != $_G['uid'] && $value['username'] != '') { ?>
<a href="home.php?mod=spacecp&amp;ac=follow&amp;op=<?php if($value['follow']) { ?>del<?php } else { ?>add<?php } ?>&amp;fuid=<?php echo $value['uid'];?>&amp;hash=<?php echo FORMHASH;?>&amp;from=a_followmod_" id="a_followmod_<?php echo $key;?>" onclick="showWindow('followmod', this.href, 'get', 0)"><?php if($value['follow']) { ?>取消收听<?php } else { ?>收听TA<?php } ?></a>
<?php } if($value['uid'] != $_G['uid'] && $value['username'] != '') { ?>
<span class="pipe">|</span><a href="javascript:;" id="interaction_<?php echo $value['uid'];?>" onmouseover="showMenu(this.id);" class="showmenu">互动</a>
<?php } if(!$value['isfriend'] && $value['username'] != '') { ?>
<span class="pipe">|</span><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $value['uid'];?>&amp;handlekey=adduserhk_<?php echo $value['uid'];?>" id="a_friend_<?php echo $key;?>" onclick="showWindow(this.id, this.href, 'get', 0);" title="加为好友">加为好友</a>
<?php } elseif(!in_array($_GET['view'], array('blacklist', 'visitor', 'trace', 'online'))) { ?>
<span class="pipe">|</span><a href="javascript:;" id="opfrd_<?php echo $value['uid'];?>" onmouseover="showMenu(this.id);" class="showmenu">管理</a>
<?php } ?>
</div>
<?php if($value['isfriend'] && !in_array($_GET['view'], array('blacklist', 'visitor', 'trace', 'online'))) { ?>
<div id="opfrd_<?php echo $value['uid'];?>_menu" class="p_pop" style="display: none; width: 80px;">
<p><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=changegroup&amp;uid=<?php echo $value['uid'];?>&amp;handlekey=editgrouphk_<?php echo $value['uid'];?>" id="friend_group_<?php echo $value['uid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);" title="好友分组">好友分组</a></p>
<p><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=editnote&amp;uid=<?php echo $value['uid'];?>&amp;handlekey=editnote_<?php echo $value['uid'];?>" id="friend_editnote_<?php echo $value['uid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);" title="备注">备注</a></p>
<p><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=ignore&amp;uid=<?php echo $value['uid'];?>&amp;handlekey=delfriendhk_<?php echo $value['uid'];?>" id="a_ignore_<?php echo $key;?>" onclick="showWindow(this.id, this.href, 'get', 0);" title="删除">删除</a></p>
</div>
<?php } if($value['uid'] != $_G['uid'] && $value['username'] != '') { ?>
<div id="interaction_<?php echo $value['uid'];?>_menu" class="p_pop" style="display: none; width: 80px;">
<p><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>&amp;do=profile" target="_blank" title="查看资料">查看资料</a></p>
<p><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" target="_blank" title="去串个门">去串个门</a></p>
<p><a href="home.php?mod=spacecp&amp;ac=poke&amp;op=send&amp;uid=<?php echo $value['uid'];?>" id="a_poke_<?php echo $key;?>" onclick="showWindow(this.id, this.href, 'get', 0);" title="打个招呼">打个招呼</a></p>
<p><a href="home.php?mod=spacecp&amp;ac=pm&amp;op=showmsg&amp;handlekey=showmsg_<?php echo $value['uid'];?>&amp;touid=<?php echo $value['uid'];?>&amp;pmid=0&amp;daterange=2" id="a_sendpm_<?php echo $key;?>" onclick="showWindow('showMsgBox', this.href, 'get', 0)" title="发送消息">发送消息</a></p>
<?php if(!empty($_G['setting']['pluginhooks']['space_interaction_extra'])) echo $_G['setting']['pluginhooks']['space_interaction_extra'];?>
</div>
<?php } ?>
</li>
<?php } ?>
</ul>
</div>
<?php if($multi) { ?><div class="pgs cl mtm"><?php echo $multi;?></div><?php } } else { ?>
                	<?php if($_GET['view'] == 'me' && !$friendnum) { ?>
                    	<?php if($specialuser_list) { ?>
                            <div id="friend_ul">
                            	<h2 class="mtw">推荐好友</h2>
                                <ul class="buddy cl">
                                <?php if(is_array($specialuser_list)) foreach($specialuser_list as $key => $value) { ?>                                    	<li id="friend_<?php echo $value['uid'];?>_li">
                                        	<div class="avt">
                                                <a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" c="1">
                                                    <?php if($ols[$value['uid']]) { ?><em class="gol" title="在线 <?php echo dgmdate($ols[$value[uid]], 'H:i');?>"></em><?php } ?>
                                                    <?php echo avatar($value[uid],small);?>                                                </a>
                                            </div>
                                            <h4>
                                                <a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>"><?php echo $value['username'];?></a>
                                            </h4>
                                            <p class="maxh"><?php echo $value['reason'];?></p>
                                            <div class="xg1">
                                            <?php if(isset($value['follow']) && $key != $_G['uid'] && $value['username'] != '') { ?>
<a href="home.php?mod=spacecp&amp;ac=follow&amp;op=<?php if($value['follow']) { ?>del<?php } else { ?>add<?php } ?>&amp;fuid=<?php echo $value['uid'];?>&amp;hash=<?php echo FORMHASH;?>&amp;from=a_specialuser_" id="a_specialuser_<?php echo $key;?>" onclick="showWindow('followmod', this.href, 'get', 0)"><?php if($value['follow']) { ?>取消收听<?php } else { ?>收听TA<?php } ?></a>
<span class="pipe">|</span>
<?php } ?>
<a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $value['uid'];?>&amp;handlekey=adduserhk_<?php echo $value['uid'];?>" id="a_friend_<?php echo $key;?>" onclick="showWindow(this.id, this.href, 'get', 0);" title="加为好友">加为好友</a></div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                        <?php if($online_list) { ?>
                        	<div id="friend_ul">
                            	<h2 class="mtw">在线成员</h2>
                                <ul class="buddy cl">
                                <?php if(is_array($online_list)) foreach($online_list as $key => $value) { ?>                                    	<li id="friend_<?php echo $value['uid'];?>_li">
                                        	<div class="avt">
                                                <a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" c="1">
                                                    <?php if($ols[$value['uid']]) { ?><em class="gol" title="在线 <?php echo dgmdate($ols[$value[uid]], 'H:i');?>"></em><?php } ?>
                                                    <?php echo avatar($value[uid],small);?>                                                </a>
                                            </div>
                                            <h4>
                                                <a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>"><?php echo $value['username'];?></a>
                                            </h4>
                                            <p class="maxh"><?php echo $value['recentnote'];?></p>
                                            <div class="xg1">
                                            <?php if(isset($value['follow']) && $key != $_G['uid'] && $value['username'] != '' && helper_access::check_module('follow')) { ?>
<a href="home.php?mod=spacecp&amp;ac=follow&amp;op=<?php if($value['follow']) { ?>del<?php } else { ?>add<?php } ?>&amp;fuid=<?php echo $value['uid'];?>&amp;hash=<?php echo FORMHASH;?>&amp;from=a_online_" id="a_online_<?php echo $key;?>" onclick="showWindow('followmod', this.href, 'get', 0)"><?php if($value['follow']) { ?>取消收听<?php } else { ?>收听TA<?php } ?></a>
<span class="pipe">|</span>
<?php } ?>
<a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $value['uid'];?>&amp;handlekey=adduserhk_<?php echo $value['uid'];?>" id="a_friend_<?php echo $key;?>" onclick="showWindow(this.id, this.href, 'get', 0);" title="加为好友">加为好友</a></div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
<div class="emp">没有相关用户列表</div>
                    <?php } } ?>
<script type="text/javascript">
function succeedhandle_followmod(url, msg, values) {
var fObj = $(values['from']+values['fuid']);
if(values['type'] == 'add') {
fObj.innerHTML = '取消收听';
fObj.className = 'flw_btn_unfo';
fObj.href = 'home.php?mod=spacecp&ac=follow&op=del&fuid='+values['fuid']+'&from='+values['from'];
} else if(values['type'] == 'del') {
fObj.innerHTML = '收听TA';
fObj.className = 'flw_btn_fo';
fObj.href = 'home.php?mod=spacecp&ac=follow&op=add&hash=<?php echo FORMHASH;?>&fuid='+values['fuid']+'&from='+values['from'];
}
}
</script>
<?php if($groups) { ?>
</div>
</div>
<?php } } else { ?>
<p class="tbmu">当前共有 <span class="xw1"><?php echo $count;?></span> 个好友</p><?php include template('home/space_list'); } if(empty($diymode)) { ?>
</div>
<!--[diy=diycontentbottom]--><div id="diycontentbottom" class="area"></div><!--[/diy]-->
</div>

<div class="appl"><div class="tbn">
<h2 class="mt bbda">好友</h2>
<ul>
<li<?php echo $actives['me'];?>><a href="home.php?mod=space&amp;do=friend">好友列表</a></li>
<li<?php echo $actives['search'];?>><a href="home.php?mod=spacecp&amp;ac=search">查找好友</a></li>
<li<?php echo $actives['find'];?>><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=find">可能认识的人</a></li>
<?php if($_G['setting']['regstatus'] > 1) { ?>
<li<?php echo $actives['invite'];?>><a href="home.php?mod=spacecp&amp;ac=invite">邀请好友</a></li>
<?php } ?>
<li<?php echo $actives['request'];?>><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=request">好友请求</a></li>	
<li<?php echo $actives['group'];?>><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=group">好友分组</a></li>
</ul>
</div><div class="drag">
<!--[diy=diy2]--><div id="diy2" class="area"></div><!--[/diy]-->
</div>
</div>
</div>

<div class="wp mtn">
<!--[diy=diy3]--><div id="diy3" class="area"></div><!--[/diy]-->
</div>
<?php } else { ?>
</div>
</div>
</div><?php if($_G['setting']['homepagestyle']) { ?>
<div class="sd"><div id="pcd" class="bm cl"><?php $encodeusername = rawurlencode($space[username]);?><div class="bm_c">
<div class="hm">
<p><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>" class="avtm"><?php echo avatar($space[uid],middle);?></a></p>
<h2 class="xs2"><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>"><?php echo $space['username'];?></a></h2>
</div>
<ul class="xl xl2 cl ul_list">
<?php if($space['self']) { if($_G['setting']['homepagestyle']) { ?>
<li class="ul_diy"><a href="home.php?mod=space&amp;do=index&amp;diy=yes">装扮空间</a></li>
<?php } if(helper_access::check_module('wall')) { ?>
<li class="ul_msg"><a href="home.php?mod=space&amp;do=wall">查看留言</a></li>
<?php } ?>
<li class="ul_avt"><a href="home.php?mod=spacecp&amp;ac=avatar">编辑头像</a></li>
<li class="ul_profile"><a href="home.php?mod=spacecp&amp;ac=profile">更新资料</a></li>
<?php } else { if(helper_access::check_module('follow')) { ?>
<li class="ul_broadcast"><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>">查看广播</a></li>
<?php } if(helper_access::check_module('follow') && $space['uid'] != $_G['uid']) { ?>
<li class="ul_flw"><?php $follow = 0;?><?php $follow = C::t('home_follow')->fetch_all_by_uid_followuid($_G['uid'], $space['uid']);?><?php if(!$follow) { ?>
<a id="followmod" onclick="showWindow(this.id, this.href, 'get', 0);" href="home.php?mod=spacecp&amp;ac=follow&amp;op=add&amp;hash=<?php echo FORMHASH;?>&amp;fuid=<?php echo $space['uid'];?>">收听TA</a>
<?php } else { ?>
<a id="followmod" onclick="showWindow(this.id, this.href, 'get', 0);" href="home.php?mod=spacecp&amp;ac=follow&amp;op=del&amp;fuid=<?php echo $space['uid'];?>">取消收听</a>
<?php } ?>
</li>
<?php } require_once libfile('function/friend');$isfriend=friend_check($space[uid]);?><?php if(!$isfriend) { ?>
<li class="ul_add"><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $space['uid'];?>&amp;handlekey=addfriendhk_<?php echo $space['uid'];?>" id="a_friend_li_<?php echo $space['uid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);">加为好友</a></li>
<?php } else { ?>
<li class="ul_ignore"><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=ignore&amp;uid=<?php echo $space['uid'];?>&amp;handlekey=ignorefriendhk_<?php echo $space['uid'];?>" id="a_ignore_<?php echo $space['uid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);">解除好友</a></li>
<?php } if(helper_access::check_module('wall')) { ?>
<li class="ul_contect"><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=wall">给我留言</a></li>
<?php } ?>
<li class="ul_poke"><a href="home.php?mod=spacecp&amp;ac=poke&amp;op=send&amp;uid=<?php echo $space['uid'];?>&amp;handlekey=propokehk_<?php echo $space['uid'];?>" id="a_poke_<?php echo $space['uid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);">打个招呼</a></li>

<li class="ul_pm"><a href="home.php?mod=spacecp&amp;ac=pm&amp;op=showmsg&amp;handlekey=showmsg_<?php echo $space['uid'];?>&amp;touid=<?php echo $space['uid'];?>&amp;pmid=0&amp;daterange=2" id="a_sendpm_<?php echo $space['uid'];?>" onclick="showWindow('showMsgBox', this.href, 'get', 0)">发送消息</a></li>
<?php } ?>
</ul>
<?php if(checkperm('allowbanuser') || checkperm('allowedituser') || $_G['adminid'] == 1) { ?>
<hr class="da mtn m0">
<ul class="ptn xl xl2 cl">
<?php if(checkperm('allowbanuser') || checkperm('allowedituser')) { ?>
<li>
<?php if(checkperm('allowbanuser')) { ?>
<a href="<?php if($_G['adminid'] == 1) { ?>admin.php?action=members&operation=ban&username=<?php echo $encodeusername;?>&frames=yes<?php } else { ?>forum.php?mod=modcp&action=member&op=ban&uid=<?php echo $space['uid'];?><?php } ?>" id="usermanageli" onmouseover="showMenu(this.id)" class="showmenu" target="_blank">用户管理</a>
<?php } else { ?>
<a href="<?php if($_G['adminid'] == 1) { ?>admin.php?action=members&operation=search&username=<?php echo $encodeusername;?>&submit=yes&frames=yes<?php } else { ?>forum.php?mod=modcp&action=member&op=edit&uid=<?php echo $space['uid'];?><?php } ?>" id="usermanageli" onmouseover="showMenu(this.id)" class="showmenu" target="_blank">用户管理</a>
<?php } ?>
</li>
<?php } if($_G['adminid'] == 1) { ?>
<li><a href="forum.php?mod=modcp&amp;action=thread&amp;op=post&amp;do=search&amp;searchsubmit=1&amp;users=<?php echo $encodeusername;?>" id="umanageli" onmouseover="showMenu(this.id)" class="showmenu">内容管理</a></li>
<?php } ?>
</ul>
<?php if(checkperm('allowbanuser') || checkperm('allowedituser')) { ?>
<ul id="usermanageli_menu" class="p_pop" style="width: 80px; display:none;">
<?php if(checkperm('allowbanuser')) { ?>
<li><a href="<?php if($_G['adminid'] == 1) { ?>admin.php?action=members&operation=ban&username=<?php echo $encodeusername;?>&frames=yes<?php } else { ?>forum.php?mod=modcp&action=member&op=ban&uid=<?php echo $space['uid'];?><?php } ?>" target="_blank">禁止用户</a></li>
<?php } if(checkperm('allowedituser')) { ?>
<li><a href="<?php if($_G['adminid'] == 1) { ?>admin.php?action=members&operation=search&username=<?php echo $encodeusername;?>&submit=yes&frames=yes<?php } else { ?>forum.php?mod=modcp&action=member&op=edit&uid=<?php echo $space['uid'];?><?php } ?>" target="_blank">编辑用户</a></li>
<?php } ?>
</ul>
<?php } if($_G['adminid'] == 1) { ?>
<ul id="umanageli_menu" class="p_pop" style="width: 80px; display:none;">
<li><a href="forum.php?mod=modcp&amp;action=thread&amp;op=post&amp;searchsubmit=1&amp;do=search&amp;users=<?php echo $encodeusername;?>" target="_blank">管理帖子</a></li>
<?php if(helper_access::check_module('doing')) { ?>
<li><a href="admin.php?action=doing&amp;searchsubmit=1&amp;detail=1&amp;search=true&amp;fromumanage=1&amp;users=<?php echo $encodeusername;?>" target="_blank">管理记录</a></li>
<?php } if(helper_access::check_module('blog')) { ?>
<li><a href="admin.php?action=blog&amp;searchsubmit=1&amp;detail=1&amp;search=true&amp;fromumanage=1&amp;uid=<?php echo $space['uid'];?>" target="_blank">管理日志</a></li>
<?php } if(helper_access::check_module('feed')) { ?>
<li><a href="admin.php?action=feed&amp;searchsubmit=1&amp;detail=1&amp;fromumanage=1&amp;uid=<?php echo $space['uid'];?>" target="_blank">管理动态</a></li>
<?php } if(helper_access::check_module('album')) { ?>
<li><a href="admin.php?action=album&amp;searchsubmit=1&amp;detail=1&amp;search=true&amp;fromumanage=1&amp;uid=<?php echo $space['uid'];?>" target="_blank">管理相册</a></li>
<li><a href="admin.php?action=pic&amp;searchsubmit=1&amp;detail=1&amp;search=true&amp;fromumanage=1&amp;users=<?php echo $encodeusername;?>" target="_blank">管理图片</a></li>
<?php } if(helper_access::check_module('wall')) { ?>
<li><a href="admin.php?action=comment&amp;searchsubmit=1&amp;detail=1&amp;fromumanage=1&amp;authorid=<?php echo $space['uid'];?>" target="_blank">管理评论</a></li>
<?php } if(helper_access::check_module('share')) { ?>
<li><a href="admin.php?action=share&amp;searchsubmit=1&amp;detail=1&amp;search=true&amp;fromumanage=1&amp;uid=<?php echo $space['uid'];?>" target="_blank">管理分享</a></li>
<?php } if(helper_access::check_module('group')) { ?>
<li><a href="admin.php?action=threads&amp;operation=group&amp;searchsubmit=1&amp;detail=1&amp;search=true&amp;fromumanage=1&amp;users=<?php echo $encodeusername;?>" target="_blank">群组主题</a></li>
<li><a href="admin.php?action=prune&amp;searchsubmit=1&amp;detail=1&amp;operation=group&amp;fromumanage=1&amp;users=<?php echo $encodeusername;?>" target="_blank">群组帖子</a></li>
<?php } ?>
</ul>
<?php } } ?>
</div>
</div>
</div>
<script type="text/javascript">
function succeedhandle_followmod(url, msg, values) {
var fObj = $('followmod');
if(values['type'] == 'add') {
fObj.innerHTML = '取消收听';
fObj.href = 'home.php?mod=spacecp&ac=follow&op=del&fuid='+values['fuid'];
} else if(values['type'] == 'del') {
fObj.innerHTML = '收听TA';
fObj.href = 'home.php?mod=spacecp&ac=follow&op=add&hash=<?php echo FORMHASH;?>&fuid='+values['fuid'];
}
}
</script></div><?php } } include template('common/footer'); ?>