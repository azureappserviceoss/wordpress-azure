<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('spacecp_poke');
0
|| checktplrefresh('./template/mahjong/home/spacecp_poke.htm', './template/mahjong/home/spacecp_poke_type.htm', 1511248239, '2', './data/template/4_2_home_spacecp_poke.tpl.php', './template/mahjong', 'home/spacecp_poke')
|| checktplrefresh('./template/mahjong/home/spacecp_poke.htm', './template/mahjong/common/userabout.htm', 1511248239, '2', './data/template/4_2_home_spacecp_poke.tpl.php', './template/mahjong', 'home/spacecp_poke')
;?><?php include template('common/header'); $icons = array(
0 => '不用动作',
1 => '<img alt="cyx" src="'.STATICURL.'image/poke/cyx.gif" class="vm" /> 踩一下',
2 => '<img alt="wgs" src="'.STATICURL.'image/poke/wgs.gif" class="vm" /> 握个手',
3 => '<img alt="wx" src="'.STATICURL.'image/poke/wx.gif" class="vm" /> 微笑',
4 => '<img alt="jy" src="'.STATICURL.'image/poke/jy.gif" class="vm" /> 加油',
5 => '<img alt="pmy" src="'.STATICURL.'image/poke/pmy.gif" class="vm" /> 抛媚眼',
6 => '<img alt="yb" src="'.STATICURL.'image/poke/yb.gif" class="vm" /> 拥抱',
7 => '<img alt="fw" src="'.STATICURL.'image/poke/fw.gif" class="vm" /> 飞吻',
8 => '<img alt="nyy" src="'.STATICURL.'image/poke/nyy.gif" class="vm" /> 挠痒痒',
9 => '<img alt="gyq" src="'.STATICURL.'image/poke/gyq.gif" class="vm" /> 给一拳',
10 => '<img alt="dyx" src="'.STATICURL.'image/poke/dyx.gif" class="vm" /> 电一下',
11 => '<img alt="yw" src="'.STATICURL.'image/poke/yw.gif" class="vm" /> 依偎',
12 => '<img alt="ppjb" src="'.STATICURL.'image/poke/ppjb.gif" class="vm" /> 拍拍肩膀',
13 => '<img alt="yyk" src="'.STATICURL.'image/poke/yyk.gif" class="vm" /> 咬一口'
);?><?php if(!$_G['inajax']) { ?>
<div id="pt" class="bm cl">
<div class="z"><a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em> <a href="home.php"><?php echo $_G['setting']['navs']['4']['navname'];?></a> <em>&rsaquo;</em> 打个招呼</div>
</div>
<div id="ct" class="ct2_a wp cl">
<div class="mn">
<div class="bm bw0">
<h1 class="mt"><img alt="poke" src="<?php echo STATICURL;?>image/feed/poke.gif" class="vm" /> 招呼</h1>
<ul class="tb cl">
<li<?php echo $actives['poke'];?>><a href="home.php?mod=spacecp&amp;ac=poke">收到的招呼</a></li>
<li<?php echo $actives['send'];?>><a href="home.php?mod=spacecp&amp;ac=poke&amp;op=send">打个招呼</a></li>
</ul>
<?php } if($op == 'send' || $op == 'reply') { if($_G['inajax']) { ?>
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">打个招呼</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<?php } ?>
<form method="post" autocomplete="off" id="pokeform_<?php echo $tospace['uid'];?>" name="pokeform_<?php echo $tospace['uid'];?>" action="home.php?mod=spacecp&amp;ac=poke&amp;op=<?php echo $op;?>&amp;uid=<?php echo $tospace['uid'];?>" <?php if($_G['inajax']) { ?>onsubmit="ajaxpost(this.id, 'return_<?php echo $_GET['handlekey'];?>');"<?php } ?>>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>">
<input type="hidden" name="pokesubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="from" value="<?php echo $_GET['from'];?>" />
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<div class="c <?php if($_G['inajax']) { ?>altw<?php } else { ?>mtm<?php } ?>">
<div class="mbm xs2">
<?php if($tospace['uid']) { ?>
<a href="home.php?mod=space&amp;uid=<?php echo $tospace['uid'];?>" class="avt avts"><?php echo avatar($tospace[uid],small);?></a>
向 <strong><?php echo $tospace['username'];?></strong> 打个招呼:
<?php } else { ?>
用户名: <input type="text" name="username" value="" class="px" />
<?php } ?>
</div>
<ul class="poke cl"><?php if(is_array($icons)) foreach($icons as $k => $v) { ?><li><label for="poke_<?php echo $k;?>"><input type="radio" name="iconid" id="poke_<?php echo $k;?>" value="<?php echo $k;?>" <?php if($k==3) { ?>checked="checked"<?php } ?> /><?php echo $v;?></label></li>
<?php } ?>
</ul>
<input type="text" name="note" id="note" value="" size="30" onkeydown="ctrlEnter(event, 'pokesubmit_btn', 1);" class="px" style="width: 337px;" />
<p class="mbm xg1">内容为可选，并且会覆盖之前的招呼，最多 10 个字</p>
</div>
<p class="o<?php if($_G['inajax']) { ?> pns<?php } ?>">
<button type="submit" name="pokesubmit_btn" id="pokesubmit_btn" value="true" class="pn pnc"><strong>发送</strong></button>
</p>
</form>
<script type="text/javascript">
function succeedhandle_<?php echo $_GET['handlekey'];?>(url, msg, values) {
if(values['from'] == 'notice') {
deleteQueryNotice(values['uid'], 'pokeQuery');
}
showCreditPrompt();
}
</script>

<?php } elseif($op == 'view') { if(is_array($list)) foreach($list as $key => $subvalue) { ?><p class="pbm mbm bbda">
<?php if($subvalue['fromuid']==$space['uid']) { ?>我<?php } else { ?><a href="home.php?mod=space&amp;uid=<?php echo $subvalue['fromuid'];?>" class="xi2"><?php echo $value['fromusername'];?></a><?php } ?>:
<span class="xw0">
<?php if($subvalue['iconid']) { ?><?php echo $icons[$subvalue['iconid']];?><?php } else { ?>打个招呼<?php } if($subvalue['note']) { ?>, 说: <?php echo $subvalue['note'];?><?php } ?>
&nbsp; <span class="xg1"><?php echo dgmdate($subvalue[dateline],'n-j H:i');?></span>
</span>
</p>
<?php } ?>
<div class="pbn ptm xg1 xw0">
<a href="home.php?mod=spacecp&amp;ac=poke&amp;op=reply&amp;uid=<?php echo $value['uid'];?>&amp;handlekey=pokehk_<?php echo $value['uid'];?>" id="a_p_r_<?php echo $value['uid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);">回打招呼</a><span class="pipe">|</span>
<a href="home.php?mod=spacecp&amp;ac=poke&amp;op=ignore&amp;uid=<?php echo $value['uid'];?>" id="a_p_i_<?php echo $value['uid'];?>" onclick="showWindow('pokeignore', this.href, 'get', 0);">忽略</a>
<?php if(!$value['isfriend']) { ?><span class="pipe">|</span><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $value['uid'];?>&amp;handlekey=addfriendhk_<?php echo $value['uid'];?>" id="a_friend_<?php echo $value['uid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);">加为好友</a> <?php } ?>
</div>
<?php } elseif($op == 'ignore') { ?>
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">忽略打招呼</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<form method="post" autocomplete="off" id="friendform_<?php echo $uid;?>" name="friendform_<?php echo $uid;?>" action="home.php?mod=spacecp&amp;ac=poke&amp;op=ignore&amp;uid=<?php echo $uid;?>" <?php if($_G['inajax']) { ?>onsubmit="ajaxpost(this.id, 'return_<?php echo $_GET['handlekey'];?>');"<?php } ?>>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>">
<input type="hidden" name="ignoresubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="from" value="<?php echo $_GET['from'];?>" />
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<div class="c altw mtm mbm">确定忽略招呼吗？</div>
<p class="o pns">
<button type="submit" name="ignoresubmit_btn" class="pn pnc" value="true"><strong>确定</strong></button>
</p>
</form>
<?php } else { ?>
<p class="tbmu">您可以回复招呼或者进行忽略<span class="pipe">|</span><a href="home.php?mod=spacecp&amp;ac=poke&amp;op=ignore" id="a_poke" onclick="showWindow('allignore', this.href, 'get', 0);">全部忽略</a></p>
<?php if($list) { ?>
<div id="poke_ul" class="xld xlda"><?php if(is_array($list)) foreach($list as $key => $value) { ?><dl id="poke_<?php echo $value['uid'];?>" class="bbda cl">
<dd class="m avt"><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>"><?php echo avatar($value[uid],small);?></a></dd>
<dt id="poke_td_<?php echo $value['uid'];?>">
<p class="mbm">
<a href="home.php?mod=space&amp;uid=<?php echo $value['fromuid'];?>" class="xi2"><?php echo $value['fromusername'];?></a>:
<span class="xw0">
<?php if($value['iconid']) { ?><?php echo $icons[$value['iconid']];?><?php } else { ?>打个招呼<?php } if($value['note']) { ?>, 说: <?php echo $value['note'];?><?php } ?>
&nbsp; <span class="xg1"><?php echo dgmdate($value[dateline], 'n-j H:i');?></span>
</span>
</p>
<div class="pbn ptm xg1 xw0 cl">
<div class="y"><a href="javascript:;" onclick="view_poke(<?php echo $value['uid'];?>);">查看所有招呼</a></div>
<a href="home.php?mod=spacecp&amp;ac=poke&amp;op=reply&amp;uid=<?php echo $value['uid'];?>&amp;handlekey=pokereply" id="a_p_r_<?php echo $value['uid'];?>" onclick="showWindow('pokereply', this.href, 'get', 0);">回打招呼</a><span class="pipe">|</span>
<a href="home.php?mod=spacecp&amp;ac=poke&amp;op=ignore&amp;uid=<?php echo $value['uid'];?>&amp;handlekey=pokeignore" id="a_p_i_<?php echo $value['uid'];?>" onclick="showWindow('pokeignore', this.href, 'get', 0);">忽略</a>
<?php if(!$value['isfriend']) { ?><span class="pipe">|</span><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $value['uid'];?>&amp;handlekey=addfriendhk_<?php echo $value['uid'];?>" id="a_friend_<?php echo $value['uid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);">加为好友</a> <?php } ?>
</div>
</dt>
</dl>
<?php } ?>
</div>
<?php if($multi) { ?><div class="pgs cl mtm"><?php echo $multi;?></div><?php } ?>
<script type="text/javascript">
function view_poke(uid) {
ajaxget('home.php?mod=spacecp&ac=poke&op=view&uid='+uid, 'poke_td_'+uid);
}
<?php if($_GET['fuid']) { ?>
view_poke(<?php echo $_GET['fuid'];?>);
<?php } ?>
</script>
<?php } else { ?>
<div class="emp">还没有新招呼</div>
<?php } ?>

<script type="text/javascript">
function succeedhandle_pokereply(url, msg, values) {
if(parseInt(values['uid'])) {
$('poke_'+values['uid']).style.display = "none";
}
showCreditPrompt();
}
function errorhandle_pokeignore(msg, values) {
if(parseInt(values['uid'])) {
$('poke_'+values['uid']).style.display = "none";
}
}
function errorhandle_allignore(msg, values) {
if($('poke_ul')) {
$('poke_ul').innerHTML = '<p class="emp">忽略了全部的招呼</p>';
}
}
</script>
<?php } if(!$_G['inajax']) { ?>
</div>
</div>
<div class="appl"><?php if(!empty($_G['setting']['pluginhooks']['global_userabout_top'][$_G['basescript'].'::'.CURMODULE])) echo $_G['setting']['pluginhooks']['global_userabout_top'][$_G['basescript'].'::'.CURMODULE];?><?php getuserapp(1);?><ul><?php if(is_array($_G['setting']['spacenavs'])) foreach($_G['setting']['spacenavs'] as $nav) { if($nav['available'] && (!$nav['level'] || ($nav['level'] == 1 && $_G['uid']) || ($nav['level'] == 2 && $_G['adminid'] > 0) || ($nav['level'] == 3 && $_G['adminid'] == 1))) { if(in_array($nav['code'], array('userpanelarea1', 'userpanelarea2'))) { if(!empty($_G['my_panelapp']) && $_G['setting']['my_app_status']) { if($nav['code']=='userpanelarea1' && !empty($_G['my_panelapp']['1'])) { if(is_array($_G['my_panelapp']['1'])) foreach($_G['my_panelapp']['1'] as $appid => $app) { ?><li>
<a href="userapp.php?mod=app&amp;id=<?php echo $app['appid'];?>" title="<?php echo $app['appname'];?>"><img <?php if($app['icon']) { ?>src="<?php echo $app['icon'];?>" onerror="this.onerror=null;this.src='http://appicon.manyou.com/icons/<?php echo $app['appid'];?>'"<?php } else { ?> src="http://appicon.manyou.com/icons/<?php echo $app['appid'];?>"<?php } ?> name="<?php echo $appid;?>" alt="<?php echo $app['appname'];?>" /><?php echo $app['appname'];?></a>
</li>
<?php } } elseif($nav['code']=='userpanelarea2' && !empty($_G['my_panelapp']['2'])) { if(is_array($_G['my_panelapp']['2'])) foreach($_G['my_panelapp']['2'] as $appid => $app) { ?><li>
<a href="userapp.php?mod=app&amp;id=<?php echo $app['appid'];?>" title="<?php echo $app['appname'];?>"><img <?php if($app['icon']) { ?>src="<?php echo $app['icon'];?>" onerror="this.onerror=null;this.src='http://appicon.manyou.com/icons/<?php echo $app['appid'];?>'"<?php } else { ?> src="http://appicon.manyou.com/icons/<?php echo $app['appid'];?>"<?php } ?> name="<?php echo $appid;?>" alt="<?php echo $app['appname'];?>" /><?php echo $app['appname'];?></a>
</li>
<?php } } } } else { ?>
<?php echo $nav['code'];?>
<?php } } } ?>
</ul>
<?php if($_G['setting']['my_app_status']) { if(!empty($_G['cache']['userapp'])) { ?>
<ul id="my_defaultapp"><?php if(is_array($_G['cache']['userapp'])) foreach($_G['cache']['userapp'] as $value) { ?><li><a href="userapp.php?mod=app&amp;id=<?php echo $value['appid'];?>" title="<?php echo $value['appname'];?>"><img <?php if($value['icon']) { ?>src="<?php echo $value['icon'];?>" onerror="this.onerror=null;this.src='http://appicon.manyou.com/icons/<?php echo $value['appid'];?>'"<?php } else { ?> src="http://appicon.manyou.com/icons/<?php echo $value['appid'];?>"<?php } ?> alt="<?php echo $value['appname'];?>" /><?php echo $value['appname'];?></a></li>
<?php } ?>
<?php if(!empty($_G['setting']['pluginhooks']['userapp_menu_top'])) echo $_G['setting']['pluginhooks']['userapp_menu_top'];?>
</ul>
<?php } if($_G['my_menu']) { ?>
<ul id="my_userapp"><?php if(is_array($_G['my_menu'])) foreach($_G['my_menu'] as $value) { ?><li id="userapp_li_<?php echo $value['appid'];?>"><a href="userapp.php?mod=app&amp;id=<?php echo $value['appid'];?>" title="<?php echo $value['appname'];?>"><img <?php if($value['icon']) { ?>src="<?php echo $value['icon'];?>" onerror="this.onerror=null;this.src='http://appicon.manyou.com/icons/<?php echo $value['appid'];?>'"<?php } else { ?> src="http://appicon.manyou.com/icons/<?php echo $value['appid'];?>"<?php } ?> alt="<?php echo $value['appname'];?>" /><?php echo $value['appname'];?></a></li>
<?php } ?>
<?php if(!empty($_G['setting']['pluginhooks']['userapp_menu_middle'])) echo $_G['setting']['pluginhooks']['userapp_menu_middle'];?>
</ul>
<?php } if($_G['my_menu_more']) { ?>
<p class="pbm bbda xg1 cl"><a href="javascript:;" class="unfold" id="a_app_more" onclick="userapp_open();">展开</a></p>
<?php } if(checkperm('allowmyop')) { ?>
<ul class="myo mtm">
<li><a href="userapp.php?mod=manage&amp;my_suffix=%2Fapp%2Flist%3Fsort%3Dtime"><img src="<?php echo IMGDIR;?>/app_add.gif" alt="app_add" />添加<?php echo $_G['setting']['navs']['5']['navname'];?></a></li>
<li><a href="userapp.php?mod=manage&amp;ac=menu"><img src="<?php echo IMGDIR;?>/app_set.gif" alt="app_set" />管理<?php echo $_G['setting']['navs']['5']['navname'];?></a></li>
</ul>
<?php } } ?>
<?php if(!empty($_G['setting']['pluginhooks']['global_userabout_bottom'][$_G['basescript'].'::'.CURMODULE])) echo $_G['setting']['pluginhooks']['global_userabout_bottom'][$_G['basescript'].'::'.CURMODULE];?></div>
</div>
<?php } include template('common/footer'); ?>