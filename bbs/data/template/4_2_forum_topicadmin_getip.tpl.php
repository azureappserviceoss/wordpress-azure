<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('topicadmin_getip');?><?php include template('common/header'); ?><b><?php echo $member['useip'];?><?php if($member['port']) { ?>:<?php echo $member['port'];?><?php } ?></b> <?php echo $member['iplocation'];?>
<?php if($_G['group']['allowviewip']) { ?>
<br /><a href="admin.php?action=members&amp;operation=ipban&amp;ip=<?php echo $member['useip'];?>&amp;frames=yes" target="_blank" class="xg2">禁止此 IP</a>
<a href="admin.php?action=members&amp;operation=search&amp;regip=<?php echo $member['useip'];?>&amp;submit=yes&amp;frames=yes" target="_blank" class="xg2">此 IP 下用户</a>
<?php } include template('common/footer'); ?>