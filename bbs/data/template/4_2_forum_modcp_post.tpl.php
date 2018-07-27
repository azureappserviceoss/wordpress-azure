<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<div class="bm bw0 mdcp">
<h1 class="mt">主题管理</h1>
<ul class="tb cl">
<li><a href="<?php echo $cpscript;?>?mod=modcp&action=thread&op=thread<?php echo $forcefid;?>" hidefocus="true">版块主题</a></li>
<li class="a"><a href="<?php echo $cpscript;?>?mod=modcp&action=thread&op=post<?php echo $forcefid;?>" hidefocus="true">帖子管理</a></li>
<li><a href="<?php echo $cpscript;?>?mod=modcp&action=recyclebin<?php echo $forcefid;?>" hidefocus="true">主题回收站</a></li>
<li><a href="<?php echo $cpscript;?>?mod=modcp&action=recyclebinpost<?php echo $forcefid;?>" hidefocus="true">回帖回收站</a></li>
</ul>
<script src="<?php echo $_G['setting']['jspath'];?>calendar.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>&op=<?php echo $op;?>">
<input type="hidden" name="do" value="search">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
<div class="exfm">
<table cellspacing="0" cellpadding="0">
<tr>
<th width="15%">版块选择:</th>
<td width="35%">
<span class="ftid">
<select name="fid" id="fid" class="ps" width="168">
<option value="">请选择版块</option><?php if(is_array($modforums['list'])) foreach($modforums['list'] as $id => $name) { ?><option value="<?php echo $id;?>" <?php if($id == $_G['fid']) { ?>selected<?php } ?>><?php echo $name;?></option>
<?php } ?>
</select>
</span>
</td>
<th width="15%">帖子类型:</th>
<td width="35%">
<span class="ftid">
<select name="threadoption" id="threadoption" class="ps" width="168">
<option value="0" <?php echo $threadoptionselect['0'];?>>全部</option>
<option value="1" <?php echo $threadoptionselect['1'];?>>主题首帖</option>
<option value="2" <?php echo $threadoptionselect['2'];?>>主题回复帖</option>
</select>
</span>
</td>
</tr>
<tr>
<th>帖子作者:</th>
<td><input type="text" name="users" class="px" size="20" value="<?php echo $result['users'];?>" style="width: 180px"/></td>
<th>时间范围:</th>
<td><input type="text" name="starttime" class="px" size="10" value="<?php echo $result['starttime'];?>" onclick="showcalendar(event, this)"/> 至
<?php if($_G['adminid'] == 1) { ?>
<input type="text" name="endtime" class="px" size="10" value="<?php echo $result['endtime'];?>" onclick="showcalendar(event, this)"/>
<?php } else { ?>
<input type="text" name="endtime" class="px" size="10" value="<?php echo $result['endtime'];?>" readonly="readonly" disabled="disabled" />
<?php if($_G['adminid'] == 2) { ?>
<br />您只能操作最近 2 周的帖子
<?php } elseif($_G['adminid'] == 3) { ?>
<br />您只能操作最近 1 周的帖子
<?php } } ?>
 </td>
</tr>
<tr>
<th>内容关键字:</th>
<td><input type="text" name="keywords" class="px" size="20" value="<?php echo $result['keywords'];?>" style="width: 180px"/></td>
<th>发帖 IP:</th>
<td><input type="text" name="useip" class="px" value="<?php echo $result['useip'];?>" style="width: 180px" /></td>
</tr>
<?php if($posttableselect) { ?>
<tr>
<th>帖子分表:</th>
<td colspan="3"><span class="ftid"><?php echo $posttableselect;?></span></td>
</tr>
<?php } ?>
<tr>
<td>&nbsp;</td>
<td colspan="3">
<button type="submit" name="searchsubmit" id="searchsubmit" class="pn" value="true"><strong>提交</strong></button>
</td>
</tr>
</table>
</div>
</form>
<?php if($error == 1) { ?>
<p class="xi1">搜索条件不足！您至少应当在 关键字，帖子作者或者发帖 IP 当中设置一个搜索的条件</p>
<?php } elseif($error == 2) { ?>
<p class="xi1">时间范围错误！版主只能删除近 1 周的帖子，超级版主可以删除 2 周内的帖子，请重新选择开始时间</p>
<?php } elseif($error == 3) { ?>
<p class="xi1">您输入的关键字不合法！每个关键字至少由 2 个汉字或者 4 个英文字符组成</p>
<?php } elseif($error == 4) { ?>
<p class="xi1">抱歉，您无权使用批量删帖功能</p>
<?php } elseif($do=='list' && empty($error)) { ?>
<h2 class="mtm mbm">当前版块: <a href="forum.php?mod=forumdisplay&amp;fid=<?php echo $_G['fid'];?>" target="_blank" class="xi2"><?php echo $_G['forum']['name'];?></a>, 共搜索出结果 <strong><?php echo $total;?></strong> 条</h2>
<?php if($postlist) { ?>
<div id="threadlist" class="tl">
<form method="post" autocomplete="off" name="moderate" id="moderate" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>&op=<?php echo $op;?>">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="fid" value="<?php echo $_G['fid'];?>" />
<input type="hidden" name="do" value="delete" />
<input type="hidden" name="posttableid" value="<?php echo $posttableid;?>" />
<table cellspacing="0" cellpadding="0">
<tr class="th">
<?php if($_G['group']['allowmassprune']) { ?><td width="40">&nbsp;</td><?php } ?>
<th>&nbsp;</th>
<td class="frm">版块</td>
<td class="by">作者</td>
</tr><?php if(is_array($postlist)) foreach($postlist as $post) { ?><tr>
<?php if($_G['group']['allowmassprune']) { ?><td><input type="checkbox" name="delete[]" class="pc" value="<?php echo $post['pid'];?>" /></td><?php } ?>
<th>
主题: &nbsp;<a target="_blank" href="forum.php?mod=redirect&amp;goto=findpost&amp;pid=<?php echo $post['pid'];?>&amp;ptid=<?php echo $post['tid'];?><?php if($post['invisible'] == -2) { ?>&amp;modthreadkey=<?php echo $post['modthreadkey'];?><?php } ?>"><?php echo $post['tsubject'];?></a><br />
<span class="xg1"><?php echo $post['message'];?></span>
</th>
<td class="frm">
<a href="forum.php?mod=forumdisplay&amp;fid=<?php echo $post['fid'];?>"><?php echo $post['forum'];?></a>
</td>
<td class="by">
<cite>
<?php if($post['authorid'] && $post['author']) { ?>
<a href="home.php?mod=space&amp;uid=<?php echo $post['authorid'];?>" target="_blank"><?php echo $post['author'];?></a>
<?php } else { ?>
<a href="home.php?mod=space&amp;uid=<?php echo $post['authorid'];?>" target="_blank">匿名</a>
<?php } ?>
</cite>
<em><?php echo $post['dateline'];?></em>
</td>
</tr>
<?php } ?>

<tr class="bw0_all">
<td colspan="<?php if($_G['group']['allowmassprune']) { ?>4<?php } else { ?>3<?php } ?>" class="ptm">
<?php if($multipage) { ?><?php echo $multipage;?><?php } if($postlist && $_G['group']['allowmassprune']) { ?>
<label for="chkall"><input type="checkbox" name="chkall" id="chkall" class="pc" onclick="checkall(this.form, 'delete')" />删?</label>
<button type="submit" name="deletesubmit" id="deletesubmit" class="pn" value="true"><strong>删除</strong></button>
<label for="nocredit"><input type="checkbox" name="nocredit" id="nocredit" class="pc" value="1" checked="checked" />不更新用户积分</label>
<?php } ?>
</td>
</tr>
</table>
</form>
</div>
<?php } } ?>
</div>
<script type="text/javascript" reload="1">
simulateSelect('fid');
simulateSelect('threadoption');
simulateSelect('posttableid');
</script>