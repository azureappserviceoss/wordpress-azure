<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<div class="bm bw0 mdcp">
<h1 class="mt">管理日志</h1>
<div class="exfm">
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=log">
<input type="hidden" name="action" value="log">
<table cellspacing="0" cellpadding="0">
<tr>
<th width="15%">关键字:</th>
<td width="45%"><input type="text" name="keyword" value="<?php echo $keyword;?>" size="20" class="px" /></td>
<th width="15%">每页显示条数:</th>
<td width="45%"><input type="text" name="lpp" value="<?php echo $lpp;?>" size="20" class="px" /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="3"><button type="submit" class="pn" name="submit" id="searchsubmit" value="true"><strong>查找</strong></button></td>
</tr>
</table>
</form>
</div>
<?php if(!empty($loglist)) { ?>
<h2 class="mtm mbm">日志列表</h2>
<table id="list_modcp_logs" cellspacing="0" cellpadding="0" class="dt">
<thead>
<tr>
<th width="12%">时间</th>
<th width="15%">用户名</th>
<th width="15%">IP</td>
<th width="15%">操作</th>
<th width="13%">版块</th>
<th width="30%">其他</th>
</tr>
</thead>
<?php if($loglist) { if(is_array($loglist)) foreach($loglist as $log) { ?><tr>
<td><?php echo $log['1'];?></td>
<td><span class="xi2"><?php echo $log['2'];?></span><br /><span class="xg1"><?php if($log['3'] == 1) { ?> 论坛管理员 <?php } elseif($log['3'] == 2) { ?> 超级版主 <?php } elseif($log['3'] == 3) { ?> 版主 <?php } else { ?> GID <?php echo $log['3'];?> <?php } ?></span></td>
<td><?php echo $log['4'];?></td>
<td><?php echo $log['5'];?> <br /><?php echo $log['6'];?></td>
<td class="xi2"><?php echo $log['7'];?></td>
<td><?php echo $log['8'];?></td>
</tr>
<?php } } else { ?>
<tr><td colspan="6"><p class="emp">抱歉，没有找到匹配结果</p></td></tr>
<?php } ?>
</table>
<?php if(!empty($multipage)) { ?><div class="pgs cl mtm"><?php echo $multipage;?></div><?php } } ?>
</div>