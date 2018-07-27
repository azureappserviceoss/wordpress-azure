<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<div class="bm bw0 mdcp">
<h1 class="mt">用户权限</h1>
<div class="mbm">通常情况下，用户在版块的权限是根据他的用户组决定的，此处您可以限制某个用户在某版块的权限。<br />注意: 看帖是基本权限，一旦禁止, 其他权限会同时进行禁止。<br />图例说明: <img src="static/image/common/access_normal.gif" class="vm" /> 默认权限&nbsp;&nbsp;&nbsp;&nbsp;<img src="static/image/common/access_disallow.gif" class="vm" /> 强制禁止&nbsp;&nbsp;&nbsp;&nbsp;<img src="static/image/common/access_allow.gif" class="vm" />强制允许 </div>
<?php if($modforums['fids']) { ?>
<script type="text/javascript">
function chkallaccess(obj) {
$('new_post').checked
= $('new_post').disabled
= $('new_reply').checked
= $('new_reply').disabled
= $('new_postattach').checked
= $('new_postattach').disabled
= $('new_getattach').checked
= $('new_getattach').disabled
= $('new_getimage').checked
= $('new_getimage').disabled
= $('new_postimage').disabled
= obj.checked;
}

function disallaccess(obj) {
$('new_view').checked
= $('new_post').checked
= $('new_post').checked
= $('new_reply').checked
= $('new_postattach').checked
= $('new_getattach').checked
= $('new_getimage').checked
= $('new_postimage').disabled
= false;
$('customaccess').disabled
= $('new_view').disabled
= $('new_view').disabled
= $('new_post').disabled
= $('new_post').disabled
= $('new_reply').disabled
= $('new_postattach').disabled
= $('new_getattach').disabled
= $('new_getimage').disabled
= $('new_postimage').disabled
= obj.checked;
}

</script>
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="op" value="<?php echo $op;?>" id="operation" />
<div class="exfm">
<table cellspacing="0" cellpadding="0">
<?php if($adderror || $successed) { ?>
<tr>
<th>&nbsp;</th>
<td>
<span class="rq"> *
<?php if($successed) { ?>
用户权限更新成功, 请继续操作
<?php } elseif($adderror == 1) { ?>
此用户不存在或被冻结
<?php } elseif($adderror == 2) { ?>
抱歉，您没有权限操作管理人员或特殊用户！
<?php } elseif($adderror == 3) { ?>
管理员设置此用户某些权限为强制允许，您不能变更管理员的这些设置
<?php } ?>
</span>
</td>
</tr>
<?php } ?>
<tr>
<th width="15%">版块选择:</th>
<td width="80%">
<span class="ftid">
<select name="fid" id="fid" class="ps" width="108"><?php if(is_array($modforums['list'])) foreach($modforums['list'] as $id => $name) { ?><option value="<?php echo $id;?>" <?php if($id == $_G['fid']) { ?>selected="selected"<?php } ?>><?php echo $name;?></option>
<?php } ?>
</select>
</span>
</td>
</tr>
<tr>
<th>用户名:</th>
<td>
<input type="text" size="20" value="<?php echo $new_user;?>" name="new_user" class="px" /> &nbsp;&nbsp;
</td>
</tr>
<tr>
<th>权限变更:</th>
<td>
<label for="deleteaccess" class="lb"><input type="checkbox" value="1" name="deleteaccess" id="deleteaccess" onclick="disallaccess(this)" class="pc" />恢复默认</label>
<span id="customaccess">
<label for="new_view" class="lb"><input type="checkbox" value="-1" name="new_view" id="new_view" onclick="chkallaccess(this)" class="pc" />禁止查看主题</label>
<label for="new_post" class="lb"><input type="checkbox" value="-1" name="new_post" id="new_post" class="pc" />禁止发表主题</label>
<label for="new_reply" class="lb"><input type="checkbox" value="-1" name="new_reply" id="new_reply" class="pc" />禁止发表回复</label>
<label for="new_getattach" class="lb"><input type="checkbox" value="-1" name="new_getattach" id="new_getattach" class="pc" />禁止下载附件</label>
<label for="new_getimage" class="lb"><input type="checkbox" value="-1" name="new_getimage" id="new_getimage" class="pc" />禁止查看图片</label>
<label for="new_postattach" class="lb"><input type="checkbox" value="-1" name="new_postattach" id="new_postattach" class="pc" />禁止上传附件</label>
<label for="new_postimage" class="lb"><input type="checkbox" value="-1" name="new_postimage" id="new_postimage" class="pc" />禁止上传图片</label>
</span>
</td>
</tr>
<tr>
<td></td>
<td><button type="submit" class="pn" name="addsubmit" value="true"><strong>提交</strong></button></td>
</tr>
</table>
</div>
<script type="text/javascript">
<?php if(!empty($deleteaccess)) { ?>
var obj = $('deleteaccess');
obj.checked = true;
disallaccess(obj);
<?php } elseif(!empty($new_view)) { ?>
var obj = $('new_view');
obj.checked = true;
chkallaccess(obj);
<?php } ?>
</script>
</form>
<?php } ?>
<div class="ptm pbm cl">
<div class="y pns">
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>&op=<?php echo $op;?>">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
用户名: <input type="text" name="suser" class="px vm" value="<?php echo $suser;?>" onclick="this.value='';" />&nbsp;
<select name="fid" class="ps vm">
<option>全部版块</option>
<?php echo $forumlistall;?>
</select>&nbsp;
<button type="submit" name="searchsubmit" id="searchsubmit" class="pn vm" value="true"><strong>搜索</strong></button>
</form>
</div>
<h2>特殊用户</h2>
</div>

<table id="list_member" cellspacing="0" cellpadding="0" class="dt">
<thead>
<tr>
<th>会员</th>
<th>版块</th>
<th>浏览主题</th>
<th>发表主题</th>
<th>回复主题</th>
<th>下载附件</th>
<th>查看图片</th>
<th>上传附件</th>
<th>上传图片</th>
<th>操作时间</th>
<th>版主</th>
</tr>
</thead>
<?php if($list['data']) { if(is_array($list['data'])) foreach($list['data'] as $access) { ?><tr>
<td><?php if($users[$access['uid']] != '') { ?><a href="home.php?mod=space&amp;uid=<?php echo $access['uid'];?>" target="_blank" class="xi2"><?php echo $users[$access['uid']];?></a><?php } else { ?>UID <?php echo $access['uid'];?><?php } ?></td>
<td><?php echo $access['forum'];?></td>
<td><?php echo $access['allowview'];?></td>
<td><?php echo $access['allowpost'];?></td>
<td><?php echo $access['allowreply'];?></td>
<td><?php echo $access['allowgetattach'];?></td>
<td><?php echo $access['allowgetimage'];?></td>
<td><?php echo $access['allowpostattach'];?></td>
<td><?php echo $access['allowpostimage'];?></td>
<td><?php echo $access['dateline'];?></td>
<td><?php if($users[$access['adminuser']] != '') { ?><a href="home.php?mod=space&amp;uid=<?php echo $access['adminuser'];?>" target="_blank" class="xi2"><?php echo $users[$access['adminuser']];?></a><?php } else { ?>UID <?php echo $access['adminuser'];?><?php } ?></td>
</tr>
<?php } } else { ?>
<tr><td colspan="11"><p class="emp">当前没有特殊权限用户</p></td></tr>
<?php } ?>
</table>
<?php if(!empty($list['pagelink'])) { ?><div class="pgs cl mtm"><?php echo $list['pagelink'];?></div><?php } ?>
</div>
<script type="text/javascript" reload="1">
if($('fid')) {
simulateSelect('fid');
}
</script>