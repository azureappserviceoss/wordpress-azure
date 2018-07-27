<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('viewthread_poll_voter');?><?php include template('common/header'); if(empty($_GET['inajax'])) { ?>
<div id="pt" class="bm cl">
<div class="z"><a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em> <?php echo $navigation;?></div>
</div>
<div id="ct" class="wp cl">
<div class="mn">
<div class="bm bw0">
<?php } ?>
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">参与投票的会员</em>
<?php if(!empty($_GET['inajax'])) { ?><span><a href="javascript:;" class="flbc" onclick="hideWindow('<?php echo $_GET['handlekey'];?>')" title="关闭">关闭</a></span><?php } ?>
</h3>
<div class="c voterlist">
<p>
<select class="ps" onchange="<?php if(!empty($_GET['inajax'])) { ?>showWindow('viewvote', 'forum.php?mod=misc&action=viewvote&tid=<?php echo $_G['tid'];?>&polloptionid=' + this.value)<?php } else { ?>location.href = 'forum.php?mod=misc&action=viewvote&tid=<?php echo $_G['tid'];?>&polloptionid=' + this.value;<?php } ?>"><?php if(is_array($polloptions)) foreach($polloptions as $options) { ?><option value="<?php echo $options['polloptionid'];?>"<?php if($options['polloptionid'] == $polloptionid) { ?> selected="selected"<?php } ?>><?php echo $options['polloption'];?></option>
<?php } ?>
</select>
</p>
<ul class="ml mtm cl voterl">
<?php if(!$voterlist) { ?>
<li>无</li>
<?php } else { if(is_array($voterlist)) foreach($voterlist as $voter) { ?><li><p><a href="home.php?mod=space&amp;uid=<?php echo $voter['uid'];?>" target="_blank"><?php echo $voter['username'];?></a></p></li>
<?php } } ?>
</ul>
</div>
<div class="c cl mbn"><?php echo $multipage;?></div>
<?php if(!$_GET['inajax']) { ?>
</div>
</div>
</div>
<?php } include template('common/footer'); ?>