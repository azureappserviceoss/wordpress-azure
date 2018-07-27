<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<form method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=plugin&amp;id=myrepeats:memcp&amp;pluginop=add">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<p class="tbmu">添加马甲账号</p>
<table cellspacing="0" cellpadding="0" class="tfm" style="table-layout:fixed;margin-top:10px;">
<tbody>
<tr><td class="mtm pns">
<p>
<?php if(!$singleprem) { ?>
用户名
<?php if($permusers) { ?>
<select id="userselect" onchange="$('usernamenew').value = this.value" class="ps vm">
<option value="">选择马甲</option><?php if(is_array($permusers)) foreach($permusers as $user) { ?><option value="<?php echo $user;?>"><?php echo $user;?></option>
<?php } ?>
</select>
<?php } ?>
<input name="usernamenew" id="usernamenew" type="text" class="px vm" value="<?php echo $username;?>" style="width:100px" tabindex="1" />&nbsp;
<?php } else { ?>
用户名 <select name="usernamenew" id="usernamenew" class="ps vm"><?php if(is_array($permusers)) foreach($permusers as $user) { ?><option value="<?php echo $user;?>"<?php if($user == $username) { ?> selected="selected"<?php } ?>><?php echo $user;?></option>
<?php } ?>
</select>
<?php } ?>
密码 <input type="password" name="passwordnew" class="px vm" style="width:100px" tabindex="2" />
<select name="questionidnew" tabindex="3" onchange="if(this.value > 0) {$('answernew').style.display='';} else {$('answernew').style.display='none';}" class="ps vm">
<option value="0">安全提问</option>
<option value="1">母亲的名字</option>
<option value="2">爷爷的名字</option>
<option value="3">父亲出生的城市</option>
<option value="4">您其中一位老师的名字</option>
<option value="5">您个人计算机的型号</option>
<option value="6">您最喜欢的餐馆名称</option>
<option value="7">驾驶执照的最后四位数字</option>
</select>
<span id="answernew" style="display:none">回答 <input type="text" name="answernew" class="px vm" style="width:100px" class="txt" tabindex="4" /></span>
<br /><br />
<p>
备　注 <input type="text" name="commentnew" class="px vm" size="40" tabindex="5" />
<button type="submit" name="adduser" class="pn vm" value="yes" ><span>添加</span></button>
</p>
</td></tr>
</tbody>
</table>
</form>
<form method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=plugin&amp;id=myrepeats:memcp&amp;pluginop=update">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<?php if($repeatusers) { ?>
<table cellspacing="0" cellpadding="0" class="dt mtm">
<thead class="alt">
<tr><td width="40"></td><td width="140">用户名</td><td>备注</td><td width="120">最后切换时间</td></tr>
</thead><?php if(is_array($repeatusers)) foreach($repeatusers as $user) { ?><tr>
<td><input name="delete[]" type="checkbox" class="pc" value="<?php echo $user['username'];?>" /></td>
<td><b><?php if(!$user['locked']) { ?><a href="plugin.php?id=myrepeats:switch&amp;username=<?php echo $user['usernameenc'];?>&amp;formhash=<?php echo FORMHASH;?>"><?php echo $user['username'];?></a></b><?php } else { ?><?php echo $user['username'];?> (被管理员锁定)<?php } ?></td>
<td><input name="comment[<?php echo $user['username'];?>]" class="px" value="<?php echo $user['comment'];?>" size="40" /></td>
<td><?php if($user['lastswitch']) { ?><?php echo $user['lastswitch'];?><?php } else { ?>尚未使用<?php } ?></td>
</tr>
<?php } ?>
<tr class="bw0_all"><td><label for="chkall"><input class="pc" type="checkbox" id="chkall" name="chkall" onclick="checkall(this.form);" />删?</label></td>
<td class="mtm pns"><button type="submit" class="pn" name="updateuser" value="yes" ><span>提交</span></button></td></tr>
</table>
<?php } ?>
</form>