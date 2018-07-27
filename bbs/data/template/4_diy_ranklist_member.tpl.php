<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('member');
0
|| checktplrefresh('./template/mahjong/ranklist/member.htm', './template/mahjong/ranklist/side_left.htm', 1498571865, 'diy', './data/template/4_diy_ranklist_member.tpl.php', './template/mahjong', 'ranklist/member')
;?><?php include template('common/header'); ?><div id="pt" class="bm cl">
<div class="z">
<a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em>
<a href="misc.php?mod=ranklist">排行</a> <em>&rsaquo;</em>
用户排行
</div>
</div>

<style id="diy_style" type="text/css"></style>

<!--[diy=diyranklisttop]--><div id="diyranklisttop" class="area"></div><!--[/diy]-->

<div id="ct" class="ct2_a wp cl">
<div class="mn">
<!--[diy=diycontenttop]--><div id="diycontenttop" class="area"></div><!--[/diy]-->
<div class="bm bw0">
<h1 class="mt">用户排行</h1>
<ul class="tb cl">
<li<?php echo $a_actives['show'];?>><a href="misc.php?mod=ranklist&amp;type=member">竞价排行</a></li>
<li<?php echo $a_actives['beauty'];?>><a href="misc.php?mod=ranklist&amp;type=member&amp;view=beauty">美女排行</a></li>
<li<?php echo $a_actives['handsome'];?>><a href="misc.php?mod=ranklist&amp;type=member&amp;view=handsome">帅哥排行</a></li>
<li<?php echo $a_actives['credit'];?>><a href="misc.php?mod=ranklist&amp;type=member&amp;view=credit">积分排行</a></li>
<li<?php echo $a_actives['friendnum'];?>><a href="misc.php?mod=ranklist&amp;type=member&amp;view=friendnum">好友数排行</a></li>
<li<?php echo $a_actives['invite'];?>><a href="misc.php?mod=ranklist&amp;type=member&amp;view=invite">邀请排行</a></li>
<li<?php echo $a_actives['post'];?>><a href="misc.php?mod=ranklist&amp;type=member&amp;view=post">发帖数排行</a></li>
<?php if(helper_access::check_module('blog')) { ?>
<li<?php echo $a_actives['blog'];?>><a href="misc.php?mod=ranklist&amp;type=member&amp;view=blog">日志数排行</a></li>
<?php } ?>
<li<?php echo $a_actives['onlinetime'];?>><a href="misc.php?mod=ranklist&amp;type=member&amp;view=onlinetime">在线时间排行</a></li>
</ul>

<script type="text/javascript">
function checkCredit(id) {
var maxCredit = parseInt(<?php echo $space['credit'];?>);
var idval = parseInt($(id).value);
if(/^(\d+)$/.test(idval) == false) {
showDialog('您所填写的<?php echo $extcredits[$creditid]['title'];?>不是一个合法数值', 'notice', '提示信息', null, 0);
return false;
} else if(idval > maxCredit) {
showDialog('您的当前<?php echo $extcredits[$creditid]['title'];?>为 <?php echo $space['credit'];?>，请填写一个小于该值的数字', 'notice', '提示信息', null, 0);
return false;
} else if(idval < 1) {
showDialog('您所填写的<?php echo $extcredits[$creditid]['title'];?>不能小于1', 'notice', '提示信息', null, 0);
return false;
}
if(id == 'showcredit') {
var price = parseInt($('unitprice').value);
if(/^(\d+)$/.test(price) == false) {
showDialog('您所填写的单价不是一个合法数值', 'notice', '提示信息', null, 0);
return false;
} else if(price < 1) {
showDialog('您所填写的单价不能小于1', 'notice', '提示信息', null, 0);
return false;
} else if(price > idval+parseInt(<?php echo $myallcredit;?>)) {
showDialog('您所填写的单价不能高于竞价总额', 'notice', '提示信息', null, 0);
return false;
}
}
return true;
}
</script>
<?php if($creditsrank_change) { ?>
<p id="orderby" class="tbmu">
<a href="misc.php?mod=ranklist&amp;type=member&amp;view=credit&amp;orderby=all" id="all"<?php if($now_choose == 'all') { ?> class="a"<?php } ?>>全部</a>
<?php if($extcredits) { if(is_array($extcredits)) foreach($extcredits as $key => $credit) { ?><span class="pipe">|</span><a href="misc.php?mod=ranklist&amp;type=member&amp;view=credit&amp;orderby=<?php echo $key;?>" id="<?php echo $key;?>"<?php if($now_choose == $key) { ?> class="a"<?php } ?>><?php echo $credit['title'];?></a>
<?php } } ?>
</p>
<?php } if($now_pos >= 0) { ?>
<div class="tbmu">
<?php if($_GET['view']=='show') { ?>
<h3 class="mbn">排行榜公告:</h3>
<?php if($space['unitprice']) { ?>
自己当前的竞价单价: <?php echo $space['unitprice'];?> <?php echo $extcredits[$creditid]['unit'];?>,当前排名 <span style="font-size:20px;color:red;"><?php echo $now_pos;?></span> ,再接再厉!
<?php } else { ?>
您现在还没有上榜。让自己上榜吧，这会大大提升您的主页曝光率。
<?php } ?>
<br />竞价单价越多，竞价排名越靠前，您的主页曝光率也会越高；
<br />上榜用户的主页被别人有效浏览一次，将从竞价<?php echo $extcredits[$creditid]['title'];?>中扣除您设定的竞价值(恶意刷新访问不扣减)。
<?php } else { if($_GET['view']=='credit') { ?>
<a href="home.php?mod=spacecp&amp;ac=credit">您当前的<?php if($now_choose=='all') { ?>积分<?php } else { ?><?php echo $extcredits[$now_choose]['title'];?><?php } ?>: <?php echo $mycredits;?></a>
<?php } elseif($_GET['view']=='friendnum') { ?>
<a href="home.php?mod=space&amp;do=friend">您当前的好友数: <?php echo $space['friends'];?></a>
<?php } ?>
,当前排名 <span style="font-size:20px;color:red;"><?php echo $now_pos;?></span> ,再接再厉!
<?php } if($cache_mode) { ?>
<p>
下面列出的为排行前100名，数据每 <?php echo $cache_time;?> 分钟更新一次。
</p>
<?php } ?>
</div>

<?php if($_GET['view']=='show' && $_G['uid']) { if($creditid) { ?>
<div class="tbmu mbm pbw cl">
<form method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=top" onsubmit="return checkCredit('showcredit');" class="z">
<table>
<caption><h3 class="mbn">我也要上榜</h3></caption>
<tr>
<th class="pbn">
我的上榜宣言
<p class="xg1">最多50个汉字，会显示在榜单中</p>
</th>
<th class="pbn">
竞价单价
<p class="xg1"><?php if($_G['uid']) { ?><a href="home.php?mod=spacecp&amp;ac=common&amp;op=modifyunitprice" id="a_modify_unitprice" onclick="showWindow(this.id, this.href, 'get', 0);">(修改单价)</a><?php } ?></p>
</th>
<th class="pbn">
增加竞价<?php echo $extcredits[$creditid]['title'];?>
<p class="xg1">不要超过自己的<?php echo $extcredits[$creditid]['title'];?> <?php echo $space['credit'];?> <?php echo $extcredits[$creditid]['unit'];?></p>
</th>
</tr>
<tr>
<td><input type="text" name="note" class="px" value="" size="25" /></td>
<td>
&nbsp;<input type="text" id="unitprice" name="unitprice" class="px vm" value="1" size="7" onblur="checkCredit('showcredit');" />
</td>
<td>
&nbsp;<input type="text" id="showcredit" name="showcredit" class="px vm" value="100" size="7" onblur="checkCredit('showcredit');" />&nbsp;
<button type="submit" name="show_submit" class="pn vm"><em>增加</em></button>
</td>
</tr>
</table>
<input type="hidden" name="showsubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
</form>

<form method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=top" onsubmit="return checkCredit('stakecredit');" class="y">
<table>
<caption><h3 class="mbn">帮助好友来上榜</h3></caption>
<tr>
<td class="pbn">
要帮助的好友
<p class="xg1">请输入好友的用户名</p>
</td>
<td class="pbn">
赠送竞价<?php echo $extcredits[$creditid]['title'];?>
<p class="xg1">不要超过自己的<?php echo $extcredits[$creditid]['title'];?> <?php echo $space['credit'];?> <?php echo $extcredits[$creditid]['unit'];?></p>
</td>
</tr>
<tr>
<td><input type="text" name="fusername" class="px" value="" size="15" /></td>
<td>
&nbsp;<input type="text" name="stakecredit" id="stakecredit" class="px vm" value="20" size="7" onblur="checkCredit('stakecredit');" />&nbsp;
<button type="submit" name="friend_submit" class="pn vm"><em>赠送</em></button>
</td>
</tr>
</table>
<input type="hidden" name="friendsubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
</form>
</div>
<?php } else { ?>
<div class="mbm bbda emp">管理员已关闭竞价,暂时无法继续上榜</div>
<?php } } } include template('ranklist/member_list'); ?></div>
<!--[diy=diycontentbottom]--><div id="diycontentbottom" class="area"></div><!--[/diy]-->
</div>
<div class="appl">
<!--[diy=diysidetop]--><div id="diysidetop" class="area"></div><!--[/diy]--><div class="tbn">
<h2 class="mt bbda"><?php echo $_G['setting']['navs']['8']['navname'];?></h2>
<ul>
<li class="cl<?php if($_GET['type'] == 'index' || !$_GET['type']) { ?> a<?php } ?>"><a href="misc.php?mod=ranklist">全部</a></li>
<?php if($ranklist_setting['member']['available']) { ?>
<li class="cl<?php if($_GET['type'] == 'member') { ?> a<?php } ?>"><a href="misc.php?mod=ranklist&amp;type=member">用户</a></li>
<?php } if($ranklist_setting['thread']['available']) { ?>
<li class="cl<?php if($_GET['type'] == 'thread') { ?> a<?php } ?>"><a href="misc.php?mod=ranklist&amp;type=thread&amp;view=replies&amp;orderby=thisweek">帖子</a></li>
<?php } if(helper_access::check_module('blog') && $ranklist_setting['blog']['available']) { ?>
<li class="cl<?php if($_GET['type'] == 'blog') { ?> a<?php } ?>"><a href="misc.php?mod=ranklist&amp;type=blog&amp;view=heats&amp;orderby=thisweek">日志</a></li>
<?php } if($ranklist_setting['poll']['available']) { ?>
<li class="cl<?php if($_GET['type'] == 'poll') { ?> a<?php } ?>"><a href="misc.php?mod=ranklist&amp;type=poll&amp;view=heats&amp;orderby=thisweek">投票</a></li>
<?php } if($ranklist_setting['activity']['available']) { ?>
<li class="cl<?php if($_GET['type'] == 'activity') { ?> a<?php } ?>"><a href="misc.php?mod=ranklist&amp;type=activity&amp;view=heats&amp;orderby=thismonth">活动</a></li>
<?php } if(helper_access::check_module('album') && $ranklist_setting['picture']['available']) { ?>
<li class="cl<?php if($_GET['type'] == 'picture') { ?> a<?php } ?>"><a href="misc.php?mod=ranklist&amp;type=picture&amp;view=hot&amp;orderby=thismonth">图片</a></li>
<?php } if($ranklist_setting['forum']['available']) { ?>
<li class="cl<?php if($_GET['type'] == 'forum') { ?> a<?php } ?>"><a href="misc.php?mod=ranklist&amp;type=forum&amp;view=threads">版块</a></li>
<?php } if($ranklist_setting['group']['available']&&$_G['setting']['groupstatus']) { ?>
<li class="cl<?php if($_GET['type'] == 'group') { ?> a<?php } ?>"><a href="misc.php?mod=ranklist&amp;type=group&amp;view=credit">群组</a></li>
<?php } ?>
</ul>
<?php if(!empty($_G['setting']['pluginhooks']['ranklist_nav_extra'])) echo $_G['setting']['pluginhooks']['ranklist_nav_extra'];?>
</div><!--[diy=diysidebottom]--><div id="diysidebottom" class="area"></div><!--[/diy]-->
</div>
</div>

<!--[diy=diyranklistbottom]--><div id="diyranklistbottom" class="area"></div><!--[/diy]--><?php include template('common/footer'); ?>