<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('misc_ajax');?><?php include template('common/header'); if($op == 'comment') { if(is_array($list)) foreach($list as $k => $value) { include template('home/space_comment_li'); } } elseif($op == 'getfriendgroup') { ?>
<?php echo $group;?>
<?php } elseif($op == 'getfriendname') { ?>
<?php echo $groupname;?>

<?php } elseif($op == 'share') { if(is_array($list)) foreach($list as $value) { include template('home/space_share_li'); } } elseif($op == 'album') { ?>

<table cellspacing="0" cellpadding="0" width="100%" class="imgl">
<tr><?php $i = 0;?><?php if(is_array($piclist)) foreach($piclist as $key => $value) { $i++;?><td valign="bottom" id="image_td_<?php echo $value['picid'];?>" width="20%"><img src="<?php echo $value['pic'];?>" alt="" width="90" onclick="insertImage('<?php echo $value['bigpic'];?>');" class="cur1" /></td>
<?php if(($key+1)%5==0 && count($piclist) != $key+1) { ?></tr><tr><?php } } if(($imgpad = $i % 5) > 0) { echo str_repeat('<td></td>', 5 - $imgpad);; } ?>
</tr>
</table>
<?php if($multi) { ?><div class="pgs cl mtm"><?php echo $multi;?></div><?php } } elseif($op == 'getreward') { if($rule['credit'] || $rule['experience']) { ?>
<div class="popupmenu_layer">
<p><?php echo $rule['rulename'];?></p>
<p class="btn_line">
<?php if($rule['credit']) { ?>积分 <strong>+<?php echo $rule['credit'];?></strong> <?php } if($rule['experience']) { ?>经验 <strong>+<?php echo $rule['experience'];?></strong> <?php } ?>
</p>
<?php if($rule['cyclenum']) { ?>
<p>
本周期内，您还有 <?php echo $rule['cyclenum'];?> 次机会
</p>
<?php } ?>
</div>
<?php } } elseif($op == 'district') { ?>
<?php echo $html;?>
<?php } elseif($op == 'createalbum') { ?>
<?php echo $albumid;?>
<?php } include template('common/footer'); ?>