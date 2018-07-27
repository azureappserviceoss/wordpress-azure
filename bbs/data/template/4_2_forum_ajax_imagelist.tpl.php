<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); if($imagelist) { if($_GET['type'] != 'single') { $i = 0;?><table cellspacing="2" cellpadding="2" class="imgl"><tr>
<?php } if(is_array($imagelist)) foreach($imagelist as $image) { $i++;?><?php if($_GET['type'] != 'single') { ?>
<td valign="bottom" id="image_td_<?php echo $image['aid'];?>" width="25%">
<?php } ?>
<a href="javascript:;" title="<?php echo $image['filename'];?>" id="imageattach<?php echo $image['aid'];?>"><img src="<?php echo getforumimg($image['aid'], 1, 300, 300, 'fixnone'); ?>" id="image_<?php echo $image['aid'];?>" onclick="insertAttachimgTag('<?php echo $image['aid'];?>');doane(event);" width="<?php if($image['width'] < 110) { ?><?php echo $image['width'];?><?php } else { ?>110<?php } ?>" cwidth="<?php if($image['width'] < 300) { ?><?php echo $image['width'];?><?php } else { ?>300<?php } ?>" /></a>
<p class="mtn mbn xi2">
<?php if($attach['pid']) { ?>
<input type="hidden" name="attachupdate[<?php echo $image['aid'];?>]" id="attachupdate<?php echo $image['aid'];?>" size="2" />&nbsp;
<a href="javascript:;" onclick="uploadWindow(function (aid, url, name){$('attachupdate<?php echo $image['aid'];?>').value = aid;ajaxget('forum.php?mod=ajax&action=getimage&aid=' + aid, 'imageattach<?php echo $image['aid'];?>');}, 'image');return false;">更新</a>
<span class="pipe">|</span>
<?php } ?>
<a href="javascript:;" onclick="delImgAttach(<?php echo $image['aid'];?>,<?php if(!$attach['pid']) { ?>1<?php } else { ?>0<?php } ?>);return false;">删除</a>
</p>
<p class="imgf">
<?php if($image['description']) { ?>
<input type="text" name="attachnew[<?php echo $image['aid'];?>][description]" class="px xg2" value="<?php echo $image['description'];?>" id="image_desc_<?php echo $image['aid'];?>" />
<?php } else { ?>
<input type="text" class="px xg2" value="描述" onclick="this.style.display='none';$('image_desc_<?php echo $image['aid'];?>').style.display='';$('image_desc_<?php echo $image['aid'];?>').focus();" />
<input type="text" name="attachnew[<?php echo $image['aid'];?>][description]" class="px" style="display: none" id="image_desc_<?php echo $image['aid'];?>" />
<?php } ?>
</p>
<?php if(helper_access::check_module('album') && $_G['group']['allowupload']) { ?>
<p class="mtn"><?php if(!$attach['pid']) { ?><input type="hidden" class="pc" id="albumaid_<?php echo $image['aid'];?>" name="albumaid[]" value="" /><label for="albumaidchk_<?php echo $image['aid'];?>"><input id="albumaidchk_<?php echo $image['aid'];?>" type="checkbox" class="pc" onclick="$('albumaid_<?php echo $image['aid'];?>').value=this.checked?this.value:''" value="<?php echo $image['aid'];?>" />保存到相册</label><?php } ?></p>
<?php } ?>
</td>
<?php if($_GET['type'] != 'single' && $i % 4 == 0 && isset($imagelist[$i])) { ?></tr><tr><?php } } if($_GET['type'] != 'single') { if(($imgpad = $i % 4) > 0) { echo str_repeat('<td width="25%"></td>', 4 - $imgpad);; } ?>
</tr></table>
<?php } if($_G['inajax']) { ?>
<script type="text/javascript" reload="1">
ATTACHNUM['imageunused'] += <?php echo count($imagelist); ?>;
updateattachnum('image');
if($('attachlimitnotice')) {
ajaxget('forum.php?mod=ajax&action=updateattachlimit&fid=' + fid, 'attachlimitnotice');
}
</script>
<?php } } ?>