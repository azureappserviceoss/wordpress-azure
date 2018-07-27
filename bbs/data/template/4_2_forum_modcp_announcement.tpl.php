<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); 
0
|| checktplrefresh('./template/mahjong/forum/modcp_announcement.htm', './template/mahjong/common/seditor.htm', 1513603593, '2', './data/template/4_2_forum_modcp_announcement.tpl.php', './template/mahjong', 'forum/modcp_announcement')
;?>
<div class="bm bw0 mdcp">
<?php if($op == 'edit') { ?>
<h1 class="mt">编辑公告</h1>
<?php } else { ?>
<h1 class="mt">公告</h1>
<?php } ?>
<div class="exfm">
<script src="<?php echo $_G['setting']['jspath'];?>calendar.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=announcement&op=<?php if($op == 'edit') { ?>edit<?php } else { ?>add<?php } ?>">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
<input type="hidden" name="id" value="<?php echo $announce['id'];?>">
<input type="hidden" name="displayorder" value="<?php echo $announce['displayorder'];?>">
<table cellspacing="0" cellpadding="0">
<tr>
<th width="15%">标题:</th>
<td width="35%"><input type="text" name="subject" value="<?php echo $announce['subject'];?>" class="px" /></td>
<th width="15%">公告类型:</th>
<td width="35%">
<span class="ftid">
<select name="type" id="type" change="changeinput($('type').value)" class="ps">
<option value="0" <?php echo $announce['checked']['0'];?>>文字类型</option>
<option value="1" <?php echo $announce['checked']['1'];?>>网址链接</option>
</select>
</span>
<script type="text/javascript">
function changeinput(v){
if(v == 0) {
$('annomessage').style.display = $('annomessage_editor').style.display = '';
$('anno_type_url').style.display = 'none';
} else {
$('annomessage').style.display = $('annomessage_editor').style.display = 'none';
$('anno_type_url').style.display = '';
}
}
</script>
</td>
</tr>
<tr>
<th width="15%">起始时间:</th>
<td width="35%" class="hasd">
<input type="text" onclick="showcalendar(event, this, false)" id="starttime" name="starttime" autocomplete="off" value="<?php echo $announce['starttime'];?>" class="px" tabindex="1" />
<a href="javascript:;" class="dpbtn" onclick="showselect(this, 'starttime', 1)">^</a>
</td>
<th width="15%">结束时间:</th>
<td width="35%" class="hasd cl">
<input type="text" onclick="showcalendar(event, this, false)" id="endtime" name="endtime" autocomplete="off" value="<?php echo $announce['endtime'];?>" class="px" tabindex="1" />
<a href="javascript:;" class="dpbtn" onclick="showselect(this, 'endtime', 1)">^</a>
</td>
</tr>
<tr>
<th>&nbsp;</th>
<td colspan="3">
<div class="tedt" id="annomessage_editor" <?php if($announce['checked']['1']) { ?> style="display:none"<?php } ?>>
<div class="bar"><?php $seditor = array('anno', array('bold', 'color', 'img', 'link'));?><script src="<?php echo $_G['setting']['jspath'];?>seditor.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<div class="fpd">
<?php if(in_array('bold', $seditor['1'])) { ?>
<a href="javascript:;" title="文字加粗" class="fbld"<?php if(empty($seditor['2'])) { ?> onclick="seditor_insertunit('<?php echo $seditor['0'];?>', '[b]', '[/b]');doane(event);"<?php } ?>>B</a>
<?php } if(in_array('color', $seditor['1'])) { ?>
<a href="javascript:;" title="设置文字颜色" class="fclr" id="<?php echo $seditor['0'];?>forecolor"<?php if(empty($seditor['2'])) { ?> onclick="showColorBox(this.id, 2, '<?php echo $seditor['0'];?>');doane(event);"<?php } ?>>Color</a>
<?php } if(in_array('img', $seditor['1'])) { ?>
<a id="<?php echo $seditor['0'];?>img" href="javascript:;" title="图片" class="fmg"<?php if(empty($seditor['2'])) { ?> onclick="seditor_menu('<?php echo $seditor['0'];?>', 'img');doane(event);"<?php } ?>>Image</a>
<?php } if(in_array('link', $seditor['1'])) { ?>
<a id="<?php echo $seditor['0'];?>url" href="javascript:;" title="添加链接" class="flnk"<?php if(empty($seditor['2'])) { ?> onclick="seditor_menu('<?php echo $seditor['0'];?>', 'url');doane(event);"<?php } ?>>Link</a>
<?php } if(in_array('quote', $seditor['1'])) { ?>
<a id="<?php echo $seditor['0'];?>quote" href="javascript:;" title="引用" class="fqt"<?php if(empty($seditor['2'])) { ?> onclick="seditor_menu('<?php echo $seditor['0'];?>', 'quote');doane(event);"<?php } ?>>Quote</a>
<?php } if(in_array('code', $seditor['1'])) { ?>
<a id="<?php echo $seditor['0'];?>code" href="javascript:;" title="代码" class="fcd"<?php if(empty($seditor['2'])) { ?> onclick="seditor_menu('<?php echo $seditor['0'];?>', 'code');doane(event);"<?php } ?>>Code</a>
<?php } if(in_array('smilies', $seditor['1'])) { ?>
<a href="javascript:;" class="fsml" id="<?php echo $seditor['0'];?>sml"<?php if(empty($seditor['2'])) { ?> onclick="showMenu({'ctrlid':this.id,'evt':'click','layer':2});return false;"<?php } ?>>Smilies</a>
<?php if(empty($seditor['2'])) { ?>
<script type="text/javascript" reload="1">smilies_show('<?php echo $seditor['0'];?>smiliesdiv', <?php echo $_G['setting']['smcols'];?>, '<?php echo $seditor['0'];?>');</script>
<?php } } if(in_array('at', $seditor['1']) && $_G['group']['allowat']) { ?>
<script src="<?php echo $_G['setting']['jspath'];?>at.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<a id="<?php echo $seditor['0'];?>at" href="javascript:;" title="@朋友" class="fat"<?php if(empty($seditor['2'])) { ?> onclick="seditor_menu('<?php echo $seditor['0'];?>', 'at');doane(event);"<?php } ?>>@朋友</a>
<?php } ?>
<?php echo $seditor['3'];?>
</div></div>
<div class="area">
<textarea name="message[0]" id="annomessage" class="pt" <?php if($announce['checked']['1']) { ?> style="display:none"<?php } ?> /><?php echo $announce['message'];?></textarea>
</div>
</div>
<input name="message[1]" id="anno_type_url" value="<?php echo $announce['message'];?>" class="px"<?php if($announce['checked']['0']) { ?> style="display:none"<?php } ?> />
</td>
</tr>
<tr>
<th>&nbsp;</th>
<td colspan="3">
<?php if($op == 'edit') { ?>
<button type="submit" name="submit" id="submit" class="pn" value="true"><strong>编辑</strong></button>
<button type="button" class="pn" onclick="location.href='<?php echo $cpscript;?>?mod=modcp&action=announcement'"><strong>返回</strong></button>
<?php } else { ?>
<button type="submit" name="submit" id="submit" class="pn" value="true"><strong>添加公告</strong></button>
<?php } if($edit_successed) { ?>
公告设置更新完毕，请继续操作<script type="text/JavaScript">setTimeout("window.location.replace('<?php echo $cpscript;?>?mod=modcp&action=announcement')", 2000);</script>
<?php } elseif($add_successed) { ?>
公告添加完毕，请继续操作
<?php } ?>
</td>
</tr>
</tbody>
</table>
</form>
</div>

<?php if($op != 'edit') { ?>
<h2 class="mtm mbm">公告列表</h2>
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=announcement&op=manage">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
<table id="list_announce" cellspacing="0" cellpadding="0" class="dt">
<thead>
<tr>
<th class="c">&nbsp;</th>
<th>顺序</th>
<th>作者</th>
<th>标题</th>
<th>公告类型</th>
<th>起始时间</th>
<th>结束时间</th>
<th>操作</th>
</tr>
</thead><?php if(is_array($annlist)) foreach($annlist as $ann) { ?><tr <?php echo $ann['disabled'];?>>
<td><input type="checkbox" name="delete[]" class="pc" value="<?php echo $ann['id'];?>" <?php echo $ann['disabled'];?> /></td>
<td><input type="text" name="order[<?php echo $ann['id'];?>]" class="px" value="<?php echo $ann['displayorder'];?>" size="3" <?php echo $ann['disabled'];?> /></td>
<td><?php echo $ann['author'];?></td>
<td><?php echo $ann['subject'];?></td>
<td><?php if($ann['type'] == 1) { ?>链接<?php } else { ?>文字<?php } ?></td>
<td><?php echo $ann['starttime'];?></td>
<td><?php echo $ann['endtime'];?></td>
<td><a href="<?php echo $cpscript;?>?mod=modcp&action=announcement&op=edit&id=<?php echo $ann['id'];?>" class="xi2">编辑</a></td>
</tr>
<?php } ?>
<tr class="bw0_all">
<td><label for="chkall" onclick="checkall(this.form)"><input type="checkbox" name="chkall" id="chkall" class="pc" />删?</label></td>
<td colspan="7">
<button type="submit" name="submit" id="submit" class="pn" value="true"><strong>提交</strong></button>
<?php if(!empty($delids)) { ?>
选定公告删除完毕，请继续操作
<?php } ?>
</td>
</tr>
</table>
</form>
<?php } ?>
</div>

<script type="text/javascript" reload="1">
simulateSelect('type');
</script>