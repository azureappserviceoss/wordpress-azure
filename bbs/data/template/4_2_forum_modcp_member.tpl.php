<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<div class="bm bw0 mdcp">
<?php if($op == 'edit' || $op == 'ban') { ?>
<h1 class="mt"><?php if($op == 'edit') { ?>编辑用户<?php } else { ?>禁止用户<?php } ?></h1>
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>&op=<?php echo $op;?>">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
<div class="exfm">
<table cellspacing="0" cellpadding="0">
<caption>
<?php if(!empty($error)) { if($error == 1) { ?>
请首先输入用户名或者 UID 搜索用户，然后进行下一步。搜索 UID 比搜索用户名速度更快且准确
<?php } elseif($error == 2) { ?>
该用户不存在或被冻结，请重新输入
<?php } elseif($error == 3) { ?>
管理面板无权操作该用户
<?php if($_G['adminid'] == 1) { ?>
, <a href="admin.php?action=members&amp;operation=search&amp;username=<?php echo $usernameenc;?>&amp;submit=yes&amp;frames=yes" target="_blank" class="xi2"><u>请点击这里进入管理后台继续操作</u></a>
<?php } } } ?>
</caption>
<tr>
<th width="15%">用户名:</th>
<td width="85%"><input type="text" name="username" class="px" value="" size="20" /></td>
</tr>
<tr>
<th>UID:</th>
<td><input type="text" name="uid" class="px" value="" size="20" /> [可选]</td>
</tr>
<tr>
<td>&nbsp;</td>
<td><button type="submit" name="submit" id="searchsubmit" class="pn" value="true"><strong>查找</strong></button></td>
</tr>
</table>
</div>
</form>
<?php } if($op == 'edit' && $member && !$error) { ?>
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>&op=<?php echo $op;?>" class="schresult">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
<input type="hidden" name="username" value="<?php echo $_GET['username'];?>">
<input type="hidden" name="uid" value="<?php echo $_GET['uid'];?>">
<table cellspacing="0" cellpadding="0" class="tfm">
<tr>
<th>&nbsp;</th>
<td>
<table width="100%">
<tr>
<td width="10%" rowspan="2" class="avt"><?php echo avatar($member['uid'], 'small');; ?></td>
<td>
<p><a href="home.php?mod=space&amp;uid=<?php echo $member['uid'];?>" target="_blank" class="xi2"><?php echo $member['username'];?></a></p>
<p>UID: <?php echo $member['uid'];?></p>
<p><label><input type="checkbox" name="clearavatar" class="pc" value="1" />删除头像</label></p>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<th>自我介绍</th>
<td><textarea name="bionew" class="pt" rows="4" cols="80"><?php echo $member['bio'];?></textarea></td>
</tr>
<tr>
<th>个人签名</th>
<td><textarea name="signaturenew" class="pt" rows="4" cols="80"><?php echo $member['signature'];?></textarea></td>
</tr>
<tr>
<th>&nbsp;</th>
<td><button type="submit" name="editsubmit" id="submit" class="pn" value="true"><strong>提交</strong></button></td>
</tr>
</table>
</form>
<?php } if($op == 'ban' && $member && !$error) { ?>
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>&op=<?php echo $op;?>" class="schresult">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
<input type="hidden" name="username" value="<?php echo $_GET['username'];?>">
<input type="hidden" name="uid" value="<?php echo $_GET['uid'];?>">
<table cellspacing="0" cellpadding="0" class="tfm">
<tr>
<th>&nbsp;</th>
<td>
<table width="100%">
<tr>
<td width="10%" rowspan="2" class="avt"><?php echo avatar($member['uid'], 'small');; ?></td>
<td>
<p><a href="home.php?mod=space&amp;uid=<?php echo $member['uid'];?>" target="_blank" class="xi2"><?php echo $member['username'];?></a></p>
<p>UID: <?php echo $member['uid'];?></p>
<p><?php if($member['groupid'] == 4) { ?>禁止发言<?php } elseif($member['groupid'] == 5) { ?>禁止访问<?php } else { ?>正常状态<?php } ?> <?php if($member['banexpiry']) { ?>( 有效期至 <?php echo $member['banexpiry'];?> )<?php } ?></p>
</td>
</tr>
</table>
</td>
</tr>
<?php if($clist) { ?>
<tr>
<th>违规记录</th>
<td style="padding-top: 0;">
<table cellspacing="0" cellpadding="0" class="dt">
<tr>
<td width="15%">操作行为</td>
<td width="15%">操作时间</td>
<td>操作理由</td>
<td width="15%">操作者</td>
</tr><?php if(is_array($clist)) foreach($clist as $crime) { ?><tbody id="<?php echo $crime['cid'];?>">
<tr>
<td>
<?php if($crime['action'] == 'crime_delpost') { ?>
删除帖子
<?php } elseif($crime['action'] == 'crime_warnpost') { ?>
警告帖子
<?php } elseif($crime['action'] == 'crime_banpost') { ?>
屏蔽帖子
<?php } elseif($crime['action'] == 'crime_banspeak') { ?>
禁止发言
<?php } elseif($crime['action'] == 'crime_banvisit') { ?>
禁止访问
<?php } elseif($crime['action'] == 'crime_banstatus') { ?>
锁定用户
<?php } elseif($crime['action'] == 'crime_avatar') { ?>
清除头像
<?php } elseif($crime['action'] == 'crime_sightml') { ?>
清除签名
<?php } elseif($crime['action'] == 'crime_customstatus') { ?>
清除自定义头衔
<?php } ?>
</td>
<td><?php echo dgmdate($crime[dateline]);?></td>
<td><?php echo $crime['reason'];?></td>
<td>
<a href="home.php?mod=space&amp;uid=<?php echo $crime['operatorid'];?>" class="xi2"><?php echo $crime['operator'];?></a>
</td>
</tr>
</tbody>
<?php } ?>
</table>
</td>
</tr>
<?php } ?>
<tr>
<th>变更为:</th>
<td>
<?php if($member['groupid'] == 4 || $member['groupid'] == 5) { ?>
<label for="bannew_0" class="lb"><input type="radio" name="bannew" id="bannew_0" value="0" checked="checked" class="pr" />正常状态</label>
<?php } if($member['groupid'] != 4 && $_G['group']['allowbanuser']) { ?><label for="bannew_4" class="lb"><input type="radio" name="bannew" id="bannew_4" class="pr" value="4" <?php if($member['groupid'] != 4 && $member['groupid'] != 5) { ?>checked="checked"<?php } ?> />禁止发言</label><?php } if($member['groupid'] != 5 && $_G['group']['allowbanvisituser']) { ?><label for="bannew_5" class="lb"><input type="radio" name="bannew" id="bannew_5" class="pr" value="5" <?php if($member['groupid'] != 4 && $member['groupid'] != 5 && !$_G['group']['allowbanuser']) { ?>checked="checked"<?php } ?> />禁止访问</label><?php } ?>
</td>
</tr>
<tr>
<th>期限:</th>
<td>
<p class="hasd cl">
<script src="<?php echo $_G['setting']['jspath'];?>calendar.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<input type="text" id="banexpirynew" name="banexpirynew" autocomplete="off" value="" class="px" tabindex="1" style="margin-right: 0; width: 100px;" />
<a href="javascript:;" class="dpbtn" onclick="showselect(this, 'banexpirynew', 1, 1)">^</a>
</p>
<p class="d">期限设置仅对禁止发言和禁止访问的操作有效</p>
</td>
</tr>
<tr>
<th valign="top">理由:</th>
<td><textarea name="reason" class="pt" rows="4" cols="80"><?php echo $member['signature'];?></textarea></td>
</tr>
<tr>
<th>&nbsp;</th>
<td><button type="submit" name="bansubmit" id="submit" class="pn" value="true"><strong>提交</strong></button></td>
</tr>
</table>
</form>
<?php } if($op == 'ipban') { ?>
<h1 class="mt">禁止 IP</h1>
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>&op=<?php echo $op;?>">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
<div class="exfm">
<table cellspacing="0" cellpadding="0">
<tr>
<th width="15%">新增:</th>
<td width="85%">
<input type="text" name="ip1new" class="px" value="<?php echo $iptoban['0'];?>" size="2" maxlength="3"/> .
<input type="text" name="ip2new" class="px" value="<?php echo $iptoban['1'];?>" size="2" maxlength="3" /> .
<input type="text" name="ip3new" class="px" value="<?php echo $iptoban['2'];?>" size="2" maxlength="3" /> .
<input type="text" name="ip4new" class="px" value="<?php echo $iptoban['3'];?>" size="2" maxlength="3" />
&nbsp;&nbsp;* 表示整个 IP 段，如 192.168.*.* 表示禁止所有以 192.168 开头的 IP
</td>
</tr>
<tr>
<th width="15%">期限:</th>
<td width="85%" class="hasd cl">
<script src="<?php echo $_G['setting']['jspath'];?>calendar.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<input type="text" id="validitynew" name="validitynew" autocomplete="off" value="" class="px" tabindex="1" style="width: 100px;" />
<a href="javascript:;" class="dpbtn" onclick="showselect(this, 'validitynew', 0, 1)">^</a>
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>
<button type="submit" name="ipbansubmit" id="submit" class="pn" value="true"><strong>提交</strong></button>
<?php if($adderror) { if($adderror == 1) { ?>
请输入标准 IP 地址
<?php } elseif($adderror == 2) { ?>
新增 IP 地址失败，该 IP 地址范围包含您目前的 IP
<?php } elseif($adderror == 3) { ?>
新增 IP 地址失败，该 IP 地址已经在禁止范围内
<?php } elseif($updatecheck || $deletecheck || $addcheck) { ?>
IP 地址更新成功，请继续操作
<?php } else { ?>
您只能编辑和删改自己添加的 IP 地址
<?php } } ?>
</td>
</tr>
</table>
</div>

<h2 class="mtm mbm">已禁止的 IP 列表</h2>
<table cellspacing="0" cellpadding="0" class="dt">
<thead>
<tr>
<th class="c">删除</th>
<th>IP 地址</th>
<th>地理位置</th>
<th>操作者</th>
<th>起始时间</th>
<th>结束时间</th>
</tr>
</thead>
<?php if($iplist) { if(is_array($iplist)) foreach($iplist as $ip) { ?><tr>
<td><input type="checkbox" name="delete[]" class="pc" value="<?php echo $ip['id'];?>" <?php echo $ip['disabled'];?>></td>
<td><?php echo $ip['theip'];?></td>
<td><?php echo $ip['location'];?></td>
<td><?php echo $ip['admin'];?></td>
<td><?php echo $ip['dateline'];?></td>
<td class="hasd cl">
<input type="text" id="expirationnew[<?php echo $ip['id'];?>]" name="expirationnew[<?php echo $ip['id'];?>]" autocomplete="off" value="<?php echo $ip['expiration'];?>" class="px" tabindex="1"/>
<a href="javascript:;" class="dpbtn" onclick="showselect(this, 'expirationnew[<?php echo $ip['id'];?>]', 0, 1)">^</a>
</td>
</tr>
<?php } ?>
<tr class="bw0_all">
<td><label for="chkall" onclick="checkall(this.form)"><input type="checkbox" name="chkall" id="chkall" class="pc" />全选</label></td>
<td colspan="5"><button type="submit" name="ipbansubmit" id="submit" class="pn" value="true"><strong>提交</strong></button></td>
</tr>
<?php } else { ?>
<tr><td colspan="6"><p class="emp">当前没有已禁止的 IP</p></td></tr>
<?php } ?>
</table>
</form>
<?php } ?>
</div>