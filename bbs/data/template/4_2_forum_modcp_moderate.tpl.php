<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<script type="text/javascript">
modclickcount = 0;
function recountobj() {
modclickcount = 0;
var objform = $('moderate');
for(var i = 0; i < objform.elements.length; i++) {
if(objform.elements[i].name.match('moderate') && objform.elements[i].checked) {
modclickcount++;
}
}
$('modlayercount').innerHTML = modclickcount;
}
function modcheckall() {
var count = 0;
count = checkall($('moderate'), 'moderate', 'chkall');
$('modlayercount').innerHTML = count;
}
function toggle_post(id) {
var obj = $('list_note_' + id);
obj.style.display='block';
obj.style.height = obj.style.height == '55px' ? 'auto' : '55px' ;
}
 function modthreads(operation) {
var checked = 0;
var operation = !operation ? '' : operation;
var objform = $('moderate');
for(var i = 0; i < objform.elements.length; i++) {
if(objform.elements[i].name.match('moderate') && objform.elements[i].checked) {
checked = 1;
break;
}
}
if(!checked) {
alert('请先选择操作对象！');
} else {
$('moderate').modact.value = operation;
$('moderate').infloat.value = 'yes';
showWindow('mods', 'moderate', 'post');
}
}
</script>

<div class="bm bw0 mdcp">
<h1 class="mt">审核</h1>
<ul class="tb cl">
<?php if($_G['group']['allowmodpost']) { ?>
<li<?php if($op == 'threads') { ?> class="a"<?php } ?>><a href="<?php echo $cpscript;?>?mod=modcp&action=moderate&op=threads<?php echo $forcefid;?>" hidefocus="true">主题</a></li>
<li<?php if($op == 'replies') { ?> class="a"<?php } ?>><a href="<?php echo $cpscript;?>?mod=modcp&action=moderate&op=replies<?php echo $forcefid;?>" hidefocus="true">回复</a></li>
<?php } if($_G['group']['allowmoduser']) { ?>
<li<?php if($op == 'members') { ?> class="a"<?php } ?>><a href="<?php echo $cpscript;?>?mod=modcp&action=moderate&op=members" hidefocus="true">用户</a></li>
<?php } ?>
</ul>

<?php if($op == 'threads' || $op == 'replies') { ?>
<div class="exfm">
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>&op=<?php echo $op;?>">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
<?php if($modforums['fids']) { ?>
<table cellspacing="0" cellpadding="0">
<tr>
<th width="10%">版块选择: </th>
<th>
<span class="ftid">
<select name="fid" id="fid" width="124" class="ps">
<option value="0">全部</option><?php if(is_array($modforums['list'])) foreach($modforums['list'] as $id => $name) { ?><option value="<?php echo $id;?>" <?php if($id == $_G['fid']) { ?>selected<?php } ?>><?php echo $name;?></option>
<?php } ?>
</select>
</span>
</th>
<th width="10%">帖子范围: </th>
<td>
<span class="ftid">
<select name="filter" id="filter" width="124" class="ps">
<option value="0" <?php echo $filtercheck['0'];?>><?php if($op == 'replies') { ?>未审核回复<?php } else { ?>未审核主题<?php } ?></option>
<option value="-3" <?php echo $filtercheck['-3'];?>><?php if($op == 'replies') { ?>已忽略回复<?php } else { ?>已忽略主题<?php } ?></option>
</select>
</span>
</td>
<?php if($posttableselect) { ?>
<th width="10%">分表：</th>
<td>
<span class="ftid">
<?php echo $posttableselect;?>
</span>
</td>
<?php } ?>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="<?php if($posttableselect) { ?>5<?php } else { ?>3<?php } ?>"><button type="submit" name="submit" id="searchsubmit" class="pn" value="true"><strong>提交</strong></button></td>
</tr>
</table>
<?php } else { ?>
<p class="emp">抱歉，您没有管理任何版块的权限，无法执行此操作</p>
<?php } ?>
</form>
</div>

<?php if($updatestat) { ?><div class="ptm pbm">审核结果: <?php echo $modpost['validate'];?> 篇帖子审核通过，<?php echo $modpost['delete'];?> 篇帖子删除，<?php echo $modpost['ignore'];?> 篇帖子进入忽略列表等待审核</div><?php } if($postlist) { ?>
<form method="post" autocomplete="off" name="moderate" id="moderate" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>&op=<?php echo $op;?>" class="s_clear">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="fid" value="<?php echo $_G['fid'];?>" />
<input type="hidden" name="modact" value="" />
<input type="hidden" name="infloat" value="" />
<input type="hidden" name="dosubmit" value="yes" />
<input type="hidden" name="filter" value="<?php echo $filter;?>" />
<input type="hidden" name="posttableid" value="<?php echo $posttableid;?>" /><?php if(is_array($postlist)) foreach($postlist as $post) { ?><div class="um <?php echo swapclass('alt');; ?>" id="pid_<?php echo $post['id'];?>">
<p class="pbn">
<span class="y">
<a href="forum.php?mod=modcp&amp;action=<?php echo $_GET['action'];?>&amp;op=<?php echo $op;?>&amp;posttableid=<?php echo $posttableid;?>&amp;moderate[]=<?php echo $post['id'];?>&amp;modact=validate&amp;filter=<?php echo $filter;?>&amp;dosubmit=1" onclick="showWindow('mods', this.href)" class="xi2">通过</a><span class="pipe">|</span>
<a href="forum.php?mod=modcp&amp;action=<?php echo $_GET['action'];?>&amp;op=<?php echo $op;?>&amp;posttableid=<?php echo $posttableid;?>&amp;moderate[]=<?php echo $post['id'];?>&amp;modact=delete&amp;filter=<?php echo $filter;?>&amp;dosubmit=1" onclick="showWindow('mods', this.href)" class="xi2">删除</a><span class="pipe">|</span>
<a href="forum.php?mod=modcp&amp;action=<?php echo $_GET['action'];?>&amp;op=<?php echo $op;?>&amp;posttableid=<?php echo $posttableid;?>&amp;moderate[]=<?php echo $post['id'];?>&amp;modact=ignore&amp;filter=<?php echo $filter;?>&amp;dosubmit=1" onclick="showWindow('mods', this.href)" class="xi2">忽略</a><span class="pipe">|</span>
<a href="javascript:;" onclick="toggle_post(<?php echo $post['id'];?>);" class="xi2">展开</a>
</span>
<input type="checkbox" name="moderate[]" id="pidcheck_<?php echo $post['id'];?>" class="pc" value="<?php echo $post['id'];?>" onclick="recountobj()"/>
<a href="forum.php?mod=forumdisplay&amp;fid=<?php echo $post['fid'];?>" target="_blank" class="xi2 xw1"><?php echo $modforums['list'][$post['fid']];?></a><?php if(!empty($post['tsubject'])) { ?> &rsaquo; <span class="xw1"><?php echo $post['tsubject'];?></span><?php } if($post['subject'] && !$post['first']) { ?> &rsaquo; <span class="xw1"><?php echo $post['subject'];?></span><?php } ?>
</p>
<p class="pbn">
<span class="xi2"><?php echo $post['author'];?></span>
<span class="xg1">发表于 <?php echo $post['dateline'];?></span>
<div id="list_note_<?php echo $post['id'];?>" style="overflow: auto; overflow-x: hidden; height:55px; word-break: break-all;">
<?php echo $post['message'];?> <?php echo $post['attach'];?> <?php echo $post['sortinfo'];?>
</div>
</p>
</div>
<?php } if(!empty($multipage)) { ?><div class="pgs cl mtm"><?php echo $multipage;?></div><?php } ?>
<div class="um bw0 cl">
<label for="chkall"><input type="checkbox" class="pc" name="chkall" id="chkall" onclick="modcheckall()" />全选</label>
<button onclick="modthreads('validate'); return false;" class="pn"><strong>通过</strong></button>
<button onclick="modthreads('delete'); return false;" class="pn"><strong>删除</strong></button>
<button onclick="modthreads('ignore'); return false;" class="pn"><strong>忽略</strong></button>
<label>当前已选定 <span id="modlayercount">0</span> 个</label>
</div>
</form>
<?php } elseif($_G['fid']) { ?>
<p class="emp">抱歉，没有找到匹配结果</p>
<?php } } if($op == 'members') { ?>
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>&op=<?php echo $op;?>">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
<div class="filterform exfm">
<table cellspacing="0" cellpadding="0">
<tr>
<th width="10%">用户范围:</th>
<td width="90%">
<span class="ftid">
<select name="filter" id="filter" width="150" class="ps">
<option value="0" <?php echo $filtercheck['0'];?>>待审核的用户 ( <?php echo $count['0'];?> )</option>
<option value="1" <?php echo $filtercheck['1'];?>>已否决的用户 ( <?php echo $count['1'];?> )</option>
</select>
</span>
</td>
</tr>
<tr>
<th></th>
<td><button type="submit" class="pn" name="submit" id="searchsubmit" value="true"><strong>提交</strong></button></td>
</tr>
</table>
</div>
</form>
<?php if($memberlist) { ?>
<form method="post" autocomplete="off" name="moderate" id="moderate" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>&op=<?php echo $op;?>">
<input type="hidden" name="infloat" value="" />
<input type="hidden" name="modact" value="" />
<input type="hidden" name="dosubmit" value="yes" />
<input type="hidden" name="filter" value="<?php echo $filter;?>" />
<table cellspacing="0" cellpadding="0" class="dt">
<thead>
<tr>
<th class="c">&nbsp;</th>
<th>个人资料</th>
<th>注册原因</th>
<th>审核信息</th>
</tr>
</thead><?php if(is_array($memberlist)) foreach($memberlist as $member) { ?><tr id="pid_<?php echo $member['uid'];?>" class="<?php echo swapclass('alt'); ?>">
<td><input type="checkbox" name="moderate[]" id="pidcheck_<?php echo $member['uid'];?>" class="pc" value="<?php echo $member['uid'];?>" onclick="recountobj()" /></td>
<td valign="top">
<h5><?php echo $member['username'];?></h5>
<p>注册时间: <?php echo $member['regdate'];?></p>
<p>注册 IP: <?php echo $member['regip'];?></p>
<p>Email: <?php echo $member['email'];?></p>
<p class="mtn">
<a href="forum.php?mod=modcp&amp;action=<?php echo $_GET['action'];?>&amp;op=<?php echo $op;?>&amp;moderate[]=<?php echo $member['uid'];?>&amp;modact=validate&amp;filter=<?php echo $filter;?>&amp;dosubmit=1" onclick="showWindow('mods', this.href)">通过</a><span class="pipe">|</span>
<a href="forum.php?mod=modcp&amp;action=<?php echo $_GET['action'];?>&amp;op=<?php echo $op;?>&amp;moderate[]=<?php echo $member['uid'];?>&amp;modact=delete&amp;filter=<?php echo $filter;?>&amp;dosubmit=1" onclick="showWindow('mods', this.href)">删除</a><span class="pipe">|</span>
<a href="forum.php?mod=modcp&amp;action=<?php echo $_GET['action'];?>&amp;op=<?php echo $op;?>&amp;moderate[]=<?php echo $member['uid'];?>&amp;modact=ignore&amp;filter=<?php echo $filter;?>&amp;dosubmit=1" onclick="showWindow('mods', this.href)">否决</a>
</p>
</td>
<td valign="top"><?php echo $member['message'];?></td>
<td valign="top">
<p>提交次数: <?php echo $member['submittimes'];?></p>
<p>上次提交: <?php echo $member['submitdate'];?></p>
<p>上次审核者: <?php echo $member['admin'];?></p>
<p>上次审核时间: <?php echo $member['moddate'];?></p>
</td>
</tr>
<?php } ?>
</table>
<?php if(!empty($multipage)) { ?><div class="pgs cl mtm"><?php echo $multipage;?></div><?php } ?>
<div class="um bw0 cl">
<label for="chkall"><input type="checkbox" class="pc" name="chkall" id="chkall" onclick="modcheckall()"/>全选</label>
<button onclick="modthreads('validate'); return false;" class="pn"><strong>通过</strong></button>
<button onclick="modthreads('delete'); return false;" class="pn"><strong>删除</strong></button>
<button onclick="modthreads('ignore'); return false;" class="pn"><strong>否决</strong></button>
<label>当前已选定 <span id="modlayercount">0</span> 个</label>
</div>
</form>
<?php } else { ?>
<p class="emp">抱歉，没有找到匹配结果</p>
<?php } } ?>
</div>

<script type="text/javascript" reload="1">
if($('filter')) {
simulateSelect('filter');
}
if($('fid')) {
simulateSelect('fid');
}
if($('posttableid')) {
simulateSelect('posttableid');
}
</script>