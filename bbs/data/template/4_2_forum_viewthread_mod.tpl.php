<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('viewthread_mod');?><?php include template('common/header'); if(empty($_GET['infloat'])) { ?>
<div id="pt" class="bm cl">
<div class="z"><a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em> <?php echo $navigation;?></div>
</div>
<div id="ct" class="wp cl">
<div class="mn">
<div class="bm bw0">
<?php } ?>

<div class="f_c">
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">主题操作记录</em>
<span>
<?php if(!empty($_GET['infloat'])) { ?><a href="javascript:;" class="flbc" onclick="hideWindow('<?php echo $_GET['handlekey'];?>')" title="关闭">关闭</a><?php } ?>
</span>
</h3>
<div class="c floatwrap">
<table class="list" cellspacing="0" cellpadding="0">
<thead>
<tr>
<td>操作者</td>
<td>时间</td>
<td>操作</td>
<td>有效期</td>
</tr>
</thead><?php if(is_array($loglist)) foreach($loglist as $log) { ?><tr>
<td><?php if($log['uid']) { ?><a href="home.php?mod=space&amp;uid=<?php echo $log['uid'];?>" target="_blank"><?php echo $log['username'];?></a><?php } else { ?>任务系统<?php } ?></td>
<td><?php echo $log['dateline'];?></td>
<td <?php echo $log['status'];?>><?php echo $modactioncode[$log['action']];?><?php if($log['magicid']) { ?>(<?php echo $log['magicname'];?>)<?php } if($log['action'] == 'REB') { ?>到 <?php echo $log['reason'];?><?php } ?>
</td>
<td <?php echo $log['status'];?>><?php if($log['expiration']) { ?><?php echo $log['expiration'];?><?php } elseif(in_array($log['action'], array('STK', 'HLT', 'DIG', 'CLS', 'OPN'))) { ?>永 久<?php } ?></td>
</tr>
<?php } ?>
</table>
</div>
</div>

<?php if(empty($_GET['infloat'])) { ?>
</div>
</div>
</div>
<?php } include template('common/footer'); ?>