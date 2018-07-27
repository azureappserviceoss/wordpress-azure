<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); if($list) { ?>
<ul class="buddy cl"><?php if(is_array($list)) foreach($list as $key => $value) { ?><li class="bbda cl">
<div class="avt"><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" target="_blank" c="1"><?php echo avatar($value[uid],small);?></a></div>
<h4>
<a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" title="<?php echo $value['username'];?>" target="_blank"<?php g_color($value[groupid]);?>><?php echo $value['username'];?></a>
<?php if($ols[$value['uid']]) { ?><img src="<?php echo IMGDIR;?>/ol.gif" alt="online" title="在线" class="vm" /><?php } if($value['videophotostatus']) { ?>&nbsp;<img src="<?php echo IMGDIR;?>/videophoto.gif" title="视频认证 已认证" class="vm" /><?php } ?>
</h4>
<p class="maxh">
<?php echo $_G['cache']['usergroups'][$value['groupid']]['grouptitle'];?> <?php g_icon($value[groupid]);?><?php if($value['credits']) { ?>&nbsp;积分数: <?php echo $value['credits'];?><?php } ?>
</p>
<div class="xg1">
<a href="javascript:;" id="interaction_<?php echo $value['uid'];?>" onmouseover="showMenu(this.id);" class="showmenu">互动</a>
<?php if(isset($value['follow']) && $key != $_G['uid']) { ?><span class="pipe">|</span><a href="home.php?mod=spacecp&amp;ac=follow&amp;op=<?php if($value['follow']) { ?>del<?php } else { ?>add<?php } ?>&amp;hash=<?php echo FORMHASH;?>&amp;fuid=<?php echo $value['uid'];?>" id="a_followmod_<?php echo $key;?>" onclick="showWindow('followmod', this.href, 'get', 0)"><?php if($value['follow']) { ?>取消收听<?php } else { ?>收听TA<?php } ?></a><?php } if(isset($value['isfriend']) && !$value['isfriend']) { ?><span class="pipe">|</span><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $value['uid'];?>" id="a_friend_<?php echo $key;?>" onclick="showWindow('friend_<?php echo $key;?>', this.href, 'get', 0)" title="加为好友">加为好友</a><?php } ?>
</div>
<div id="interaction_<?php echo $value['uid'];?>_menu" class="p_pop" style="display: none; width: 80px;">
<p><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>&amp;do=profile" target="_blank" title="查看资料">查看资料</a></p>
<p><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" target="_blank" title="去串个门">去串个门</a></p>
<p><a href="home.php?mod=spacecp&amp;ac=poke&amp;op=send&amp;uid=<?php echo $value['uid'];?>" id="a_poke_<?php echo $key;?>" onclick="showWindow(this.id, this.href, 'get', 0);" title="打个招呼">打个招呼</a></p>
<p><a href="home.php?mod=spacecp&amp;ac=pm&amp;op=showmsg&amp;handlekey=showmsg_<?php echo $value['uid'];?>&amp;touid=<?php echo $value['uid'];?>&amp;pmid=0&amp;daterange=2" id="a_sendpm_<?php echo $key;?>" onclick="showWindow('showMsgBox', this.href, 'get', 0)" title="发送消息">发送消息</a></p>
</div>
</li>
<?php } ?>
</ul>
<?php if($multi) { ?><div class="mtm pgs cl"><?php echo $multi;?></div><?php } ?>
<script type="text/javascript">
function succeedhandle_followmod(url, msg, values) {
var fObj = $('a_followmod_'+values['fuid']);
if(values['type'] == 'add') {
fObj.innerHTML = '取消收听';
fObj.className = 'flw_btn_unfo';
fObj.href = 'home.php?mod=spacecp&ac=follow&op=del&fuid='+values['fuid'];
} else if(values['type'] == 'del') {
fObj.innerHTML = '收听TA';
fObj.className = 'flw_btn_fo';
fObj.href = 'home.php?mod=spacecp&ac=follow&op=add&hash=<?php echo FORMHASH;?>&fuid='+values['fuid'];
}
}
</script>
<?php } else { ?>
<div class="emp">没有相关成员</div>
<?php } ?>