<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); if($select_form) { ?>
<p class="tbmu">
排序：
<select id="mySelect" onchange="select_form()">
  <option value="uid" <?php echo $order_selected['uid'];?>>按注册时间</option>
  <option value="posts" <?php echo $order_selected['posts'];?>>按发帖总数</option>
  <option value="blogs" <?php echo $order_selected['blogs'];?>>按日志总数</option>
  <option value="credits" <?php echo $order_selected['credits'];?>>按积分总数</option>
</select>
<script type="text/javascript">
function select_form() {
x = $('mySelect');
y = x.options[x.options.selectedIndex].value;
location.href = location.href.replace(/\&select.*/, '') +  '&select=' + y;
}
</script>
</p>
<?php } if($postsrank_change) { ?>
<p id="orderby" class="tbmu">
<a href="misc.php?mod=ranklist&amp;type=member&amp;view=post&amp;orderby=posts" id="posts"<?php if($now_choose == 'posts') { ?> class="a"<?php } ?>>发帖数</a><span class="pipe">|</span>
<a href="misc.php?mod=ranklist&amp;type=member&amp;view=post&amp;orderby=digestposts" id="digestposts"<?php if($now_choose == 'digestposts') { ?> class="a"<?php } ?>>精华数</a><span class="pipe">|</span>
<a href="misc.php?mod=ranklist&amp;type=member&amp;view=post&amp;orderby=thismonth" id="thismonth"<?php if($now_choose == 'thismonth') { ?> class="a"<?php } ?>>最近30天发帖数</a><span class="pipe">|</span>
<a href="misc.php?mod=ranklist&amp;type=member&amp;view=post&amp;orderby=today" id="today"<?php if($now_choose == 'today') { ?> class="a"<?php } ?>>最近24小时发帖数</a>
</p>
<?php } if($inviterank_change) { ?>
<p id="orderby" class="tbmu">
<a href="misc.php?mod=ranklist&amp;type=member&amp;view=invite&amp;orderby=thisweek" id="thisweek"<?php if($now_choose == 'thisweek') { ?> class="a"<?php } ?>>本周</a><span class="pipe">|</span>
<a href="misc.php?mod=ranklist&amp;type=member&amp;view=invite&amp;orderby=thismonth" id="thismonth"<?php if($now_choose == 'thismonth') { ?> class="a"<?php } ?>>本月</a><span class="pipe">|</span>
<a href="misc.php?mod=ranklist&amp;type=member&amp;view=invite&amp;orderby=today" id="today"<?php if($now_choose == 'today') { ?> class="a"<?php } ?>>今日</a><span class="pipe">|</span>
<a href="misc.php?mod=ranklist&amp;type=member&amp;view=invite&amp;orderby=all" id="all"<?php if($now_choose == 'all') { ?> class="a"<?php } ?>>全部</a>
</p>
<?php } if($onlinetimerank_change) { ?>
<p id="orderby" class="tbmu">
<a href="misc.php?mod=ranklist&amp;type=member&amp;view=onlinetime&amp;orderby=thismonth" id="thismonth"<?php if($now_choose == 'thismonth') { ?> class="a"<?php } ?>>本月</a><span class="pipe">|</span>
<a href="misc.php?mod=ranklist&amp;type=member&amp;view=onlinetime&amp;orderby=all" id="all"<?php if($now_choose == 'all') { ?> class="a"<?php } ?>>全部</a>
</p>
<?php } if($list) { ?>
<div class="xld xlda hasrank"><?php if(is_array($list)) foreach($list as $key => $value) { ?><dl class="bbda cl">
<dd class="ranknum"><?php if($value['rank'] <= 3) { ?><img src="<?php echo IMGDIR;?>/rank_<?php echo $value['rank'];?>.gif" alt="<?php echo $value['rank'];?>" /><?php } else { ?><?php echo $value['rank'];?><?php } ?></dd>
<dd class="m avt"><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" target="_blank" c="1"><?php echo avatar($value[uid],small);?></a></dd>
<dt class="y">
<p class="xw0"><a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" target="_blank">去串个门</a></p>
<p class="xw0"><a href="home.php?mod=spacecp&amp;ac=poke&amp;op=send&amp;uid=<?php echo $value['uid'];?>" id="a_poke_<?php echo $key;?>" onclick="showWindow(this.id, this.href, 'get', 0);" title="打个招呼">打个招呼</a></p>
<p class="xw0"><a href="home.php?mod=spacecp&amp;ac=pm&amp;op=showmsg&amp;handlekey=showmsg_<?php echo $value['uid'];?>&amp;touid=<?php echo $value['uid'];?>&amp;pmid=0&amp;daterange=2" id="a_sendpm_<?php echo $key;?>" onclick="showWindow('showMsgBox', this.href, 'get', 0)">发送消息</a></p>
<?php if(isset($value['isfriend']) && !$value['isfriend']) { ?><p class="xw0"><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $value['uid'];?>" id="a_friend_<?php echo $key;?>" onclick="showWindow('friend_<?php echo $key;?>', this.href, 'get', 0)" title="加为好友">加为好友</a></p><?php } ?>
</dt>
<dt>
<a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>" target="_blank"<?php g_color($value[groupid]);?>><?php echo $value['username'];?></a>
<?php if($ols[$value['uid']]) { ?><img src="<?php echo IMGDIR;?>/ol.gif" alt="online" title="在线" class="vm" /> <?php } ?>
</dt>
<dd>
<p>
<?php echo $_G['cache']['usergroups'][$value['groupid']]['grouptitle'];?> <?php g_icon($value[groupid]);?><?php if($value['credits']) { ?>积分数: <?php echo $value['credits'];?><?php } if($value['extcredits']) { ?><?php echo $extcredits[$now_choose]['title'];?>: <?php echo $value['extcredits'];?> <?php echo $extcredits[$now_choose]['unit'];?><?php } if($value['invitenum']) { ?>邀请数: <a href="home.php?mod=spacecp&amp;ac=invite" target="_blank"><?php echo $value['invitenum'];?></a><?php } if($value['posts']) { ?>帖子数: <a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>&amp;do=thread&amp;view=me&amp;from=space" target="_blank"><?php echo $value['posts'];?></a><?php } if($value['blogs']) { ?>日志数: <a href="home.php?mod=space&amp;uid=<?php echo $value['uid'];?>&amp;do=blog&amp;view=me&amp;from=space" target="_blank"><?php echo $value['blogs'];?></a><?php } if($value['views']) { ?>人气: <?php echo $value['views'];?><?php } if($value['onlinetime']) { ?>在线时间: <?php echo $value['onlinetime'];?> 分钟<?php } ?>
</p>

<?php if($value['friends']) { ?><p>好友数: <?php echo $value['friends'];?></p><?php } if($value['lastactivity']) { ?><p>最后活跃: <?php echo $value['lastactivity'];?></p><?php } if($value['unitprice']) { ?><p>竞价单价: <span id="<?php if($value['uid'] == $_G['uid']) { ?>show_unitprice<?php } ?>"><?php echo $value['unitprice'];?></span><?php if($value['uid'] == $_G['uid']) { ?>&nbsp;<a href="home.php?mod=spacecp&amp;ac=common&amp;op=modifyunitprice" id="a_modify_unitprice" onclick="showWindow(this.id, this.href, 'get', 0);">(修改)</a><?php } ?></p><?php } if($value['show_credit']) { ?><p>剩余竞价<?php echo $extcredits[$creditid]['title'];?>: <?php echo $value['show_credit'];?> <?php echo $extcredits[$creditid]['unit'];?></p><?php } if($value['show_note']) { ?><p>竞价宣言: <?php echo $value['show_note'];?></p><?php } ?>
</dd>
</dl>
<?php } if($multi) { ?><div class="pgs cl mtm"><?php echo $multi;?></div><?php } ?>
</div>
<?php } else { ?>
<div class="emp">没有相关成员。</div>
<?php } if($cachetip) { ?><div class="notice">排行榜数据已被缓存，上次于 <?php echo $lastupdate;?> 被更新，下次将于 <?php echo $nextupdate;?> 进行更新</div><?php } ?>