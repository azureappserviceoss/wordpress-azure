<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<div class="bm bw0 mdcp">
<h1 class="mt">管理举报</h1>
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>">
<input type="hidden" name="do" value="search">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
<div class="exfm">
<table cellspacing="0" cellpadding="0">
<tr>
<th width="15%">版块选择:</th>
<td width="35%">
<span class="ftid">
<select name="fid" id="fid" width="168" class="ps">
<option value="">请选择版块</option><?php if(is_array($modforums['list'])) foreach($modforums['list'] as $id => $name) { ?><option value="<?php echo $id;?>" <?php if($id == $_G['fid']) { ?>selected<?php } ?>><?php echo $name;?></option>
<?php } ?>
</select>
</span>
</td>
<th width="15%">每页显示举报数:</th>
<td width="45%"><input type="text" name="lpp" value="<?php echo $lpp;?>" size="20" class="px" /></td>
</tr>
<tr>
<td></td>
<td colspan="3"><button value="true" id="searchsubmit" name="submit" class="pn" type="submit"><strong>提交</strong></button></td>
</tr>
</table>
</div>
</form>
<?php if(!empty($reportlist)) { ?>
<h2 class="mtm mbm">等待处理的举报</h2>
<form id="reportform" method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=report&fid=<?php echo $_G['fid'];?>">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
<table id="list_modcp_logs" cellspacing="0" cellpadding="0" class="dt">
<thead>
<tr>
<th></th>
<th>内容</th>
<th>举报者</th>
<th><?php if($report_reward['max'] != $report_reward['min']) { ?>奖惩<?php } ?></th>
</tr>
</thead><?php if(is_array($reportlist)) foreach($reportlist as $report) { ?><tr>
<td>
<input id="report_<?php echo $report['id'];?>" type="checkbox" class="checkbox" name="reportids[]" value="<?php echo $report['id'];?>" />
<input id="reportuids_<?php echo $report['id'];?>" type="hidden" name="reportuids[<?php echo $report['id'];?>]" value="<?php echo $report['uid'];?>" />
</td>
<td><strong>举报帖子:</strong><a href="<?php echo $report['url'];?>" target="_blank"><?php echo $report['url'];?></a><br><strong>举报理由:</strong><?php echo $report['message'];?><br><strong>举报时间:</strong><?php echo $report['dateline'];?></td>
<td><a href="home.php?mod=space&amp;uid=<?php echo $report['uid'];?>"><?php echo $report['username'];?></a></td>
<td><?php if($report_reward['max'] != $report_reward['min']) { ?><?php echo $_G['setting']['extcredits'][$curcredits]['title'];?>:&nbsp;<select name="creditsvalue[<?php echo $report['id'];?>]"><?php echo $rewardlist;?></select><br /><br />留言:&nbsp;<input type="text" name="msg[<?php echo $report['id'];?>]" class="px" value="" size="20" /><?php } ?></td>
</tr>
<?php } ?>
<tr>
<td><label for="chkall"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkall($('reportform'), 'report')" />全选</label></td>
<td colspan="3"><button type="submit" class="pn" name="reportsubmit" id="reportsubmit" value="true"><strong>处理选中</strong></button></td>
</tr>
</table>
</form>
<?php if(!empty($multipage)) { ?><div class="pgs cl mtm"><?php echo $multipage;?></div><?php } } else { ?>
没有新的举报或没有选择版块
<?php } ?>
</div>