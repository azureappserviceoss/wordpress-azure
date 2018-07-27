<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<div class="bm bw0 mdcp">
<h1 class="mt">主题管理</h1>
<ul class="tb cl">
<li><a href="<?php echo $cpscript;?>?mod=modcp&action=thread&op=thread<?php echo $forcefid;?>" hidefocus="true">版块主题</a></li>
<li><a href="<?php echo $cpscript;?>?mod=modcp&action=thread&op=post<?php echo $forcefid;?>" hidefocus="true">帖子管理</a></li>
<li><a href="<?php echo $cpscript;?>?mod=modcp&action=recyclebin<?php echo $forcefid;?>" hidefocus="true">主题回收站</a></li>
<li class="a"><a href="<?php echo $cpscript;?>?mod=modcp&action=recyclebinpost<?php echo $forcefid;?>" hidefocus="true">回帖回收站</a></li>
</ul>
<script src="<?php echo $_G['setting']['jspath'];?>calendar.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<div class="datalist">
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>&op=search">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
<div class="exfm">
<table cellspacing="0" cellpadding="0">
<tr>
<th width="15%">版块选择:</th>
<td width="35%">
<span class="ftid">
<select name="fid" id="fid" class="ps" width="168">
<option value="">请选择版块</option><?php if(is_array($modforums['list'])) foreach($modforums['list'] as $id => $name) { if($modforums['recyclebins'][$id]) { ?>
<option value="<?php echo $id;?>" <?php if($id == $_G['fid']) { ?>selected<?php } ?>><?php echo $name;?></option>
<?php } } ?>
</select>
</span>
</td>
<th>内容关键字:</th>
<td><input type="text" name="keywords" class="px" size="20" value="<?php echo $result['keywords'];?>" style="width: 180px"/></td>
</tr>
<tr>
<th>帖子作者:</th>
<td><input type="text" name="users" class="px" size="20" value="<?php echo $result['users'];?>" style="width: 180px"/></td>
<th>发表时间范围:</th>
<td><input type="text" name="starttime" class="px" size="10" value="<?php echo $result['starttime'];?>" onclick="showcalendar(event, this);" /> 至 <input type="text" name="endtime" class="px" size="10" value="<?php echo $result['endtime'];?>" onclick="showcalendar(event, this);" /></td>
</tr>
<?php if($posttableselect) { ?>
<tr>
<th width="10%">分表：</th>
<td>
<span class="ftid">
<?php echo $posttableselect;?>
</span>
</td>
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
</div>

<?php if($_G['fid']) { ?>
<h2 class="mtm mbm">当前版块: <a href="forum.php?mod=forumdisplay&amp;fid=<?php echo $_G['fid'];?>" target="_blank" class="xi2"><?php echo $_G['forum']['name'];?></a></h2>
<?php if($postlist) { ?>
<div id="threadlist" class="tl">
<form method="post" autocomplete="off" name="moderate" id="moderate" action="<?php echo $cpscript;?>?mod=modcp&fid=<?php echo $_G['fid'];?>&action=<?php echo $_GET['action'];?>">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="op" value="" />
<input type="hidden" name="oldop" value="<?php echo $op;?>" />
<input type="hidden" name="dosubmit" value="submit" />
<input type="hidden" name="posttableid" value="<?php echo $posttableid;?>" />
<table cellspacing="0" cellpadding="0">
<tr class="th">
<td class="o">&nbsp;</td>
<td>&nbsp;</td>
<td class="by">作者</td>
<?php if($_G['forum']['ismoderator'] && $_G['group']['allowviewip']) { ?>
<td class="num">IP</td>
<?php } ?>
<td class="by">HTML帖</td>
<td class="by">发布时间</td>
</tr><?php if(is_array($postlist)) foreach($postlist as $post) { ?><tbody id="<?php echo $post['pid'];?>">
<tr>
<td class="o"><input class="pc" type="checkbox" name="moderate[]" value="<?php echo $post['pid'];?>" /></td>
<td><a href="forum.php?mod=redirect&amp;goto=findpost&amp;pid=<?php echo $post['pid'];?>&amp;ptid=<?php echo $post['tid'];?>&amp;modthreadkey=<?php echo $post['modthreadkey'];?>" target="_blank"><?php echo $post['message'];?></a>
<?php if($post['attachment'] == 2) { ?>
<img src="<?php echo STATICURL;?>image/filetype/image_s.gif" alt="图片附件" align="absmiddle" />
<?php } elseif($post['attachment'] == 1) { ?>
<img src="<?php echo STATICURL;?>image/filetype/common.gif" alt="附件" align="absmiddle" />
<?php } ?>
</td>
<td class="by">
<?php if($post['authorid'] && $post['author']) { ?>
<a href="home.php?mod=space&amp;uid=<?php echo $post['authorid'];?>" target="_blank"><?php echo $post['author'];?></a>
<?php } else { ?>
<a href="home.php?mod=space&amp;uid=<?php echo $post['authorid'];?>" target="_blank">匿名</a>
<?php } ?>
</td>
<?php if($_G['forum']['ismoderator'] && $_G['group']['allowviewip']) { ?>
<td class="num"><?php echo $post['useip'];?><?php if($post['port']) { ?>:<?php echo $post['port'];?><?php } ?></td>
<?php } ?>
<td class="by">
<?php if($post['htmlon']) { ?>是<?php } else { ?>否<?php } ?>
</td>
<td class="by">
<?php echo $post['dateline'];?>
</td>
</tr>
</tbody>
<?php } ?>
<tbody>
<tr class="bw0_all">
<td><input class="pc" type="checkbox" onclick="checkall(this.form, 'moderate')" name="chkall" id="chkall"/></td>
<td colspan="5" class="ptm">
<?php if($multipage) { ?><?php echo $multipage;?><?php } if($_G['group']['allowclearrecycle']) { ?>
<button onclick="modthreads('delete')" class="pn"><strong>删除</strong></button>
<?php } ?>
<button onclick="modthreads('restore')" class="pn"><strong>恢复</strong></button>
</td>
</tr>
</tbody>
</table>
</form>
</div>
<?php } if(!$total) { ?>
<p class="emp">没有找到相关主题</p>
<?php } ?>
<script type="text/javascript">
function modthreads(operation) {
document.moderate.op.value = operation;
document.moderate.submit();
}
</script>

<?php } else { ?>
<p class="xi1">请选择版块进行管理</p>
<?php } ?>
</div>
<script type="text/javascript" reload="1">
simulateSelect('fid');
if($('posttableid')) {
simulateSelect('posttableid');
}
</script>