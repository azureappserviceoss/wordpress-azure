<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); 
0
|| checktplrefresh('./template/mahjong/forum/post_poll.htm', './template/mahjong/common/upload.htm', 1514433770, '2', './data/template/4_2_forum_post_poll.tpl.php', './template/mahjong', 'forum/post_poll')
;?>
<input type="hidden" name="polls" value="yes" />
<div class="exfm cl">
<div class="sinf sppoll z">
<input type="hidden" name="fid" value="<?php echo $_G['fid'];?>" />
<?php if($_GET['action'] == 'newthread') { ?>
<input type="hidden" name="tpolloption" value="1" />
<div class="cl">
<h4 class="z">
<em>选项: </em>
最多可填写 <?php echo $_G['setting']['maxpolloptions'];?> 个选项 &nbsp;
<span class="xw0"><input id="pollchecked" type="checkbox" class="pc" onclick="switchpollm(1)" /><label for="pollchecked">单框模式</label></span>
</h4>
</div>
<div id="pollm_c_1" class="mbm">
<span id="polloption_new"></span>
<p id="polloption_hidden" style="display: none">
<a href="javascript:;" class="d" onclick="delpolloption(this)">del</a>
<input type="text" name="polloption[]" class="px vm" autocomplete="off" style="width:290px;" tabindex="1" />
<span id="pollUploadProgress" class="vm" style="display: none;"></span>
<span id="newpoll" class="vm"></span>
</p>
<p><a href="javascript:;" onclick="addpolloption()">+增加一项</a></p>
</div>
<div id="pollm_c_2" class="mbm" style="display:none">
<textarea name="polloptions" class="pt" style="width:340px;" tabindex="1" rows="6" onchange="switchpollm(0)" /></textarea>
<p class="cl">每行填写 1 个选项</p>
</div>
<?php } else { if(is_array($poll['polloption'])) foreach($poll['polloption'] as $key => $option) { $ppid = $poll['polloptionid'][$key];?><p>
<input type="hidden" name="polloptionid[<?php echo $poll['polloptionid'][$key];?>]" value="<?php echo $poll['polloptionid'][$key];?>" />
<input type="text" name="displayorder[<?php echo $poll['polloptionid'][$key];?>]" class="px pxs vm" autocomplete="off" tabindex="1" value="<?php echo $poll['displayorder'][$key];?>" />
<input type="text" name="polloption[<?php echo $poll['polloptionid'][$key];?>]" class="px vm" autocomplete="off" style="width:230px;" tabindex="1" value="<?php echo $option;?>"<?php if(!$_G['group']['alloweditpoll']) { ?> readonly="readonly"<?php } ?> />
<!--img src="<?php echo $poll['imginfo'][$ppid]['small'];?>" class="cur1" /-->

<span id="newpoll_<?php echo $key;?>" class="vm"></span>
<span id="pollUploadProgress_<?php echo $key;?>" class="vm">
<?php if($poll['isimage']) { ?>
<img src="<?php echo IMGDIR;?>/attachimg_2.png" class="cur1" onmouseover="showMenu({'menuid':'poll_img_preview_<?php echo $poll['imginfo'][$ppid]['aid'];?>_menu','ctrlclass':'a','duration':2,'timeout':0,'pos':'34'});" onmouseout="hideMenu('poll_img_preview_<?php echo $poll['imginfo'][$ppid]['aid'];?>_menu');" />
<?php } ?>
<input type="hidden" name="pollimage[<?php echo $poll['polloptionid'][$key];?>]" id="pollUploadProgress_<?php echo $key;?>_aid" value="<?php echo $poll['imginfo'][$ppid]['aid'];?>" />
<span id="poll_img_preview_<?php echo $poll['imginfo'][$ppid]['aid'];?>_menu" style="display: none">
<img src="<?php echo $poll['imginfo'][$ppid]['small'];?>" />
</span>
</span>
</p>
<?php } ?>
<span id="polloption_new"></span>
<p id="polloption_hidden" style="display: none">
<a href="javascript:;" class="d" onclick="delpolloption(this)">del</a>
<input type="text" name="displayorder[]" class="px pxs vm" autocomplete="off" tabindex="1" />
<input type="text" name="polloption[]" class="px vm" autocomplete="off" style="width:230px;" tabindex="1" />
<span id="newpoll" class="vm"></span>
<span id="pollUploadProgress" class="vm" style="display: none;"></span>
</p>
<p><a href="javascript:;" onclick="addpolloption()">+增加一项</a></p>
<?php } ?>
</div>
<div class="sadd z">
<p class="mbn">
<label for="maxchoices">最多可选</label>
<input type="text" name="maxchoices" id="maxchoices" class="px pxs" value="<?php if($_GET['action'] == 'edit' && $poll['maxchoices']) { ?><?php echo $poll['maxchoices'];?><?php } else { ?>1<?php } ?>" tabindex="1" /> 项
</p>
<p class="mbn">
<label for="polldatas">记票天数</label>
<input type="text" name="expiration" id="polldatas" class="px pxs" value="<?php if($_GET['action'] == 'edit') { if(!$poll['expiration']) { ?>0<?php } elseif($poll['expiration'] < 0) { ?>关闭投票<?php } elseif($poll['expiration'] < TIMESTAMP) { ?>已结束<?php } else { echo (round(($poll['expiration'] - TIMESTAMP) / 86400)); } } ?>" tabindex="1" /> 天
</p>
<p class="mbn">
<input type="checkbox" name="visibilitypoll" id="visibilitypoll" class="pc" value="1"<?php if($_GET['action'] == 'edit' && !$poll['visible']) { ?> checked<?php } ?> tabindex="1" /><label for="visibilitypoll">投票后结果可见</label>
</p>
<p class="mbn">
<input type="checkbox" name="overt" id="overt" class="pc" value="1"<?php if($_GET['action'] == 'edit' && $poll['overt']) { ?> checked<?php } ?> tabindex="1" /><label for="overt">公开投票参与人</label>
</p>
<?php if(!empty($_G['setting']['pluginhooks']['post_poll_extra'])) echo $_G['setting']['pluginhooks']['post_poll_extra'];?>
</div>
</div><?php if(empty($_G['uploadjs'])) { ?>
<script src="<?php echo $_G['setting']['jspath'];?>upload.js?<?php echo VERHASH;?>" type="text/javascript"></script><?php $_G['uploadjs'] = 1;?><?php } ?><script type="text/javascript" reload="1">
var maxoptions = parseInt('<?php echo $_G['setting']['maxpolloptions'];?>');
<?php if($_GET['action'] == 'newthread') { ?>
var curoptions = 0;
var curnumber = 1;
addpolloption();
addpolloption();
addpolloption();
<?php } else { ?>
var curnumber = curoptions = <?php echo count($poll['polloption']); ?>;
for(var i=0; i < curnumber; i++) {
addUploadEvent('newpoll_'+i, 'pollUploadProgress_'+i);
}
<?php } ?>
function addUploadEvent(imgid, pollstr) {
<?php if(empty($_G['setting']['pluginhooks']['post_upload_extend']) && empty($_G['setting']['pluginhooks']['post_poll_upload_extend'])) { ?>
new SWFUpload({
upload_url: SITEURL + 'misc.php?mod=swfupload&action=swfupload&operation=poll&fid=<?php echo $_G['fid'];?>',
post_params: {"uid":"<?php echo $_G['uid'];?>", "hash":"<?php echo $swfconfig['hash'];?>"},

file_size_limit : "2048",
file_types : "*.jpg;*.jpeg;*.gif;*.png;*.bmp",
file_types_description : "图片文件",
file_upload_limit : 0,
file_queue_limit : 1,

swfupload_preload_handler : preLoad,
swfupload_load_failed_handler : loadFailed,
file_dialog_start_handler : fileDialogStart,
file_queued_handler : fileQueued,
file_queue_error_handler : fileQueueError,
file_dialog_complete_handler : fileDialogComplete,
upload_start_handler : uploadStart,
upload_progress_handler : uploadProgress,
upload_error_handler : uploadError,
upload_success_handler : uploadSuccess,
upload_complete_handler : uploadComplete,

button_image_url : IMGDIR+"/uploadbutton_small_pic.png",
button_placeholder_id : imgid,
button_width: 26,
button_height: 26,
button_cursor:SWFUpload.CURSOR.HAND,
button_window_mode: "transparent",

custom_settings : {
progressTarget : pollstr,
uploadSource: 'forum',
uploadType: 'poll'
},

debug: false
});
<?php } else { ?>
<?php if(!empty($_G['setting']['pluginhooks']['post_poll_upload_extend'])) echo $_G['setting']['pluginhooks']['post_poll_upload_extend'];?>
<?php } ?>
}
</script>
