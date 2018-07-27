<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>

<input type="hidden" name="selectsortid" size="45" value="<?php echo $_G['forum_selectsortid'];?>" />
<?php if($_G['forum_typetemplate']) { if($_G['forum']['threadsorts']['description'][$_G['forum_selectsortid']] || $_G['forum']['threadsorts']['expiration'][$_G['forum_selectsortid']]) { ?>
<div class="sinf bw0">
<dl>
<?php if($_G['forum']['threadsorts']['description'][$_G['forum_selectsortid']]) { ?>
<dt>发帖说明</dt>
<dd><?php echo $_G['forum']['threadsorts']['description'][$_G['forum_selectsortid']];?></dd>
<?php } if($_G['forum']['threadsorts']['expiration'][$_G['forum_selectsortid']]) { ?>
<dt><span class="rq">*</span>信息有效期</dt>
<dd>
<div class="ftid">
<select name="typeexpiration" tabindex="1" id="typeexpiration">
<option value="259200">3天</option>
<option value="432000">5天</option>
<option value="604800">7天</option>
<option value="2592000">1个月</option>
<option value="7776000">3个月</option>
<option value="15552000">半年</option>
<option value="31536000">1年</option>
</select>
</div>
<?php if($_G['forum_optiondata']['expiration']) { ?><span class="fb">有效期至: <?php echo $_G['forum_optiondata']['expiration'];?></span><?php } ?>
</dd>
<?php } ?>
</dl>
</div>
<?php } ?>
<?php echo $_G['forum_typetemplate'];?>

<?php } else { ?>
<table cellspacing="0" cellpadding="0" class="tfm">
<?php if($_G['forum']['threadsorts']['description'][$_G['forum_selectsortid']]) { ?>
<tr>
<th class="ptm pbm bbda">发帖说明</th>
<td class="ptm pbm bbda" colspan="2"><?php echo $_G['forum']['threadsorts']['description'][$_G['forum_selectsortid']];?></td>
</tr>
<?php } if($_G['forum']['threadsorts']['expiration'][$_G['forum_selectsortid']]) { ?>
<tr>
<th class="ptm pbm bbda">信息有效期</th>
<td class="ptm pbm bbda" colspan="2">
<div class="ftid">
<select name="typeexpiration" tabindex="1" id="typeexpiration">
<option value="259200">3天</option>
<option value="432000">5天</option>
<option value="604800">7天</option>
<option value="2592000">1个月</option>
<option value="7776000">3个月</option>
<option value="15552000">半年</option>
<option value="31536000">1年</option>
</select>
</div>
<?php if($_G['forum_optiondata']['expiration']) { ?>有效期至: <?php echo $_G['forum_optiondata']['expiration'];?><?php } ?>
</td>
</tr>
<?php } if(is_array($_G['forum_optionlist'])) foreach($_G['forum_optionlist'] as $optionid => $option) { ?><tr>
<th class="ptm pbm bbda"><?php if($option['required']) { ?><span class="rq">*</span><?php } ?><?php echo $option['title'];?></th>
<td class="ptm pbm bbda">
<div id="select_<?php echo $option['identifier'];?>">
<?php if(in_array($option['type'], array('number', 'text', 'email', 'calendar', 'image', 'url', 'range', 'upload', 'range'))) { if($option['type'] == 'calendar') { ?>
<script src="<?php echo $_G['setting']['jspath'];?>calendar.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<input type="text" name="typeoption[<?php echo $option['identifier'];?>]" id="typeoption_<?php echo $option['identifier'];?>" tabindex="1" size="<?php echo $option['inputsize'];?>" onchange="checkoption('<?php echo $option['identifier'];?>', '<?php echo $option['required'];?>', '<?php echo $option['type'];?>')" value="<?php echo $option['value'];?>" onclick="showcalendar(event, this, false)" <?php echo $option['unchangeable'];?> class="px"/>
<?php } elseif($option['type'] == 'image') { if(!($option['unchangeable'] && $option['value'])) { ?>
<button type="button" class="pn" onclick="uploadWindow(function (aid, url){sortaid_<?php echo $option['identifier'];?>_upload(aid, url)})"><em><?php if($option['value']) { ?>更新<?php } else { ?>上传<?php } ?></em></button>
<input type="hidden" name="typeoption[<?php echo $option['identifier'];?>][aid]" value="<?php echo $option['value']['aid'];?>" id="sortaid_<?php echo $option['identifier'];?>" />
<input type="hidden" name="sortaid_<?php echo $option['identifier'];?>_url" id="sortaid_<?php echo $option['identifier'];?>_url" />
<?php if($option['value']) { ?><input type="hidden" name="oldsortaid[<?php echo $option['identifier'];?>]" value="<?php echo $option['value']['aid'];?>" tabindex="1" /><?php } ?>
<input type="hidden" name="typeoption[<?php echo $option['identifier'];?>][url]" id="sortattachurl_<?php echo $option['identifier'];?>" <?php if($option['value']['url']) { ?>value="<?php echo $option['value']['url'];?>"<?php } ?> tabindex="1" />
<?php } ?>
<div id="sortattach_image_<?php echo $option['identifier'];?>" class="ptn">
<?php if($option['value']['url']) { ?>
<a href="<?php echo $option['value']['url'];?>" target="_blank"><img class="spimg" src="<?php echo $option['value']['url'];?>" alt="" /></a>
<?php } ?>
</div>
<script type="text/javascript" reload="1">
function sortaid_<?php echo $option['identifier'];?>_upload(aid, url) {
$('sortaid_<?php echo $option['identifier'];?>_url').value = url;
updatesortattach(aid, url, '<?php echo $_G['setting']['attachurl'];?>forum', '<?php echo $option['identifier'];?>');
}
</script>
<?php } else { ?>
<input type="text" name="typeoption[<?php echo $option['identifier'];?>]" id="typeoption_<?php echo $option['identifier'];?>" class="px" tabindex="1" size="<?php echo $option['inputsize'];?>" onBlur="checkoption('<?php echo $option['identifier'];?>', '<?php echo $option['required'];?>', '<?php echo $option['type'];?>'<?php if($option['maxnum']) { ?>, '<?php echo $option['maxnum'];?>'<?php } else { ?>, '0'<?php } if($option['minnum']) { ?>, '<?php echo $option['minnum'];?>'<?php } else { ?>, '0'<?php } if($option['maxlength']) { ?>, '<?php echo $option['maxlength'];?>'<?php } ?>)" value="<?php if($_G['tid']) { ?><?php echo $option['value'];?><?php } else { if($member_profile[$option['profile']]) { ?><?php echo $member_profile[$option['profile']];?><?php } else { ?><?php echo $option['defaultvalue'];?><?php } } ?>" <?php echo $option['unchangeable'];?> />
<?php } } elseif(in_array($option['type'], array('radio', 'checkbox', 'select'))) { if($option['type'] == 'select') { if(is_array($option['value'])) foreach($option['value'] as $selectedkey => $selectedvalue) { if($selectedkey) { ?>
<script type="text/javascript">
changeselectthreadsort('<?php echo $selectedkey;?>', <?php echo $optionid;?>, 'update');
</script>
<?php } else { ?>
<select tabindex="1" onchange="changeselectthreadsort(this.value, '<?php echo $optionid;?>');checkoption('<?php echo $option['identifier'];?>', '<?php echo $option['required'];?>', '<?php echo $option['type'];?>')" <?php echo $option['unchangeable'];?> class="ps">
<option value="0">请选择</option><?php if(is_array($option['choices'])) foreach($option['choices'] as $id => $value) { if(!$value['foptionid']) { ?>
<option value="<?php echo $id;?>"><?php echo $value['content'];?> <?php if($value['level'] != 1) { ?>&raquo;<?php } ?></option>
<?php } } ?>
</select>
<?php } } if(!is_array($option['value'])) { ?>
<select tabindex="1" onchange="changeselectthreadsort(this.value, '<?php echo $optionid;?>');checkoption('<?php echo $option['identifier'];?>', '<?php echo $option['required'];?>', '<?php echo $option['type'];?>')" <?php echo $option['unchangeable'];?> class="ps">
<option value="0">请选择</option><?php if(is_array($option['choices'])) foreach($option['choices'] as $id => $value) { if(!$value['foptionid']) { ?>
<option value="<?php echo $id;?>"><?php echo $value['content'];?> <?php if($value['level'] != 1) { ?>&raquo;<?php } ?></option>
<?php } } ?>
</select>
<?php } } elseif($option['type'] == 'radio') { ?>
<ul class="xl2"><?php if(is_array($option['choices'])) foreach($option['choices'] as $id => $value) { ?><li><label><input type="radio" name="typeoption[<?php echo $option['identifier'];?>]" id="typeoption_<?php echo $option['identifier'];?>" class="pr" tabindex="1" onclick="checkoption('<?php echo $option['identifier'];?>', '<?php echo $option['required'];?>', '<?php echo $option['type'];?>')" value="<?php echo $id;?>" <?php echo $option['value'][$id];?> <?php echo $option['unchangeable'];?> class="pr"> <?php echo $value;?></label></li>
<?php } ?>
</ul>
<?php } elseif($option['type'] == 'checkbox') { ?>
<ul class="xl2"><?php if(is_array($option['choices'])) foreach($option['choices'] as $id => $value) { ?><li><label><input type="checkbox" name="typeoption[<?php echo $option['identifier'];?>][]" id="typeoption_<?php echo $option['identifier'];?>" class="pc" tabindex="1" onclick="checkoption('<?php echo $option['identifier'];?>', '<?php echo $option['required'];?>', '<?php echo $option['type'];?>')" value="<?php echo $id;?>" <?php echo $option['value'][$id][$id];?> <?php echo $option['unchangeable'];?> class="pc"> <?php echo $value;?></label></li>
<?php } ?>
</ul>
<?php } } elseif(in_array($option['type'], array('textarea'))) { ?>
<textarea name="typeoption[<?php echo $option['identifier'];?>]" tabindex="1" id="typeoption_<?php echo $option['identifier'];?>" rows="<?php echo $option['rowsize'];?>" cols="<?php echo $option['colsize'];?>" onBlur="checkoption('<?php echo $option['identifier'];?>', '<?php echo $option['required'];?>', '<?php echo $option['type'];?>', 0, 0<?php if($option['maxlength']) { ?>, '<?php echo $option['maxlength'];?>'<?php } ?>)" <?php echo $option['unchangeable'];?> class="pt"><?php echo $option['value'];?></textarea>
<?php } ?>
<?php echo $option['unit'];?>
</div>				 
<?php if($option['maxnum'] || $option['minnum'] || $option['maxlength'] || $option['unchangeable'] || $option['description']) { ?>
<div class="d">
<?php if($option['maxnum']) { ?>
最大值 <?php echo $option['maxnum'];?>&nbsp;
<?php } if($option['minnum']) { ?>
最小值 <?php echo $option['minnum'];?>&nbsp;
<?php } if($option['maxlength']) { ?>
最大长度 <?php echo $option['maxlength'];?>&nbsp;
<?php } if($option['unchangeable']) { ?>
不可修改&nbsp;
<?php } if($option['description']) { ?>
<?php echo $option['description'];?>
<?php } ?>
</div>
<?php } ?>
</td>
<td class="ptm pbm bbda" width="180"><span id="check<?php echo $option['identifier'];?>"></span></td>
</tr>
<?php } ?>
</table>
<?php } ?>

<script type="text/javascript" reload="1">
var CHECKALLSORT = false;

function warning(obj, msg) {
obj.style.display = '';
obj.innerHTML = '<img src="<?php echo IMGDIR;?>/check_error.gif" width="16" height="16" class="vm" /> ' + msg;
obj.className = "warning";
if(CHECKALLSORT) {
showDialog(msg);
}
}

EXTRAFUNC['validator']['special'] = 'validateextra';
function validateextra() {
CHECKALLSORT = true;<?php if(is_array($_G['forum_optionlist'])) foreach($_G['forum_optionlist'] as $optionid => $option) { ?>if(!checkoption('<?php echo $option['identifier'];?>', '<?php echo $option['required'];?>', '<?php echo $option['type'];?>')) {
return false;
}
<?php } ?>
return true;
}

<?php if($_G['forum']['threadsorts']['expiration'][$_G['forum_selectsortid']]) { ?>
simulateSelect('typeexpiration');
<?php } ?>
</script>
