<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<table cellspacing="0" cellpadding="0"><tr><td class="t_f" id="postmessage_<?php echo $post['pid'];?>"><?php echo $post['message'];?></td></tr></table>

<script type="text/javascript">
<?php if($optiontype=='checkbox') { ?>
var max_obj = <?php echo $maxchoices;?>;
var p = 0;
<?php } ?>
</script>

<form id="poll" name="poll" method="post" autocomplete="off" action="forum.php?mod=misc&amp;action=votepoll&amp;fid=<?php echo $_G['fid'];?>&amp;tid=<?php echo $_G['tid'];?>&amp;pollsubmit=yes<?php if($_GET['from']) { ?>&amp;from=<?php echo $_GET['from'];?><?php } ?>&amp;quickforward=yes" onsubmit="if($('post_<?php echo $post['pid'];?>')) {ajaxpost('poll', 'post_<?php echo $post['pid'];?>', 'post_<?php echo $post['pid'];?>');return false}">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="pinf">
<?php if($multiple) { ?><strong>多选投票</strong><?php if($maxchoices) { ?>: ( 最多可选 <?php echo $maxchoices;?> 项 )<?php } } else { ?><strong>单选投票</strong><?php } if($visiblepoll && $_G['group']['allowvote']) { ?> , 投票后结果可见<?php } ?>, 共有 <?php echo $voterscount;?> 人参与投票
<?php if(!$visiblepoll && ($overt || $_G['adminid'] == 1 || $thread['authorid'] == $_G['uid']) && $post['invisible'] == 0) { ?>
<a href="forum.php?mod=misc&amp;action=viewvote&amp;tid=<?php echo $_G['tid'];?>" onclick="showWindow('viewvote', this.href)">查看投票参与人</a>
<?php } ?>
</div>

<?php if(!empty($_G['setting']['pluginhooks']['viewthread_poll_top'])) echo $_G['setting']['pluginhooks']['viewthread_poll_top'];?>

<?php if($_G['forum_thread']['remaintime']) { ?>
<p class="ptmr">
距结束还有:
<strong>
<?php if($_G['forum_thread']['remaintime']['0']) { ?><?php echo $_G['forum_thread']['remaintime']['0'];?> 天<?php } if($_G['forum_thread']['remaintime']['1']) { ?><?php echo $_G['forum_thread']['remaintime']['1'];?> 小时<?php } ?>
<?php echo $_G['forum_thread']['remaintime']['2'];?> 分钟
</strong>
</p>
<?php } elseif($expiration && $expirations < TIMESTAMP) { ?>
<p class="ptmr"><strong>投票已经结束</strong></p>
<?php } ?>

<div class="pcht">

<table summary="poll panel" cellspacing="0" cellpadding="0" width="100%">
<?php if($isimagepoll) { $i = 0;?><tr><?php if(is_array($polloptions)) foreach($polloptions as $key => $option) { $i++;?><?php $imginfo=$option['imginfo'];?><td valign="bottom" id="polloption_<?php echo $option['polloptionid'];?>" width="25%">
<div class="polltd cl">
<?php if($imginfo) { ?>
<a href="javascript:;" title="<?php echo $imginfo['filename'];?>" >
<img id="aimg_<?php echo $imginfo['aid'];?>" aid="<?php echo $imginfo['aid'];?>" src="<?php echo $imginfo['small'];?>" width="130px" onclick="zoom(this, this.getAttribute('zoomfile'), 0, 0, '<?php echo $_G['setting']['showexif'];?>')" zoomfile="<?php echo $imginfo['big'];?>" alt="<?php echo $imginfo['filename'];?>" title="<?php echo $imginfo['filename'];?>" w="<?php echo $imginfo['width'];?>" />
</a>
<?php } else { ?>
<a href="javascript:;" title=""><img src="<?php echo IMGDIR;?>/nophoto.gif" width="130px" /></a>
<?php } ?>
<p class="mtn mbn xi2">
<?php if($_G['group']['allowvote']) { ?>
<label><input class="pr" type="<?php echo $optiontype;?>" id="option_<?php echo $key;?>" name="pollanswers[]" value="<?php echo $option['polloptionid'];?>" <?php if($_G['forum_thread']['is_archived']) { ?>disabled="disabled"<?php } ?> <?php if($optiontype=='checkbox') { ?>onclick="poll_checkbox(this)"<?php } else { ?>onclick="$('pollsubmit').disabled = false"<?php } ?> /></label>
<?php } ?>
<?php echo $option['polloption'];?>
</p>
<?php if(!$visiblepoll) { ?>
<div class="imgf imgf2">
<span class="jdt" style="width: <?php echo $option['width'];?>; background-color:#<?php echo $option['color'];?>">&nbsp;</span>
<p class="imgfc">
<span class="z"><?php echo $option['votes'];?>票</span>
<span class="y"><?php echo $option['percent'];?>% </span>
</p>
</div>
<?php } ?>
</div>
</td>
<?php if($key % 4 == 0 && isset($polloptions[$key])) { ?></tr><tr><?php } } if(($imgpad = $key % 4) > 0) { echo str_repeat('<td width="25%"></td>', 4 - $imgpad);; } ?>
</tr>

<?php } else { if(is_array($polloptions)) foreach($polloptions as $key => $option) { ?><tr<?php if($visiblepoll) { ?> class="ptl"<?php } ?>>
<?php if($_G['group']['allowvote']) { ?>
<td class="pslt"><input class="pr" type="<?php echo $optiontype;?>" id="option_<?php echo $key;?>" name="pollanswers[]" value="<?php echo $option['polloptionid'];?>" <?php if($_G['forum_thread']['is_archived']) { ?>disabled="disabled"<?php } ?> <?php if($optiontype=='checkbox') { ?>onclick="poll_checkbox(this)"<?php } else { ?>onclick="$('pollsubmit').disabled = false"<?php } ?> /></td>
<?php } ?>
<td class="pvt">
<label for="option_<?php echo $key;?>"><?php echo $key;?>. &nbsp;<?php echo $option['polloption'];?></label>
</td>
<td class="pvts"></td>
</tr>

<?php if(!$visiblepoll) { ?>
<tr>
<?php if($_G['group']['allowvote']) { ?>
<td>&nbsp;</td>
<?php } ?>
<td>
<div class="pbg">
<div class="pbr" style="width: <?php echo $option['width'];?>; background-color:#<?php echo $option['color'];?>"></div>
</div>
</td>
<td><?php echo $option['percent'];?>% <em style="color:#<?php echo $option['color'];?>">(<?php echo $option['votes'];?>)</em></td>
</tr>
<?php } } } ?>
<tr>
<?php if($_G['group']['allowvote']) { ?><td class="selector">&nbsp;</td><?php } ?>
<td colspan="<?php if($isimagepoll) { ?>4<?php } else { ?>2<?php } ?>">
<?php if(!empty($_G['setting']['pluginhooks']['viewthread_poll_bottom'])) echo $_G['setting']['pluginhooks']['viewthread_poll_bottom'];?>
<?php if($_G['group']['allowvote'] && !$_G['forum_thread']['is_archived']) { ?>
<button class="pn" type="submit" disabled="disabled" name="pollsubmit" id="pollsubmit" value="true"<?php if($post['invisible'] < 0) { ?> disabled="disabled"<?php } ?>><span>提交</span></button>
<?php if($overt) { ?>
(此为公开投票，其他人可看到您的投票项目)
<?php } } elseif(!$allwvoteusergroup) { ?>
您所在的用户组没有投票权限
<?php } elseif(!$allowvotepolled) { ?>
您已经投过票，谢谢您的参与
<?php } elseif(!$allowvotethread) { ?>
该投票已经关闭或者过期，不能投票
<?php } ?>
</td>
</tr>
</table>

</div>
</form>
