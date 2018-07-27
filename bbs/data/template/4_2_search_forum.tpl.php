<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('forum');
0
|| checktplrefresh('./template/mahjong/search/forum.htm', './template/mahjong/search/header.htm', 1510752468, '2', './data/template/4_2_search_forum.tpl.php', './template/mahjong', 'search/forum')
|| checktplrefresh('./template/mahjong/search/forum.htm', './template/mahjong/search/pubsearch.htm', 1510752468, '2', './data/template/4_2_search_forum.tpl.php', './template/mahjong', 'search/forum')
|| checktplrefresh('./template/mahjong/search/forum.htm', './template/mahjong/search/thread_list.htm', 1510752468, '2', './data/template/4_2_search_forum.tpl.php', './template/mahjong', 'search/forum')
|| checktplrefresh('./template/mahjong/search/forum.htm', './template/mahjong/search/footer.htm', 1510752468, '2', './data/template/4_2_search_forum.tpl.php', './template/mahjong', 'search/forum')
|| checktplrefresh('./template/mahjong/search/forum.htm', './template/mahjong/common/header_common.htm', 1510752468, '2', './data/template/4_2_search_forum.tpl.php', './template/mahjong', 'search/forum')
;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<base href="<?php echo $_G['siteurl'];?>" /><link rel="stylesheet" type="text/css" href="data/cache/style_<?php echo STYLEID;?>_common.css?<?php echo VERHASH;?>" /><link rel="stylesheet" type="text/css" href="data/cache/style_<?php echo STYLEID;?>_search_forum.css?<?php echo VERHASH;?>" /><?php if($_G['uid'] && isset($_G['cookie']['extstyle']) && strpos($_G['cookie']['extstyle'], TPLDIR) !== false) { ?><link rel="stylesheet" id="css_extstyle" type="text/css" href="<?php echo $_G['cookie']['extstyle'];?>/style.css" /><?php } elseif($_G['style']['defaultextstyle']) { ?><link rel="stylesheet" id="css_extstyle" type="text/css" href="<?php echo $_G['style']['defaultextstyle'];?>/style.css" /><?php } ?><script type="text/javascript">var STYLEID = '<?php echo STYLEID;?>', STATICURL = '<?php echo STATICURL;?>', IMGDIR = '<?php echo IMGDIR;?>', VERHASH = '<?php echo VERHASH;?>', charset = '<?php echo CHARSET;?>', discuz_uid = '<?php echo $_G['uid'];?>', cookiepre = '<?php echo $_G['config']['cookie']['cookiepre'];?>', cookiedomain = '<?php echo $_G['config']['cookie']['cookiedomain'];?>', cookiepath = '<?php echo $_G['config']['cookie']['cookiepath'];?>', showusercard = '<?php echo $_G['setting']['showusercard'];?>', attackevasive = '<?php echo $_G['config']['security']['attackevasive'];?>', disallowfloat = '<?php echo $_G['setting']['disallowfloat'];?>', creditnotice = '<?php if($_G['setting']['creditnotice']) { ?><?php echo $_G['setting']['creditnames'];?><?php } ?>', defaultstyle = '<?php echo $_G['style']['defaultextstyle'];?>', REPORTURL = '<?php echo $_G['currenturl_encode'];?>', SITEURL = '<?php echo $_G['siteurl'];?>', JSPATH = '<?php echo $_G['setting']['jspath'];?>', DYNAMICURL = '<?php echo $_G['dynamicurl'];?>';</script>
<script src="<?php echo $_G['setting']['jspath'];?>common.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<?php if(empty($_GET['diy'])) { $_GET['diy'] = '';?><?php } if(!isset($topic)) { $topic = array();?><?php } ?>
</head>

<body id="nv_search" onkeydown="if(event.keyCode==27) return false;">
<div id="append_parent"></div><div id="ajaxwaitid"></div>
<div id="toptb" class="cl">
<div class="z">
<a href="./" id="navs" class="showmenu xi2" onmouseover="showMenu(this.id)">返回首页</a>
</div>
<div class="y">
<?php if($_G['uid']) { ?>
<strong><a href="home.php?mod=space" target="_blank" title="访问我的空间"><?php echo $_G['member']['username'];?></a></strong>
<a href="javascript:;" id="myspace" class="showmenu xi2" onmouseover="showMenu(this.id);">快捷导航</a>
<?php if(!empty($_G['setting']['pluginhooks']['global_usernav_extra1'])) echo $_G['setting']['pluginhooks']['global_usernav_extra1'];?>
<a href="home.php?mod=spacecp">设置</a>
<?php if($_G['uid'] && ($_G['group']['radminid'] == 1 || getstatus($_G['member']['allowadmincp'], 1))) { ?><a href="admin.php" target="_blank">管理中心</a><?php } ?>
<?php if(!empty($_G['setting']['pluginhooks']['global_usernav_extra2'])) echo $_G['setting']['pluginhooks']['global_usernav_extra2'];?>
<a href="member.php?mod=logging&amp;action=logout&amp;formhash=<?php echo FORMHASH;?>">退出</a>
<?php } elseif(!empty($_G['cookie']['loginuser'])) { ?>
<strong><a id="loginuser"><?php echo $_G['cookie']['loginuser'];?></a></strong>
<a href="member.php?mod=logging&amp;action=login" onclick="showWindow('login', this.href)">激活</a>
<a href="member.php?mod=logging&amp;action=logout&amp;formhash=<?php echo FORMHASH;?>">退出</a>
<?php } else { ?>
<a href="member.php?mod=<?php echo $_G['setting']['regname'];?>"><?php echo $_G['setting']['reglinkname'];?></a>
<a href="member.php?mod=logging&amp;action=login" onclick="showWindow('login', this.href)">登录</a>
<?php } ?>
</div>
</div>
<?php if(!empty($_G['setting']['plugins']['jsmenu'])) { ?>
<ul class="p_pop h_pop" id="plugin_menu" style="display: none"><?php if(is_array($_G['setting']['plugins']['jsmenu'])) foreach($_G['setting']['plugins']['jsmenu'] as $module) { ?>     <?php if(!$module['adminid'] || ($module['adminid'] && $_G['adminid'] > 0 && $module['adminid'] >= $_G['adminid'])) { ?>
     <li><?php echo $module['url'];?></li>
     <?php } } ?>
</ul>
<?php } ?>
<?php echo $_G['setting']['menunavs'];?>

<?php if($_G['setting']['navs']) { ?>
<ul class="p_pop h_pop" id="navs_menu" style="display: none"><?php if(is_array($_G['setting']['navs'])) foreach($_G['setting']['navs'] as $nav) { $nav_showmenu = strpos($nav['nav'], 'onmouseover="showMenu(');?>    <?php $nav_navshow = strpos($nav['nav'], 'onmouseover="navShow(')?>    <?php if($nav_hidden !== false || $nav_navshow !== false) { $nav['nav'] = preg_replace("/onmouseover\=\"(.*?)\"/i", '',$nav['nav'])?>    <?php } if($nav['available'] && (!$nav['level'] || ($nav['level'] == 1 && $_G['uid']) || ($nav['level'] == 2 && $_G['adminid'] > 0) || ($nav['level'] == 3 && $_G['adminid'] == 1))) { ?><li <?php echo $nav['nav'];?>></li><?php } } ?>
</ul>
<?php } ?>

<ul id="myspace_menu" class="p_pop" style="display:none;"><?php if(is_array($_G['setting']['mynavs'])) foreach($_G['setting']['mynavs'] as $nav) { if($nav['available'] && (!$nav['level'] || ($nav['level'] == 1 && $_G['uid']) || ($nav['level'] == 2 && $_G['adminid'] > 0) || ($nav['level'] == 3 && $_G['adminid'] == 1))) { ?>
<li><?php echo $nav['code'];?></li>
<?php } } ?>
</ul><div id="ct" class="cl w">
<div class="mw">
<form class="searchform" method="post" autocomplete="off" action="search.php?mod=forum" onsubmit="if($('scform_srchtxt')) searchFocus($('scform_srchtxt'));">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" /><?php $keywordenc = $keyword ? rawurlencode($keyword) : '';?><?php if($searchid || ($_GET['adv'] && CURMODULE == 'forum')) { ?>
<table id="scform" class="mbm" cellspacing="0" cellpadding="0">
<tr>
<td><h1><a href="search.php" title="<?php echo $_G['setting']['bbname'];?>"><img src="<?php echo IMGDIR;?>/logo_sc_s.png" alt="<?php echo $_G['setting']['bbname'];?>" /></a></h1></td>
<td>
<div id="scform_tb" class="cl">
<?php if(CURMODULE == 'forum') { ?>
<span class="y">
<a href="javascript:;" id="quick_sch" class="showmenu" onmouseover="delayShow(this);">快速</a>
<?php if(CURMODULE == 'forum') { ?>
<a href="search.php?mod=forum&amp;adv=yes<?php if($keyword) { ?>&amp;srchtxt=<?php echo $keywordenc;?><?php } ?>">高级</a>
<?php } ?>
</span>
<?php } if($_G['setting']['portalstatus'] && $_G['setting']['search']['portal']['status'] && ($_G['group']['allowsearch'] & 1 || $_G['adminid'] == 1)) { ?><?php
$slist[portal] = <<<EOF
<a href="search.php?mod=portal
EOF;
 if($keyword) { 
$slist[portal] .= <<<EOF
&amp;srchtxt={$keywordenc}&amp;searchsubmit=yes
EOF;
 } 
$slist[portal] .= <<<EOF
"
EOF;
 if(CURMODULE == 'portal') { 
$slist[portal] .= <<<EOF
 class="a"
EOF;
 } 
$slist[portal] .= <<<EOF
>文章</a>
EOF;
?><?php } if($_G['setting']['search']['forum']['status'] && ($_G['group']['allowsearch'] & 2 || $_G['adminid'] == 1)) { ?><?php
$slist[forum] = <<<EOF
<a href="search.php?mod=forum
EOF;
 if($keyword) { 
$slist[forum] .= <<<EOF
&amp;srchtxt={$keywordenc}&amp;searchsubmit=yes
EOF;
 } 
$slist[forum] .= <<<EOF
"
EOF;
 if(CURMODULE == 'forum') { 
$slist[forum] .= <<<EOF
 class="a"
EOF;
 } 
$slist[forum] .= <<<EOF
>帖子</a>
EOF;
?><?php } if(helper_access::check_module('blog') && $_G['setting']['search']['blog']['status'] && ($_G['group']['allowsearch'] & 4 || $_G['adminid'] == 1)) { ?><?php
$slist[blog] = <<<EOF
<a href="search.php?mod=blog
EOF;
 if($keyword) { 
$slist[blog] .= <<<EOF
&amp;srchtxt={$keywordenc}&amp;searchsubmit=yes
EOF;
 } 
$slist[blog] .= <<<EOF
"
EOF;
 if(CURMODULE == 'blog') { 
$slist[blog] .= <<<EOF
 class="a"
EOF;
 } 
$slist[blog] .= <<<EOF
>日志</a>
EOF;
?><?php } if(helper_access::check_module('album') && $_G['setting']['search']['album']['status'] && ($_G['group']['allowsearch'] & 8 || $_G['adminid'] == 1)) { ?><?php
$slist[album] = <<<EOF
<a href="search.php?mod=album
EOF;
 if($keyword) { 
$slist[album] .= <<<EOF
&amp;srchtxt={$keywordenc}&amp;searchsubmit=yes
EOF;
 } 
$slist[album] .= <<<EOF
"
EOF;
 if(CURMODULE == 'album') { 
$slist[album] .= <<<EOF
 class="a"
EOF;
 } 
$slist[album] .= <<<EOF
>相册</a>
EOF;
?><?php } if($_G['setting']['groupstatus'] && $_G['setting']['search']['group']['status'] && ($_G['group']['allowsearch'] & 16 || $_G['adminid'] == 1)) { ?><?php
$slist[group] = <<<EOF
<a href="search.php?mod=group
EOF;
 if($keyword) { 
$slist[group] .= <<<EOF
&amp;srchtxt={$keywordenc}&amp;searchsubmit=yes
EOF;
 } 
$slist[group] .= <<<EOF
"
EOF;
 if(CURMODULE == 'group') { 
$slist[group] .= <<<EOF
 class="a"
EOF;
 } 
$slist[group] .= <<<EOF
>{$_G['setting']['navs']['3']['navname']}</a>
EOF;
?><?php } if(helper_access::check_module('collection') && $_G['setting']['search']['collection']['status'] && ($_G['group']['allowsearch'] & 64 || $_G['adminid'] == 1)) { ?><?php
$slist[collection] = <<<EOF
<a href="search.php?mod=collection
EOF;
 if($keyword) { 
$slist[collection] .= <<<EOF
&amp;srchtxt={$keywordenc}&amp;searchsubmit=yes
EOF;
 } 
$slist[collection] .= <<<EOF
"
EOF;
 if(CURMODULE == 'collection') { 
$slist[collection] .= <<<EOF
 class="a"
EOF;
 } 
$slist[collection] .= <<<EOF
>淘帖</a>
EOF;
?><?php } ?><?php
$slist[user] = <<<EOF
<a href="search.php?mod=user
EOF;
 if($keyword) { 
$slist[user] .= <<<EOF
&amp;srchtxt={$keywordenc}&amp;searchsubmit=yes
EOF;
 } 
$slist[user] .= <<<EOF
"
EOF;
 if(CURMODULE == 'user') { 
$slist[user] .= <<<EOF
 class="a"
EOF;
 } 
$slist[user] .= <<<EOF
>用户</a>
EOF;
?><?php echo implode("", $slist);; ?></div>
<table id="scform_form" cellspacing="0" cellpadding="0">
<tr>
<td class="td_srchtxt"><input type="text" id="scform_srchtxt" name="srchtxt" size="45" maxlength="40" value="<?php echo $keyword;?>" tabindex="1" x-webkit-speech speech /><script type="text/javascript">initSearchmenu('scform_srchtxt');$('scform_srchtxt').focus();</script></td>
<td class="td_srchbtn"><input type="hidden" name="searchsubmit" value="yes" /><button type="submit" id="scform_submit" class="schbtn"><strong>搜索</strong></button></td>
</tr>
</table>
</td>
</tr>
</table>
<?php } else { if(!empty($srchtype)) { ?><input type="hidden" name="srchtype" value="<?php echo $srchtype;?>" /><?php } if($srchtype != 'threadsort') { ?>
<div class="hm mtw ptw pbw"><h1 class="mtw ptw"><a href="./" title="<?php echo $_G['setting']['bbname'];?>"><img src="<?php echo IMGDIR;?>/logo_sc.png" alt="<?php echo $_G['setting']['bbname'];?>" /></a></a></h1></div>
<table id="scform" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
<tr>
<td id="scform_tb" class="xs2">
<?php if(CURMODULE == 'forum') { ?>
<span class="y xs1">
<a href="javascript:;" id="quick_sch" class="showmenu" onmouseover="delayShow(this);">快速</a>
<?php if(CURMODULE == 'forum') { ?>
<a href="search.php?mod=forum&amp;adv=yes">高级</a>
<?php } ?>
</span>
<?php } if(helper_access::check_module('portal') && $_G['setting']['search']['portal']['status'] && ($_G['group']['allowsearch'] & 1 || $_G['adminid'] == 1)) { ?><?php
$slist[portal] = <<<EOF
<a href="search.php?mod=portal
EOF;
 if($keyword) { 
$slist[portal] .= <<<EOF
&amp;srchtxt={$keywordenc}&amp;searchsubmit=yes
EOF;
 } 
$slist[portal] .= <<<EOF
"
EOF;
 if(CURMODULE == 'portal') { 
$slist[portal] .= <<<EOF
 class="a"
EOF;
 } 
$slist[portal] .= <<<EOF
>文章</a>
EOF;
?><?php } if($_G['setting']['search']['forum']['status'] && ($_G['group']['allowsearch'] & 2 || $_G['adminid'] == 1)) { ?><?php
$slist[forum] = <<<EOF
<a href="search.php?mod=forum
EOF;
 if($keyword) { 
$slist[forum] .= <<<EOF
&amp;srchtxt={$keywordenc}&amp;searchsubmit=yes
EOF;
 } 
$slist[forum] .= <<<EOF
"
EOF;
 if(CURMODULE == 'forum') { 
$slist[forum] .= <<<EOF
 class="a"
EOF;
 } 
$slist[forum] .= <<<EOF
>帖子</a>
EOF;
?><?php } if(helper_access::check_module('blog') && $_G['setting']['search']['blog']['status'] && ($_G['group']['allowsearch'] & 4 || $_G['adminid'] == 1) && helper_access::check_module('blog')) { ?><?php
$slist[blog] = <<<EOF
<a href="search.php?mod=blog
EOF;
 if($keyword) { 
$slist[blog] .= <<<EOF
&amp;srchtxt={$keywordenc}&amp;searchsubmit=yes
EOF;
 } 
$slist[blog] .= <<<EOF
"
EOF;
 if(CURMODULE == 'blog') { 
$slist[blog] .= <<<EOF
 class="a"
EOF;
 } 
$slist[blog] .= <<<EOF
>日志</a>
EOF;
?><?php } if(helper_access::check_module('album') && $_G['setting']['search']['album']['status'] && ($_G['group']['allowsearch'] & 8 || $_G['adminid'] == 1) && helper_access::check_module('album')) { ?><?php
$slist[album] = <<<EOF
<a href="search.php?mod=album
EOF;
 if($keyword) { 
$slist[album] .= <<<EOF
&amp;srchtxt={$keywordenc}&amp;searchsubmit=yes
EOF;
 } 
$slist[album] .= <<<EOF
"
EOF;
 if(CURMODULE == 'album') { 
$slist[album] .= <<<EOF
 class="a"
EOF;
 } 
$slist[album] .= <<<EOF
>相册</a>
EOF;
?><?php } if(helper_access::check_module('group') && $_G['setting']['search']['group']['status'] && ($_G['group']['allowsearch'] & 16 || $_G['adminid'] == 1)) { ?><?php
$slist[group] = <<<EOF
<a href="search.php?mod=group
EOF;
 if($keyword) { 
$slist[group] .= <<<EOF
&amp;srchtxt={$keywordenc}&amp;searchsubmit=yes
EOF;
 } 
$slist[group] .= <<<EOF
"
EOF;
 if(CURMODULE == 'group') { 
$slist[group] .= <<<EOF
 class="a"
EOF;
 } 
$slist[group] .= <<<EOF
>{$_G['setting']['navs']['3']['navname']}</a>
EOF;
?><?php } if(helper_access::check_module('collection') && $_G['setting']['search']['collection']['status'] && ($_G['group']['allowsearch'] & 64 || $_G['adminid'] == 1)) { ?><?php
$slist[collection] = <<<EOF
<a href="search.php?mod=collection
EOF;
 if($keyword) { 
$slist[collection] .= <<<EOF
&amp;srchtxt={$keywordenc}&amp;searchsubmit=yes
EOF;
 } 
$slist[collection] .= <<<EOF
"
EOF;
 if(CURMODULE == 'collection') { 
$slist[collection] .= <<<EOF
 class="a"
EOF;
 } 
$slist[collection] .= <<<EOF
>淘帖</a>
EOF;
?><?php } echo implode("", $slist);; ?><a href="search.php?mod=user<?php if($keyword) { ?>&amp;srchtxt=<?php echo $keywordenc;?>&amp;searchsubmit=yes<?php } ?>"<?php if(CURMODULE == 'user') { ?> class="a"<?php } ?>>用户</a>
</td>
</tr>
<tr>
<td>
<table cellspacing="0" cellpadding="0" id="scform_form">
<tr>
<td class="td_srchtxt"><input type="text" id="scform_srchtxt" name="srchtxt" size="65" maxlength="40" value="<?php echo $keyword;?>" tabindex="1" /><script type="text/javascript">initSearchmenu('scform_srchtxt');$('scform_srchtxt').focus();</script></td>
<td class="td_srchbtn"><input type="hidden" name="searchsubmit" value="yes" /><button type="submit" id="scform_submit" value="true"><strong>搜索</strong></button></td>
</tr>
</table>
</td>
</tr>
</table>
<?php } } if(CURMODULE == 'forum') { ?>
<ul id="quick_sch_menu" class="p_pop" style="display: none;">
<li><a href="search.php?mod=forum&amp;srchfrom=3600&amp;searchsubmit=yes">1 小时以内的新帖</a></li>
<li><a href="search.php?mod=forum&amp;srchfrom=14400&amp;searchsubmit=yes">4 小时以内的新帖</a></li>
<li><a href="search.php?mod=forum&amp;srchfrom=28800&amp;searchsubmit=yes">8 小时以内的新帖</a></li>
<li><a href="search.php?mod=forum&amp;srchfrom=86400&amp;searchsubmit=yes">24 小时以内的新帖</a></li>
<li><a href="search.php?mod=forum&amp;srchfrom=604800&amp;searchsubmit=yes">1 周内帖子</a></li>
<li><a href="search.php?mod=forum&amp;srchfrom=2592000&amp;searchsubmit=yes">1 月内帖子</a></li>
<li><a href="search.php?mod=forum&amp;srchfrom=15552000&amp;searchsubmit=yes">6 月内帖子</a></li>
<li><a href="search.php?mod=forum&amp;srchfrom=31536000&amp;searchsubmit=yes">1 年内帖子</a></li>
</ul>
<?php } ?><?php if(!empty($_G['setting']['pluginhooks']['forum_top'])) echo $_G['setting']['pluginhooks']['forum_top'];?><?php $policymsgs = $p = '';?><?php if(is_array($_G['setting']['creditspolicy']['search'])) foreach($_G['setting']['creditspolicy']['search'] as $id => $policy) { ?><?php
$policymsg = <<<EOF

EOF;
 if($_G['setting']['extcredits'][$id]['img']) { 
$policymsg .= <<<EOF
{$_G['setting']['extcredits'][$id]['img']} 
EOF;
 } 
$policymsg .= <<<EOF
{$_G['setting']['extcredits'][$id]['title']} {$policy} {$_G['setting']['extcredits'][$id]['unit']}
EOF;
?><?php $policymsgs .= $p.$policymsg;$p = ', ';?><?php } if($policymsgs) { ?><p>每进行一次搜索将扣除 <?php echo $policymsgs;?></p><?php } ?>
</form>

<?php if(!empty($searchid) && submitcheck('searchsubmit', 1)) { ?><div class="tl">
<div class="sttl mbn">
<h2><?php if($keyword) { ?>结果: <em>找到 “<span class="emfont"><?php echo $keyword;?></span>” 相关内容 <?php echo $index['num'];?> 个</em> <?php if($modfid) { ?><a href="forum.php?mod=modcp&amp;action=thread&amp;fid=<?php echo $modfid;?>&amp;keywords=<?php echo $modkeyword;?>&amp;submit=true&amp;do=search&amp;page=<?php echo $page;?>" target="_blank">进入管理面板</a><?php } } else { ?>结果: <em>找到相关主题 <?php echo $index['num'];?> 个</em><?php } ?></h2>
</div><?php echo adshow("search/y mtw");?><?php if(empty($threadlist)) { ?>
<p class="emp xs2 xg2">对不起，没有找到匹配结果。</p>
<?php } else { ?>
<div class="slst mtw" id="threadlist" <?php if($modfid) { ?> style="position: relative;"<?php } ?>>
<?php if($modfid) { ?>
<form method="post" autocomplete="off" name="moderate" id="moderate" action="forum.php?mod=topicadmin&amp;action=moderate&amp;fid=<?php echo $modfid;?>&amp;infloat=yes&amp;nopost=yes">
<?php } ?>
<ul><?php if(is_array($threadlist)) foreach($threadlist as $thread) { ?><li class="pbw" id="<?php echo $thread['tid'];?>">
<h3 class="xs3">
<?php if($modfid) { if($thread['fid'] == $modfid && ($thread['displayorder'] <= 3 || $_G['adminid'] == 1)) { ?>
<input onclick="tmodclick(this)" type="checkbox" name="moderate[]" value="<?php echo $thread['tid'];?>" />&nbsp;
<?php } else { ?>
<input type="checkbox" disabled="disabled" />&nbsp;
<?php } } ?>
<a href="forum.php?mod=viewthread&amp;tid=<?php echo $thread['realtid'];?>&amp;highlight=<?php echo $index['keywords'];?>" target="_blank" <?php echo $thread['highlight'];?>><?php echo $thread['subject'];?></a>
</h3>
<p class="xg1"><?php echo $thread['replies'];?> 个回复 - <?php echo $thread['views'];?> 次查看</p>
<p><?php if(!$thread['price'] && !$thread['readperm']) { ?><?php echo $thread['message'];?><?php } else { ?>内容隐藏需要，请点击进去查看<?php } ?></p>
<p>
<span><?php echo $thread['dateline'];?></span>
 -
<span>
<?php if($thread['authorid'] && $thread['author']) { ?>
<a href="home.php?mod=space&amp;uid=<?php echo $thread['authorid'];?>" target="_blank"><?php echo $thread['author'];?></a>
<?php } else { if($_G['forum']['ismoderator']) { ?><a href="home.php?mod=space&amp;uid=<?php echo $thread['authorid'];?>" target="_blank">匿名</a><?php } else { ?>匿名<?php } } ?>
</span>
 -
<span><a href="forum.php?mod=forumdisplay&amp;fid=<?php echo $thread['fid'];?>" target="_blank" class="xi1"><?php echo $thread['forumname'];?></a></span>
</p>
</li>
<?php } ?>
</ul>
<?php if($modfid) { ?>
</form>
<script src="<?php echo $_G['setting']['jspath'];?>forum_moderate.js?<?php echo VERHASH;?>" type="text/javascript"></script><?php include template('forum/topicadmin_modlayer'); } ?>
</div>
<?php } if(!empty($multipage)) { ?><div class="pgs cl mbm"><?php echo $multipage;?></div><?php } ?>
</div><?php } ?>

</div>
</div>
<?php if(!empty($_G['setting']['pluginhooks']['forum_bottom'])) echo $_G['setting']['pluginhooks']['forum_bottom'];?><?php $focusid = getfocus_rand($_G[basescript]);?><?php if($focusid !== null) { $focus = $_G['cache']['focus']['data'][$focusid];?><div class="focus" id="focus">
<div class="bm">
<div class="bm_h cl">
<a href="javascript:;" onclick="setcookie('nofocus_<?php echo $focusid;?>', 1, <?php echo $_G['cache']['focus']['cookie'];?>*3600);$('focus').style.display='none'" class="y" title="关闭">关闭</a>
<h2><?php if($_G['cache']['focus']['title']) { ?><?php echo $_G['cache']['focus']['title'];?><?php } else { ?>站长推荐<?php } ?></h2>
</div>
<div class="bm_c">
<dl class="xld cl bbda">
<dt><a href="<?php echo $focus['url'];?>" class="xi2" target="_blank"><?php echo $focus['subject'];?></a></dt>
<?php if($focus['image']) { ?>
<dd class="m"><a href="<?php echo $focus['url'];?>" target="_blank"><img src="<?php echo $focus['image'];?>" alt="<?php echo $focus['subject'];?>" /></a></dd>
<?php } ?>
<dd><?php echo $focus['summary'];?></dd>
</dl>
<p class="ptn hm"><a href="<?php echo $focus['url'];?>" class="xi2" target="_blank">查看 &raquo;</a></p>
</div>
</div>
</div>
<?php } ?><?php echo adshow("footerbanner/wp a_f hm/1");?><?php echo adshow("footerbanner/wp a_f hm/2");?><?php echo adshow("footerbanner/wp a_f hm/3");?><?php echo adshow("float/a_fl/1");?><?php echo adshow("float/a_fr/2");?><?php echo adshow("couplebanner/a_fl a_cb/1");?><?php echo adshow("couplebanner/a_fr a_cb/2");?><?php if(!empty($_G['setting']['pluginhooks']['global_footer'])) echo $_G['setting']['pluginhooks']['global_footer'];?>

<div id="ft" class="w cl">
<em>Powered by <strong><a href="http://www.discuz.net" target="_blank">Discuz!</a></strong> <em><?php echo $_G['setting']['version'];?></em><?php if(!empty($_G['setting']['boardlicensed'])) { ?> <a href="http://license.comsenz.com/?pid=1&amp;host=<?php echo $_SERVER['HTTP_HOST'];?>" target="_blank">Licensed</a><?php } ?></em> &nbsp;
<em>&copy; 2001-2013 <a href="http://www.comsenz.com" target="_blank">Comsenz Inc.</a></em>
<span class="pipe">|</span><?php if(is_array($_G['setting']['footernavs'])) foreach($_G['setting']['footernavs'] as $nav) { if($nav['available'] && ($nav['type'] && (!$nav['level'] || ($nav['level'] == 1 && $_G['uid']) || ($nav['level'] == 2 && $_G['adminid'] > 0) || ($nav['level'] == 3 && $_G['adminid'] == 1)) ||
!$nav['type'] && ($nav['id'] == 'stat' && $_G['group']['allowstatdata'] || $nav['id'] == 'report' && $_G['uid'] || $nav['id'] == 'archiver'))) { ?><?php echo $nav['code'];?><span class="pipe">|</span><?php } } ?>
<strong><a href="<?php echo $_G['setting']['siteurl'];?>" target="_blank"><?php echo $_G['setting']['sitename'];?></a></strong>
<?php if($_G['setting']['icp']) { ?>( <a href="http://www.miibeian.gov.cn/" target="_blank"><?php echo $_G['setting']['icp'];?></a> )<?php } ?>
<?php if(!empty($_G['setting']['pluginhooks']['global_footerlink'])) echo $_G['setting']['pluginhooks']['global_footerlink'];?>
<?php if($_G['setting']['statcode']) { ?><span class="pipe">| <?php echo $_G['setting']['statcode'];?></span><?php } updatesession();?></div>
<?php if($_G['uid'] && !isset($_G['cookie']['checkpm'])) { ?>
<script src="home.php?mod=spacecp&ac=pm&op=checknewpm&rand=<?php echo $_G['timestamp'];?>" type="text/javascript"></script>
<?php } output();?></body>
</html>