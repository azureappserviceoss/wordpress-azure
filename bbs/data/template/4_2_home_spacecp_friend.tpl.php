<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('spacecp_friend');
0
|| checktplrefresh('./template/mahjong/home/spacecp_friend.htm', './template/mahjong/home/space_friend_nav.htm', 1511534132, '2', './data/template/4_2_home_spacecp_friend.tpl.php', './template/mahjong', 'home/spacecp_friend')
;?><?php include template('common/header'); if(!$_G['inajax']) { ?>
<div id="pt" class="bm cl">
<div class="z">
<a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em>
<a href="home.php?mod=space&amp;do=friend">好友</a> <em>&rsaquo;</em>
<?php if($op == 'find') { ?>
可能认识的人
<?php } elseif($op == 'request') { ?>
好友请求
<?php } elseif($op == 'group') { ?>
好友分组
<?php } ?>
</div>
</div>

<div id="ct" class="ct2_a wp cl">
<div class="mn">
<div class="bm bw0">
<?php } if(!$_G['inajax'] && ($op == 'syn' || $op == 'find' || $op == 'search' || $op == 'group' || $op == 'request')) { ?>
<h1 class="mt"><img alt="friend" src="<?php echo STATICURL;?>image/feed/friend.gif" class="vm" />
<?php if($op == 'find') { ?>
可能认识的人
<?php } elseif($op == 'request') { ?>
好友请求
<?php } elseif($op == 'group') { ?>
好友分组
<?php } else { ?>
好友
<?php } ?>
</h1>
<?php } if($op =='ignore') { ?>
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">忽略好友</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<form method="post" autocomplete="off" id="friendform_<?php echo $uid;?>" name="friendform_<?php echo $uid;?>" action="home.php?mod=spacecp&amp;ac=friend&amp;op=ignore&amp;uid=<?php echo $uid;?>&amp;confirm=1" <?php if($_G['inajax']) { ?>onsubmit="ajaxpost(this.id, 'return_<?php echo $_GET['handlekey'];?>');"<?php } ?>>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>">
<input type="hidden" name="friendsubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="from" value="<?php echo $_GET['from'];?>" />
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<div class="c">确定忽略好友关系吗？</div>
<p class="o pns">
<button type="submit" name="friendsubmit_btn" class="pn pnc" value="true"><strong>确定</strong></button>
</p>
</form>
<script type="text/javascript">
function succeedhandle_<?php echo $_GET['handlekey'];?>(url, msg, values) {
if(values['from'] == 'notice') {
deleteQueryNotice(values['uid'], 'pendingFriend');
} else if(typeof friend_delete == 'function') {
friend_delete(values['uid']);
}
}
</script>
<?php } elseif($op == 'find') { if(!empty($recommenduser) || $nearlist || $friendlist || $onlinelist) { if(!empty($recommenduser)) { ?>
<h2 class="mtw">站长推荐用户</h2>
<ul class="buddy cl"><?php if(is_array($recommenduser)) foreach($recommenduser as $key => $value) { ?><li>
<div class="avt"><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" title="<?php echo $value['username'];?>" target="_blank" c="1"><?php echo avatar($value[uid],small);?></a></div>
<h4><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" title="<?php echo $value['username'];?>"><?php echo $value['username'];?></a></h4>
<p title="<?php echo $value['reason'];?>" class="maxh"><?php echo $value['reason'];?></p>
<p><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $value['uid'];?>" id="a_near_friend_<?php echo $key;?>" onclick="showWindow(this.id, this.href, 'get', 0);" class="addbuddy">加为好友</a></p>
</li>
<?php } ?>
</ul>
<?php } if($nearlist) { ?>
<h2 class="mtw">惊喜，他们就在您的附近 </h2>
<ul class="buddy cl"><?php if(is_array($nearlist)) foreach($nearlist as $key => $value) { ?><li>
<div class="avt"><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" title="<?php echo $value['username'];?>" target="_blank" c="1"><?php echo avatar($value[uid],small);?></a></div>
<h4><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" title="<?php echo $value['username'];?>"><?php echo $value['username'];?></a></h4>
<p><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $value['uid'];?>" id="a_near_friend_<?php echo $key;?>" onclick="showWindow(this.id, this.href, 'get', 0);" class="addbuddy">加为好友</a></p>
</li>
<?php } ?>
</ul>
<?php } if($friendlist) { ?>
<h2 class="mtw">他们是您的好友的好友，您也可能认识</h2>
<ul class="buddy cl"><?php if(is_array($friendlist)) foreach($friendlist as $key => $value) { ?><li>
<div class="avt"><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" title="<?php echo $value['username'];?>" target="_blank" c="1"><?php echo avatar($value[uid],small);?></a></div>
<h4><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" title="<?php echo $value['username'];?>"><?php echo $value['username'];?></a></h4>
<p><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $value['uid'];?>&amp;handlekey=friendhk_<?php echo $value['uid'];?>" id="a_friend_friend_<?php echo $key;?>" onclick="showWindow(this.id, this.href, 'get', 0);" class="addbuddy">加为好友</a></p>
</li>
<?php } ?>
</ul>
<?php } if($onlinelist) { ?>
<h2 class="mtw">他们当前正在线，加为好友就可以互动啦 </h2>
<ul class="buddy cl"><?php if(is_array($onlinelist)) foreach($onlinelist as $key => $value) { ?><li>
<div class="avt"><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" title="<?php echo $value['username'];?>" target="_blank" c="1"><?php echo avatar($value[uid],small);?></a></div>
<h4><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" title="<?php echo $value['username'];?>"><?php echo $value['username'];?></a></h4>
<p><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $value['uid'];?>&amp;handlekey=onlinehk_<?php echo $value['uid'];?>" id="a_online_friend_<?php echo $key;?>" onclick="showWindow(this.id, this.href, 'get', 0);" class="addbuddy">加为好友</a></p>
</li>
<?php } ?>
</ul>
<?php } } else { ?>
<div class="emp mtw ptw hm xs2">
暂时没有可能认识的人
</div>
<?php } } elseif($op == 'search') { ?>

<h3 class="tbmu">搜索用户结果:</h3><?php include template('home/space_list'); } elseif($op=='changenum') { ?>
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">好友热度</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<form method="post" autocomplete="off" id="changenumform_<?php echo $uid;?>" name="changenumform_<?php echo $uid;?>" action="home.php?mod=spacecp&amp;ac=friend&amp;op=changenum&amp;uid=<?php echo $uid;?>">
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>">
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">
<p>调整好友的热度</p>
<p>新的热度:<input type="text" name="num" value="<?php echo $friend['num'];?>" size="5" class="px" /> (0~9999之间的一个数字)</p>
</div>
<p class="o pns">
<button type="submit" name="changenumsubmit" class="pn pnc" value="true"><strong>确定</strong></button>
</p>
</form>
<script type="text/javascript" reload="1">
function succeedhandle_<?php echo $_GET['handlekey'];?>(url, msg, values) {
friend_delete(values['uid']);
$('spannum_'+values['fid']).innerHTML = values['num'];
hideWindow('<?php echo $_GET['handlekey'];?>');
}
</script>
<?php } elseif($op=='changegroup') { ?>
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">好友分组</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<form method="post" autocomplete="off" id="changegroupform_<?php echo $uid;?>" name="changegroupform_<?php echo $uid;?>" action="home.php?mod=spacecp&amp;ac=friend&amp;op=changegroup&amp;uid=<?php echo $uid;?>" <?php if($_G['inajax']) { ?>onsubmit="ajaxpost(this.id, 'return_<?php echo $_GET['handlekey'];?>');"<?php } ?>>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>">
<input type="hidden" name="changegroupsubmit" value="true" />
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">
<p>好友分组</p>
<table><tr><?php $i=0;?><?php if(is_array($groups)) foreach($groups as $key => $value) { ?><td style="padding:8px 8px 0 0;"><label><input type="radio" name="group" value="<?php echo $key;?>"<?php echo $groupselect[$key];?> /><?php echo $value;?></label></td>
<?php if($i%2==1) { ?></tr><tr><?php } $i++;?><?php } ?>
</tr></table>
</div>
<p class="o pns">
<button type="submit" name="changegroupsubmit_btn" class="pn pnc" value="true"><strong>确定</strong></button>
</p>
</form>
<script type="text/javascript">
function succeedhandle_<?php echo $_GET['handlekey'];?>(url, msg, values) {
friend_changegroup(values['gid']);
}
</script>

<?php } elseif($op=='editnote') { ?>

<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">好友备注</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<form method="post" autocomplete="off" id="editnoteform_<?php echo $uid;?>" name="editnoteform_<?php echo $uid;?>" action="home.php?mod=spacecp&amp;ac=friend&amp;op=editnote&amp;uid=<?php echo $uid;?>" <?php if($_G['inajax']) { ?>onsubmit="ajaxpost(this.id, 'return_<?php echo $_GET['handlekey'];?>');"<?php } ?>>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>">
<input type="hidden" name="editnotesubmit" value="true" />
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">
<p>为当前好友填写一句话备注，便于自己识别</p>
<input type="text" name="note" class="px mtn" value="<?php echo $friend['note'];?>" size="50" />
</div>
<p class="o pns">
<button type="submit" name="editnotesubmit_btn" class="pn pnc" value="true"><strong>确定</strong></button>
</p>
</form>
<script type="text/javascript">
function succeedhandle_<?php echo $_GET['handlekey'];?>(url, msg, values) {
var uid=values['uid'];
var elem = $('friend_note_'+uid);
if(elem) {
elem.innerHTML = values['note'];
}
}
</script>

<?php } elseif($op=='group') { ?>

<p class="tbmu">
<a href="home.php?mod=spacecp&amp;ac=friend&amp;op=group"<?php if(!isset($_GET['group'])) { ?> class="a"<?php } ?>>全部好友</a><?php if(is_array($groups)) foreach($groups as $key => $value) { ?><span class="pipe">|</span><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=group&amp;group=<?php echo $key;?>"<?php if(isset($_GET['group']) && $_GET['group']==$key) { ?> class="a"<?php } ?>><?php echo $value;?></a>
<?php } ?>
</p>
<p class="tbmu">对选定的好友进行分组，热度表示的是您跟好友互动的次数 </p>

<?php if($list) { ?>
<form method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=friend&amp;op=group&amp;ref">
<div id="friend_ul">
<ul class="buddy cl"><?php if(is_array($list)) foreach($list as $key => $value) { ?><li>
<div class="avt"><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>"><?php echo avatar($value[uid],small);?></a></div>
<h4><input type="checkbox" name="fuids[]" value="<?php echo $value['uid'];?>" class="pc" /> <a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>"><?php echo $value['username'];?></a></h4>
<p class="xg1">热度:<?php echo $value['num'];?></p>
<p class="xg1"><?php echo $value['group'];?></p>
</li>
<?php } ?>
</ul>
</div>
<div class="mtn">
<label for="chkall" onclick="checkAll(this.form, 'fuids')"><input type="checkbox" name="chkall" id="chkall" class="pc" />全选</label>
设置用户组:
<select name="group" class="ps vm"><?php if(is_array($groups)) foreach($groups as $key => $value) { ?><option value="<?php echo $key;?>"><?php echo $value;?></option>
<?php } ?>
</select>&nbsp;
<button type="submit" name="btnsubmit" value="true" class="pn pnc vm"><strong>确定</strong></button>
</div>
<?php if($multi) { ?><div class="pgs cl mtm"><?php echo $multi;?></div><?php } ?>
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="groupsubmin" value="true" />
</form>
<?php } else { ?>
<div class="emp">没有相关用户列表</div>
<?php } } elseif($op=='groupname') { ?>
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">好友组</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<div id="__groupnameform_<?php echo $group;?>">
<form method="post" autocomplete="off" id="groupnameform_<?php echo $group;?>" name="groupnameform_<?php echo $group;?>" action="home.php?mod=spacecp&amp;ac=friend&amp;op=groupname&amp;group=<?php echo $group;?>" <?php if($_G['inajax']) { ?>onsubmit="ajaxpost(this.id, 'return_<?php echo $_GET['handlekey'];?>');"<?php } ?>>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>">
<input type="hidden" name="groupnamesubmit" value="true" />
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">
<p>设置新好友分组名</p>
<p class="mtm">新名称:<input type="text" name="groupname" value="<?php echo $groups[$group];?>" size="15" class="px" /></p>
</div>
<p class="o pns">
<button type="submit" name="groupnamesubmit_btn" value="true" class="pn pnc"><strong>确定</strong></button>
</p>
</form>
<script type="text/javascript">
function succeedhandle_<?php echo $_GET['handlekey'];?>(url, msg, values) {
friend_changegroupname(values['gid']);
}
</script>
</div>

<?php } elseif($op=='groupignore') { ?>
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">调整用户组动态</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<div id="<?php echo $group;?>">
<form method="post" autocomplete="off" id="groupignoreform" name="groupignoreform" action="home.php?mod=spacecp&amp;ac=friend&amp;op=groupignore&amp;group=<?php echo $group;?>" <?php if($_G['inajax']) { ?>onsubmit="ajaxpost(this.id, 'return_<?php echo $_GET['handlekey'];?>');"<?php } ?>>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>">
<input type="hidden" name="groupignoresubmit" value="true" />
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">
<?php if(!isset($space['privacy']['filter_gid'][$group])) { ?>
<p>在首页不显示该用户组的好友动态</p>
<?php } else { ?>
<p>在首页显示该用户组的好友动态</p>
<?php } ?>
</div>
<p class="o pns">
<button type="submit" name="groupignoresubmit_btn" class="pn pnc" value="true"><strong>确定</strong></button>
</p>
</form>
</div>
<?php } elseif($op=='request') { ?>

<div class="tbmu">
<?php if($list) { ?>
<div class="y">
<a href="home.php?mod=spacecp&amp;ac=friend&amp;op=addconfirm&amp;key=<?php echo $space['key'];?>">批准全部申请</a><span class="pipe">|</span><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=ignore&amp;confirm=1&amp;key=<?php echo $space['key'];?>" onclick="return confirm('确定要忽略所有的好友申请吗？');">忽略所有好友申请(慎用)</a>
</div>
<?php } ?>
<span id="add_friend_div">请选定好友的申请进行批准或忽略</span>
<?php if($maxfriendnum) { ?>
(最多可以添加 <?php echo $maxfriendnum;?> 个好友)
<p>
<?php if($_G['magic']['friendnum']) { ?>
<img src="<?php echo STATICURL;?>image/magic/friendnum.small.gif" alt="friendnum" class="vm" />
<a id="a_magic_friendnum" href="home.php?mod=magic&amp;mid=friendnum" onclick="showWindow(this.id, this.href, 'get', '0')">我要扩容</a>
(您可以购买道具“<?php echo $_G['setting']['magics']['friendnum'];?>”来扩容，让自己可以添加更多的好友 )
<?php } ?>
</p>
<?php } ?>
</div>
<?php if($list) { ?>
<ul id="friend_ul"><?php if(is_array($list)) foreach($list as $key => $value) { ?><li id="friend_tbody_<?php echo $value['fuid'];?>">
<table cellpadding="0" cellspacing="0" class="tfm">
<tr>
<td width="140">
<div class="avt avtm"><a href="home.php?mod=space&amp;uid=<?php echo $value['fuid'];?>" c="1"><?php echo avatar($value[fuid],middle);?></a></div>
</td>
<td>
<h4>
<a href="home.php?mod=space&amp;uid=<?php echo $value['fuid'];?>"><?php echo $value['fusername'];?></a>
<?php if($ols[$value['fuid']]) { ?><img src="<?php echo IMGDIR;?>/ol.gif" alt="online" title="在线" class="vm" /> <?php } if($value['videostatus']) { ?>
<img src="<?php echo IMGDIR;?>/videophoto.gif" alt="videophoto" class="vm" /> <span class="xg1">已通过视频认证</span>
<?php } ?>
</h4>
<div id="friend_<?php echo $value['fuid'];?>">
<?php if($value['note']) { ?><div class="quote"><blockquote id="quote"><?php echo $value['note'];?></blockquote></div><?php } ?>
<p><?php echo dgmdate($value[dateline], 'n-j H:i');?></p>
<p><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=getcfriend&amp;fuid=<?php echo $value['fuid'];?>&amp;handlekey=cfrfriendhk_<?php echo $value['uid'];?>" id="a_cfriend_<?php echo $key;?>" onclick="showWindow(this.id, this.href, 'get', 0);" class="xi2">查看你们的共同好友</a></p>
<p class="mtm cl pns">
<a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $value['fuid'];?>&amp;handlekey=afrfriendhk_<?php echo $value['uid'];?>" id="afr_<?php echo $value['fuid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);" class="pn z"><em class="z">批准申请</em></a>
<a href="home.php?mod=spacecp&amp;ac=friend&amp;op=ignore&amp;uid=<?php echo $value['fuid'];?>&amp;confirm=1&amp;handlekey=afifriendhk_<?php echo $value['uid'];?>" id="afi_<?php echo $value['fuid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);" class="z ptn">忽略</a>
</p>
</div>
</td>
</tr>
<tbody id="cf_<?php echo $value['fuid'];?>"></tbody>
</table>
</li>
<?php } ?>
</ul>
<?php if($multi) { ?><div class="pgs cl mtm"><?php echo $multi;?></div><?php } } else { ?>
<div class="emp">没有新的好友请求</div>
<?php } } elseif($op=='getcfriend') { ?>

<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">共同好友</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<div class="c" style="width: 370px;">
<?php if($list) { if(count($list)>14) { ?>
<p>当前最多显示 15 位共同的好友</p>
<?php } else { ?>
<p>你们目前有 <?php echo count($list)?> 位共同的好友</p>
<?php } ?>
<ul class="mtm ml mls cl"><?php if(is_array($list)) foreach($list as $key => $value) { ?><li>
<div class="avt"><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>"><?php echo avatar($value[uid],small);?></a></div>
<p><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" title="<?php echo $value['username'];?>"><?php echo $value['username'];?></a></p>
</li>
<?php } ?>
</ul>
<?php } else { ?>
<p>你们目前还没有共同的好友</p>
<?php } ?>
</div>

<?php } elseif($op=='add') { ?>
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">加为好友</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<form method="post" autocomplete="off" id="addform_<?php echo $tospace['uid'];?>" name="addform_<?php echo $tospace['uid'];?>" action="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $tospace['uid'];?>" <?php if($_G['inajax']) { ?>onsubmit="ajaxpost(this.id, 'return_<?php echo $_GET['handlekey'];?>');"<?php } ?>>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="addsubmit" value="true" />
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">
<table>
<tr>
<th valign="top" width="60" class="avt"><a href="home.php?mod=space&amp;uid=<?php echo $tospace['uid'];?>"><?php echo avatar($tospace[uid],small);?></th>
<td valign="top">添加 <strong><?php echo $tospace['username'];?></strong> 为好友，附言:<br />
<input type="text" name="note" value="" size="35" class="px"  onkeydown="ctrlEnter(event, 'addsubmit_btn', 1);" />
<p class="mtn xg1">(附言为可选，<?php echo $tospace['username'];?> 会看到这条附言，最多 10 个字 )</p>
<p class="mtm">
分组: <select name="gid" class="ps"><?php if(is_array($groups)) foreach($groups as $key => $value) { ?><option value="<?php echo $key;?>" <?php if(empty($space['privacy']['groupname']) && $key==1) { ?> selected="selected"<?php } ?>><?php echo $value;?></option>
<?php } ?>
</select>
</p>
</td>
</tr>
</table>
</div>
<p class="o pns">
<button type="submit" name="addsubmit_btn" id="addsubmit_btn" value="true" class="pn pnc"><strong>确定</strong></button>
</p>
</form>
<?php } elseif($op=='add2') { ?>

<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">批准请求</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<form method="post" autocomplete="off" id="addratifyform_<?php echo $tospace['uid'];?>" name="addratifyform_<?php echo $tospace['uid'];?>" action="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $tospace['uid'];?>" <?php if($_G['inajax']) { ?>onsubmit="ajaxpost(this.id, 'return_<?php echo $_GET['handlekey'];?>');"<?php } ?>>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="add2submit" value="true" />
<input type="hidden" name="from" value="<?php echo $_GET['from'];?>" />
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">
<table cellspacing="0" cellpadding="0">
<tr>
<th valign="top" width="60" class="avt"><a href="home.php?mod=space&amp;uid=<?php echo $tospace['uid'];?>"><?php echo avatar($tospace[uid],small);?></th>
<td valign="top">
<p>批准 <strong><?php echo $tospace['username'];?></strong> 的好友请求，并分组:</p>
<table><tr><?php $i=0;?><?php if(is_array($groups)) foreach($groups as $key => $value) { ?><td style="padding:8px 8px 0 0;"><label for="group_<?php echo $key;?>"><input type="radio" name="gid" id="group_<?php echo $key;?>" value="<?php echo $key;?>"<?php echo $groupselect[$key];?> /><?php echo $value;?></label></td>
<?php if($i%2==1) { ?></tr><tr><?php } $i++;?><?php } ?>
</tr></table>
</td>
</tr>
</table>
</div>
<p class="o pns">
<button type="submit" name="add2submit_btn" value="true" class="pn pnc"><strong>批准</strong></button>
</p>
</form>
<script type="text/javascript">
function succeedhandle_<?php echo $_GET['handlekey'];?>(url, msg, values) {
if(values['from'] == 'notice') {
deleteQueryNotice(values['uid'], 'pendingFriend');
} else {
myfriend_post(values['uid']);
}
}
</script>
<?php } elseif($op=='getinviteuser') { ?>
<?php echo $jsstr;?>
<?php } if(!$_G['inajax']) { ?>
</div>
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
</div></div>
</div>
<?php } include template('common/footer'); ?>