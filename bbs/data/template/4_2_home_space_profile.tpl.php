<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('space_profile');
0
|| checktplrefresh('./template/mahjong/home/space_profile.htm', './template/mahjong/home/space_header.htm', 1510752267, '2', './data/template/4_2_home_space_profile.tpl.php', './template/mahjong', 'home/space_profile')
|| checktplrefresh('./template/mahjong/home/space_profile.htm', './template/mahjong/home/space_profile_body.htm', 1510752267, '2', './data/template/4_2_home_space_profile.tpl.php', './template/mahjong', 'home/space_profile')
|| checktplrefresh('./template/mahjong/home/space_profile.htm', './template/mahjong/home/space_userabout.htm', 1510752267, '2', './data/template/4_2_home_space_profile.tpl.php', './template/mahjong', 'home/space_profile')
|| checktplrefresh('./template/mahjong/home/space_profile.htm', './template/mahjong/common/header_common.htm', 1510752267, '2', './data/template/4_2_home_space_profile.tpl.php', './template/mahjong', 'home/space_profile')
|| checktplrefresh('./template/mahjong/home/space_profile.htm', './template/mahjong/home/space_diy.htm', 1510752267, '2', './data/template/4_2_home_space_profile.tpl.php', './template/mahjong', 'home/space_profile')
|| checktplrefresh('./template/mahjong/home/space_profile.htm', './template/mahjong/home/space_header_personalnv.htm', 1510752267, '2', './data/template/4_2_home_space_profile.tpl.php', './template/mahjong', 'home/space_profile')
;?>
<?php if($_G['setting']['homepagestyle']) { $_G[cookie][extstyle] = false;?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<h1 class="mt">个人资料</h1>
</div>
<div class="bm_c">
<?php } else { include template('common/header'); ?><div id="pt" class="bm cl">
<div class="z">
<a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em>
<a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>"><?php echo $space['username'];?></a> <em>&rsaquo;</em>
个人资料
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
<?php } ?><div class="bm_c u_profile">

<div class="pbm mbm bbda cl">
<h2 class="mbn">
<?php echo $space['username'];?>
<?php if($_G['ols'][$space['uid']]) { ?>
<img src="<?php echo IMGDIR;?>/ol.gif" alt="online" title="在线" class="vm" />&nbsp;
<?php } ?>
<span class="xw0">(UID: <?php echo $space['uid'];?><?php $isfriendinfo = 'home_friend_info_'.$space['uid'].'_'.$_G[uid];?><?php if($_G[$isfriendinfo]['note']) { ?>
, <span class="xg1"><?php echo $_G[$isfriendinfo]['note'];?></span>
<?php } ?>
)</span>
</h2>
<?php if(CURMODULE == 'space') { ?>
<?php if(!empty($_G['setting']['pluginhooks']['space_profile_baseinfo_top'])) echo $_G['setting']['pluginhooks']['space_profile_baseinfo_top'];?>
<?php } elseif(CURMODULE == 'follow') { ?>
<?php if(!empty($_G['setting']['pluginhooks']['follow_profile_baseinfo_top'])) echo $_G['setting']['pluginhooks']['follow_profile_baseinfo_top'];?>
<?php } ?>
<ul class="pf_l cl pbm mbm">
<?php if($_G['setting']['allowspacedomain'] && $_G['setting']['domain']['root']['home'] && checkperm('domainlength') && !empty($space['domain'])) { $spaceurl = 'http://'.$space['domain'].'.'.$_G['setting']['domain']['root']['home'];?><li><em>二级域名</em><a href="<?php echo $spaceurl;?>" onclick="setCopy('<?php echo $spaceurl;?>', '空间地址复制成功');return false;"><?php echo $spaceurl;?></a></li>
<?php } if($_G['setting']['homepagestyle']) { ?>
<li><em>空间访问量</em><strong class="xi1"><?php echo $space['views'];?></strong></li>
<?php } if(in_array($_G['adminid'], array(1, 2))) { ?>
<li><em>Email</em><?php echo $space['email'];?></li>
<?php } ?>
<li><em>邮箱状态</em><?php if($space['emailstatus'] > 0) { ?>已验证<?php } else { ?>未验证<?php } ?></li>
<li><em>视频认证</em><?php if($space['videophotostatus'] > 0) { ?>已认证 <?php if($showvideophoto) { ?>&nbsp;&nbsp;(<a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=videophoto" id="viewphoto" onclick="showWindow(this.id, this.href, 'get', 0)">查看认证照片</a>)<?php } } else { ?>未认证<?php } ?></li>
</ul>
<ul>
<?php if($space['spacenote']) { ?><li><em class="xg1">最新记录&nbsp;&nbsp;</em><?php echo $space['spacenote'];?></li><?php } if($space['customstatus']) { ?><li class="xg1"><em>自定义头衔&nbsp;&nbsp;</em><?php echo $space['customstatus'];?></li><?php } if($space['group']['maxsigsize'] && $space['sightml']) { ?><li><em class="xg1">个人签名&nbsp;&nbsp;</em><table><tr><td><?php echo $space['sightml'];?></td></tr></table></li><?php } ?>
</ul>
<ul class="cl bbda pbm mbm">
<li>
<em class="xg2">统计信息</em>
<a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=friend&amp;view=me&amp;from=space" target="_blank">好友数 <?php echo $space['friends'];?></a>
<?php if(helper_access::check_module('doing')) { ?>
<span class="pipe">|</span>
<a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=doing&amp;view=me&amp;from=space" target="_blank">记录数 <?php echo $space['doings'];?></a>
<?php } if(helper_access::check_module('blog')) { ?>
<span class="pipe">|</span>
<a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=blog&amp;view=me&amp;from=space" target="_blank">日志数 <?php echo $space['blogs'];?></a>
<?php } if(helper_access::check_module('album')) { ?>
<span class="pipe">|</span>
<a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=album&amp;view=me&amp;from=space" target="_blank">相册数 <?php echo $space['albums'];?></a>
<?php } if($_G['setting']['allowviewuserthread'] !== false) { ?>
<span class="pipe">|</span><?php $space['posts'] = $space['posts'] - $space['threads'];?><a href="<?php if(CURMODULE != 'follow') { ?>home.php?mod=space&uid=<?php echo $space['uid'];?>&do=thread&view=me&type=reply&from=space<?php } else { ?>home.php?mod=space&uid=<?php echo $space['uid'];?>&view=thread&type=reply<?php } ?>" target="_blank">回帖数 <?php echo $space['posts'];?></a>
<span class="pipe">|</span>
<a href="<?php if(CURMODULE != 'follow') { ?>home.php?mod=space&uid=<?php echo $space['uid'];?>&do=thread&view=me&type=thread&from=space<?php } else { ?>home.php?mod=space&uid=<?php echo $space['uid'];?>&view=thread<?php } ?>" target="_blank">主题数 <?php echo $space['threads'];?></a>
<?php } if(helper_access::check_module('share')) { ?>
<span class="pipe">|</span>
<a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=share&amp;view=me&amp;from=space" target="_blank">分享数 <?php echo $space['sharings'];?></a>
<?php } ?>
</li>
</ul>
<?php if(CURMODULE == 'space') { ?>
<?php if(!empty($_G['setting']['pluginhooks']['space_profile_baseinfo_middle'])) echo $_G['setting']['pluginhooks']['space_profile_baseinfo_middle'];?>
<?php } elseif(CURMODULE == 'follow') { ?>
<?php if(!empty($_G['setting']['pluginhooks']['follow_profile_baseinfo_middle'])) echo $_G['setting']['pluginhooks']['follow_profile_baseinfo_middle'];?>
<?php } ?>
<ul class="pf_l cl"><?php if(is_array($profiles)) foreach($profiles as $value) { ?><li><em><?php echo $value['title'];?></em><?php echo $value['value'];?></li>
<?php } ?>
</ul>
</div>
<?php if(CURMODULE == 'space') { ?>
<?php if(!empty($_G['setting']['pluginhooks']['space_profile_baseinfo_bottom'])) echo $_G['setting']['pluginhooks']['space_profile_baseinfo_bottom'];?>
<?php } elseif(CURMODULE == 'follow') { ?>
<?php if(!empty($_G['setting']['pluginhooks']['follow_profile_baseinfo_bottom'])) echo $_G['setting']['pluginhooks']['follow_profile_baseinfo_bottom'];?>
<?php } if($space['medals']) { ?>
<div class="pbm mbm bbda cl">
<h2 class="mbn">勋章</h2>
<p class="md_ctrl">
<a href="home.php?mod=medal"><?php if(is_array($space['medals'])) foreach($space['medals'] as $medal) { ?><img src="<?php echo STATICURL;?>/image/common/<?php echo $medal['image'];?>" alt="<?php echo $medal['name'];?>" id="md_<?php echo $medal['medalid'];?>" onmouseover="showMenu({'ctrlid':this.id, 'menuid':'md_<?php echo $medal['medalid'];?>_menu', 'pos':'12!'});" />
<?php } ?>
</a>
</p>
</div><?php if(is_array($space['medals'])) foreach($space['medals'] as $medal) { ?><div id="md_<?php echo $medal['medalid'];?>_menu" class="tip tip_4" style="display: none;">
<div class="tip_horn"></div>
<div class="tip_c">
<h4><?php echo $medal['name'];?></h4>
<p><?php echo $medal['description'];?></p>
</div>
</div>
<?php } } if($_G['setting']['verify']['enabled']) { $showverify = true;?><?php if(is_array($_G['setting']['verify'])) foreach($_G['setting']['verify'] as $vid => $verify) { if($verify['available']) { if($showverify) { ?>
<div class="pbm mbm bbda cl">
<h2 class="mbn">用户认证</h2><?php $showverify = false;?><?php } if($space['verify'.$vid] == 1) { ?>
<a href="home.php?mod=spacecp&amp;ac=profile&amp;op=verify&amp;vid=<?php echo $vid;?>" target="_blank"><?php if($verify['icon']) { ?><img src="<?php echo $verify['icon'];?>" class="vm" alt="<?php echo $verify['title'];?>" title="<?php echo $verify['title'];?>" /><?php } else { ?><?php echo $verify['title'];?><?php } ?></a>&nbsp;
<?php } elseif(!empty($verify['unverifyicon'])) { ?>
<a href="home.php?mod=spacecp&amp;ac=profile&amp;op=verify&amp;vid=<?php echo $vid;?>" target="_blank"><?php if($verify['unverifyicon']) { ?><img src="<?php echo $verify['unverifyicon'];?>" class="vm" alt="<?php echo $verify['title'];?>" title="<?php echo $verify['title'];?>" /><?php } ?></a>&nbsp;
<?php } } } if(!$showverify) { ?></div><?php } } if($count) { ?>
<div class="pbm mbm bbda cl">
<h2 class="mbn">管理以下版块</h2><?php if(is_array($manage_forum)) foreach($manage_forum as $key => $value) { ?><a href="forum.php?mod=forumdisplay&amp;fid=<?php echo $key;?>" target="_blank"><?php echo $value;?></a> &nbsp;
<?php } ?>
</div>
<?php } if($groupcount) { ?>
<div class="pbm mbm bbda cl">
<h2 class="mbn">已加入群组</h2><?php if(is_array($usergrouplist)) foreach($usergrouplist as $key => $value) { ?><a href="forum.php?mod=group&amp;fid=<?php echo $value['fid'];?>" target="_blank"><?php echo $value['name'];?></a> &nbsp;
<?php } ?>
</div>
<?php } ?>
<div class="pbm mbm bbda cl">
<h2 class="mbn">活跃概况</h2>
<ul>
<?php if($space['adminid']) { ?><li><em class="xg1">管理组&nbsp;&nbsp;</em><span style="color:<?php echo $space['admingroup']['color'];?>"><a href="home.php?mod=spacecp&amp;ac=usergroup&amp;gid=<?php echo $space['adminid'];?>" target="_blank"><?php echo $space['admingroup']['grouptitle'];?></a></span> <?php echo $space['admingroup']['icon'];?></li><?php } ?>
<li><em class="xg1">用户组&nbsp;&nbsp;</em><span style="color:<?php echo $space['group']['color'];?>"<?php if($upgradecredit !== false) { ?> class="xi2" onmouseover="showTip(this)" tip="积分 <?php echo $space['credits'];?>, 距离下一级还需 <?php echo $upgradecredit;?> 积分"<?php } ?>><a href="home.php?mod=spacecp&amp;ac=usergroup&amp;gid=<?php echo $space['groupid'];?>" target="_blank"><?php echo $space['group']['grouptitle'];?></a></span> <?php echo $space['group']['icon'];?> <?php if(!empty($space['groupexpiry'])) { ?>&nbsp;有效期至&nbsp;<?php echo dgmdate($space[groupexpiry], 'Y-m-d H:i');?><?php } ?></li>
<?php if($space['extgroupids']) { ?><li><em class="xg1">扩展用户组&nbsp;&nbsp;</em><?php echo $space['extgroupids'];?></li><?php } ?>
</ul>
<ul id="pbbs" class="pf_l">
<?php if($space['oltime']) { ?><li><em>在线时间</em><?php echo $space['oltime'];?> 小时</li><?php } ?>
<li><em>注册时间</em><?php echo $space['regdate'];?></li>
<li><em>最后访问</em><?php echo $space['lastvisit'];?></li>
<?php if($_G['uid'] == $space['uid'] || $_G['group']['allowviewip']) { ?>
<li><em>注册 IP</em><?php echo $space['regip'];?> - <?php echo $space['regip_loc'];?></li>
<li><em>上次访问 IP</em><?php echo $space['lastip'];?>:<?php echo $space['port'];?> - <?php echo $space['lastip_loc'];?></li>
<?php } if($space['lastactivity']) { ?><li><em>上次活动时间</em><?php echo $space['lastactivity'];?></li><?php } if($space['lastpost']) { ?><li><em>上次发表时间</em><?php echo $space['lastpost'];?></li><?php } if($space['lastsendmail']) { ?><li><em>上次邮件通知</em><?php echo $space['lastsendmail'];?></li><?php } ?>
<li><em>所在时区</em><?php $timeoffset = array(
		'9999' => '使用系统默认',
		'-12' => '(GMT -12:00) 埃尼威托克岛, 夸贾林环礁',
		'-11' => '(GMT -11:00) 中途岛, 萨摩亚群岛',
		'-10' => '(GMT -10:00) 夏威夷',
		'-9' => '(GMT -09:00) 阿拉斯加',
		'-8' => '(GMT -08:00) 太平洋时间(美国和加拿大), 提华纳',
		'-7' => '(GMT -07:00) 山区时间(美国和加拿大), 亚利桑那',
		'-6' => '(GMT -06:00) 中部时间(美国和加拿大), 墨西哥城',
		'-5' => '(GMT -05:00) 东部时间(美国和加拿大), 波哥大, 利马, 基多',
		'-4' => '(GMT -04:00) 大西洋时间(加拿大), 加拉加斯, 拉巴斯',
		'-3.5' => '(GMT -03:30) 纽芬兰',
		'-3' => '(GMT -03:00) 巴西利亚, 布宜诺斯艾利斯, 乔治敦, 福克兰群岛',
		'-2' => '(GMT -02:00) 中大西洋, 阿森松群岛, 圣赫勒拿岛',
		'-1' => '(GMT -01:00) 亚速群岛, 佛得角群岛 [格林尼治标准时间] 都柏林, 伦敦, 里斯本, 卡萨布兰卡',
		'0' => '(GMT) 卡萨布兰卡，都柏林，爱丁堡，伦敦，里斯本，蒙罗维亚',
		'1' => '(GMT +01:00) 柏林, 布鲁塞尔, 哥本哈根, 马德里, 巴黎, 罗马',
		'2' => '(GMT +02:00) 赫尔辛基, 加里宁格勒, 南非, 华沙',
		'3' => '(GMT +03:00) 巴格达, 利雅得, 莫斯科, 奈洛比',
		'3.5' => '(GMT +03:30) 德黑兰',
		'4' => '(GMT +04:00) 阿布扎比, 巴库, 马斯喀特, 特比利斯',
		'4.5' => '(GMT +04:30) 坎布尔',
		'5' => '(GMT +05:00) 叶卡特琳堡, 伊斯兰堡, 卡拉奇, 塔什干',
		'5.5' => '(GMT +05:30) 孟买, 加尔各答, 马德拉斯, 新德里',
		'5.75' => '(GMT +05:45) 加德满都',
		'6' => '(GMT +06:00) 阿拉木图, 科伦坡, 达卡, 新西伯利亚',
		'6.5' => '(GMT +06:30) 仰光',
		'7' => '(GMT +07:00) 曼谷, 河内, 雅加达',
		'8' => '(GMT +08:00) 北京, 香港, 帕斯, 新加坡, 台北',
		'9' => '(GMT +09:00) 大阪, 札幌, 首尔, 东京, 雅库茨克',
		'9.5' => '(GMT +09:30) 阿德莱德, 达尔文',
		'10' => '(GMT +10:00) 堪培拉, 关岛, 墨尔本, 悉尼, 海参崴',
		'11' => '(GMT +11:00) 马加丹, 新喀里多尼亚, 所罗门群岛',
		'12' => '(GMT +12:00) 奥克兰, 惠灵顿, 斐济, 马绍尔群岛');?><?php echo $timeoffset[$space['timeoffset']];?>
</li>
</ul>
</div>
<div id="psts" class="<?php if($clist) { ?>pbm mbm bbda <?php } ?>cl">
<h2 class="mbn">统计信息</h2>
<ul class="pf_l">
<li><em>已用空间</em><?php echo $space['attachsize'];?></li>
<?php if($space['buyercredit']) { ?>
<li><em>卖家信用</em><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=trade&amp;view=eccredit#sellcredit" target="_blank"><?php echo $space['buyercredit'];?> <img src="<?php echo STATICURL;?>image/traderank/buyer/<?php echo $space['buyerrank'];?>.gif" border="0" class="vm" /></a></li>
<?php } if($space['sellercredit']) { ?>
<li><em>买家信用</em><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=trade&amp;view=eccredit#buyercredit" target="_blank"><?php echo $space['sellercredit'];?> <img src="<?php echo STATICURL;?>image/traderank/seller/<?php echo $space['sellerrank'];?>.gif" border="0" class="vm" /></a></li>
<?php } ?>
<li><em>积分</em><?php echo $space['credits'];?></li><?php if(is_array($_G['setting']['extcredits'])) foreach($_G['setting']['extcredits'] as $key => $value) { if($value['title']) { ?>
<li><em><?php echo $value['title'];?></em><?php echo $space["extcredits$key"];?> <?php echo $value['unit'];?></li>
<?php } } ?>
</ul>
</div>
<?php if($clist) { ?>
<div class="cl">
<h2 class="mbm">违规记录</h2>
<table id="pcr" class="dt">
<tr>
<th width="15%">操作行为</th>
<th width="15%">操作时间</th>
<th>操作理由</th>
<th width="15%">操作者</th>
</tr><?php if(is_array($clist)) foreach($clist as $crime) { ?><tr>
<td>
<?php if($crime['action'] == 'crime_delpost') { ?>
删除帖子
<?php } elseif($crime['action'] == 'crime_warnpost') { ?>
警告帖子
<?php } elseif($crime['action'] == 'crime_banpost') { ?>
屏蔽帖子
<?php } elseif($crime['action'] == 'crime_banspeak') { ?>
禁止发言
<?php } elseif($crime['action'] == 'crime_banvisit') { ?>
禁止访问
<?php } elseif($crime['action'] == 'crime_banstatus') { ?>
锁定用户
<?php } elseif($crime['action'] == 'crime_avatar') { ?>
清除头像
<?php } elseif($crime['action'] == 'crime_sightml') { ?>
清除签名
<?php } elseif($crime['action'] == 'crime_customstatus') { ?>
清除自定义头衔
<?php } ?>
</td>
<td><?php echo dgmdate($crime[dateline]);?></td>
<td><?php echo $crime['reason'];?></td>
<td><a href="home.php?mod=space&amp;uid=<?php echo $crime['operatorid'];?>" target="_blank"><?php echo $crime['operator'];?></a></td>
</tr>
<?php } ?>
</table>
</div>
<?php } if(CURMODULE == 'space') { ?>
<?php if(!empty($_G['setting']['pluginhooks']['space_profile_extrainfo'])) echo $_G['setting']['pluginhooks']['space_profile_extrainfo'];?>
<?php } elseif(CURMODULE == 'follow') { ?>
<?php if(!empty($_G['setting']['pluginhooks']['follow_profile_extrainfo'])) echo $_G['setting']['pluginhooks']['follow_profile_extrainfo'];?>
<?php } ?>
</div><?php if(!$_G['setting']['homepagestyle']) { ?><!--[diy=diycontentbottom]--><div id="diycontentbottom" class="area"></div><!--[/diy]--><?php } ?>
</div>
</div>
<?php if($_G['setting']['homepagestyle']) { ?>
</div>
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
</script><?php } ?>
</div>
</div>

<?php if(!$_G['setting']['homepagestyle']) { ?>
<div class="wp mtn">
<!--[diy=diy3]--><div id="diy3" class="area"></div><!--[/diy]-->
</div>
<?php } include template('common/footer'); ?>