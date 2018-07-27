<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('upload');?><?php include template('common/header'); ?><div class="upfile">
<h3 class="flb">
<em id="return_upload">上传</em>
<em id="uploadwindowing" class="mtn" style="visibility:hidden"><img src="<?php echo IMGDIR;?>/uploading.gif" alt="" /></em>
<span><a href="javascript:;" class="flbc" onclick="hideWindow('upload', 0)" title="关闭">关闭</a></span>
</h3>
<div class="c">
<form id="uploadform" class="uploadform ptm pbm" method="post" autocomplete="off" target="uploadattachframe" onsubmit="uploadWindowstart()" action="misc.php?mod=swfupload&amp;operation=upload&amp;type=<?php echo $type;?>&amp;inajax=yes&amp;infloat=yes&amp;simple=2" enctype="multipart/form-data">
<input type="hidden" name="handlekey" value="upload" />
<input type="hidden" name="uid" value="<?php echo $_G['uid'];?>">
<input type="hidden" name="hash" value="<?php echo md5(substr(md5($_G['config']['security']['authkey']), 8).$_G['uid']); ?>">
<div class="filebtn">
<input type="file" name="Filedata" id="filedata" class="pf cur1" size="1" onchange="$('uploadform').submit()" />
<button type="button" class="pn pnc"><strong>浏览</strong></button>
</div>
</form>
<p class="xg1 mtn">
<?php if($type == 'image') { ?>可用扩展名: <span class="xi1"><?php echo $imgexts;?></span><?php } elseif($_G['group']['attachextensions']) { ?>可用扩展名: <span class="xi1"><?php echo $_G['group']['attachextensions'];?></span><?php } ?>
</p>
<iframe name="uploadattachframe" id="uploadattachframe" style="display: none;" onload="uploadWindowload();"></iframe>
</div>
</div><?php include template('common/footer'); ?>