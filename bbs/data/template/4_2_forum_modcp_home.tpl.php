<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<div class="bm bw0 mdcp">
<h1 class="mt">内部留言</h1>
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>" id="list_adminnote">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="op" value="addnote" />
<div class="exfm">
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td rowspan="2" width="75%"><textarea name="newmessage" class="pt" rows="5" style="width: 95%; height: 120px;"></textarea></td>
<td width="25%">
<ul>
<li>留言给:</li>
<li><label><input type="checkbox" name="newaccess[1]" class="pc" value="1" checked="checked" disabled="disabled" />论坛管理员</label></li>
<li><label><input type="checkbox" name="newaccess[2]" class="pc" value="1" checked="checked" />超级版主</label></li>
<li><label><input type="checkbox" name="newaccess[3]" class="pc" value="1" checked="checked" />版主</label></li>
</ul>
</td>
</tr>
<tr>
<td>
<p>有效期:
<label><input type="text" id="newexpiration" name="newexpiration" autocomplete="off" value="30" class="px" tabindex="1" size="2" /> 天</label>
</p>
</td>
</tr>
<tr>
<td colspan="2"><button type="submit" class="pn" name="submit" value="true"><strong>添加留言</strong></button></td>
</tr>
</table>
</div>
</form>

<h2 class="bbs pbm ptm">留言列表</h2>
<?php if($notelist) { ?>
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>" name="notelist" id="notelist">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="op" value="delete" />
<input type="hidden" name="notlistsubmit" value="yes" /><?php if(is_array($notelist)) foreach($notelist as $note) { ?><div class="um">
<p class="pbn"><span class="y">(有效期: <?php echo $note['expiration'];?> 天)</span><?php echo $note['checkbox'];?> <span class="xi2"><?php echo $note['admin'];?></span> <span class="xg1"><?php echo $note['dateline'];?></span></p>
<p><?php echo $note['message'];?></p>
</div>
<?php } ?>
<div class="um bw0 cl">
<input type="checkbox" name="ncheck" id="ncheck" class="pc" onclick="checkall($('notelist'), 'delete', 'ncheck')" /> <label for="ncheck">全选</label>
<button type="submit" name="submit" id="submit" class="pn" value="true"><strong>删除</strong></button>
</div>
</form>
<?php } else { ?>
<p class="emp">当前没有人留言</p>
<?php } ?>
</div>