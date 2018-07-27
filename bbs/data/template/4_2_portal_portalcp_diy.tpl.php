<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('portalcp_diy');?><?php include template('common/header'); if($op=='blockclass') { ?>
<div class="tbmu mbm" id="contentblockclass_nav" style="margin-top: -15px"><?php $isfirst=1;?><?php if(is_array($_G['cache']['blockclass'])) foreach($_G['cache']['blockclass'] as $key => $value) { if($isfirst) { $isfirst=0;?><a href="javascript:;" id="bcnav_<?php echo $key;?>" class="a" onclick="spaceDiy.switchBlockclass('<?php echo $key;?>');return false;"><?php echo $value['name'];?></a>
<?php } else { ?>
<span class="pipe">|</span>
<a href="javascript:;" id="bcnav_<?php echo $key;?>" onclick="spaceDiy.switchBlockclass('<?php echo $key;?>');return false;"><?php echo $value['name'];?></a>
<?php } } ?>
</div><?php $isfirst=1;?><?php if(is_array($_G['cache']['blockclass'])) foreach($_G['cache']['blockclass'] as $key => $value) { if($isfirst) { $isfirst=0;?><ul class="blocks content" id="contentblockclass_<?php echo $key;?>">
<?php } else { ?>
<ul class="blocks content" id="contentblockclass_<?php echo $key;?>" class="hide">
<?php } ?>
<li class="module-<?php echo $key;?>">
<ol><?php if(is_array($value['subs'])) foreach($value['subs'] as $skey => $svalue) { ?><li class="module-<?php echo $skey;?>"><label onmousedown="drag.createObj (event,'block','<?php echo $skey;?>');" onmouseover="className='hover';" onmouseout="this.className='';"><?php echo $svalue['name'];?></label></li>
<?php } ?>
</ol>
</li>
</ul>
<?php } } elseif($op == 'style') { ?>
<ul class="content" style="overflow-y: auto; height: 90px;"><?php if(is_array($themes)) foreach($themes as $value) { ?>  <li><a href="javascript:;" onclick="spaceDiy.changeStyle('<?php echo $value['dir'];?>');return false;"><img src="<?php echo STATICURL;?><?php echo $value['dir'];?>/preview.jpg" /><?php echo $value['name'];?></a></li>
<?php } ?>
</ul>
<?php } elseif($_GET['op'] == 'image') { ?>
<div id="diyimg_prev" class="z"><?php echo $multi;?></div>
<ul id="imagebody"><?php if(is_array($list)) foreach($list as $key => $value) { ?><li class="thumb"><a href="javascript:;" onclick="return false;"><img src="<?php echo $value['pic'];?>" alt="" onclick="spaceDiy.setBgImage(this);"/></a></li>
<?php } ?>
</ul>
<div id="diyimg_next" class="z"><?php echo $multi;?></div>
<?php } elseif($_GET['op'] == 'diy') { ?>
<dl class='diy'>
<dt class="cl pns">
<div class="y">
<button type="button" id="uploadmsg_button" onclick="Util.toggleEle('upload');" class="pn pnc z<?php if(empty($list)) { ?> hide<?php } ?>"><span>上传新图片</span></button>
<div id="upload" class="z<?php if($list) { ?> hide<?php } ?>"><iframe id="uploadframe" name="uploadframe" width="0" height="0" marginwidth="0" frameborder="0" src="about:blank"></iframe>
<form method="post" autocomplete="off" name="uploadpic" id="uploadpic" action="portal.php?mod=portalcp&amp;ac=diy" enctype="multipart/form-data" target="uploadframe" onsubmit="return spaceDiy.uploadSubmit();">
<input type="file" class="t_input" name="attach" size="15">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="topicid" value="<?php echo $_GET['topicid'];?>" />
<button type="submit" name="uploadsubmit" id="btnupload" class="pn" value="true"><span>开始上传</span></button>
</form>
</div>
<span id="uploadmsg" class="z"></span>
</div>
正在编辑:
<a id="diy_tag_body" href="javascript:;" onclick="spaceDiy.setCurrentDiy('body');return false;">背景</a>
<span class="pipe">|</span><a id="diy_tag_blocktitle" href="javascript:;" onclick="spaceDiy.setCurrentDiy('blocktitle');return false;">标题栏</a></span>
<span class="pipe">|</span><a id="diy_tag_ct" href="javascript:;" onclick="spaceDiy.setCurrentDiy('ct');return false;">内容区</a>

  	<a style="margin-left: 40px;" id="bg_button" href="javascript:;" onclick="spaceDiy.hideBg();return false;">取消背景图</a>
<span class="pipe">|</span><a id="recover_button" href="javascript:;" onclick="spaceDiy.recoverStyle();return false;">恢复原装皮肤</a>
</dt>
<dd>
<div class="photo_list cl">
<div id="currentimgdiv" class="z" style="width:446px;">
<center><ul><li class="thumb" style="border:1px solid #ccc; padding:2px;"><img id="currentimg" alt="" src=""/></li></ul>
<div class="z cur1" onclick="spaceDiy.changeBgImgDiv();">更换</div></center>
</div>
<div id="diyimages" class="z" style="width: 446px; display: none">
<div id="diyimg_prev" class="z"><?php echo $multi;?></div>
<ul id="imagebody"><?php if(is_array($list)) foreach($list as $key => $value) { ?><li class="thumb"><a href="javascript:;" onclick="return false;"><img src="<?php echo $value['pic'];?>" alt="" onclick="spaceDiy.setBgImage(this);"/></a></li>
<?php } ?>
</ul>
<div id="diyimg_next" class="z"><?php echo $multi;?></div>
</div>
<div class="z" style="padding-left: 7px; width: 160px; border: solid #CCC; border-width: 0 1px;">
<table cellpadding="0" cellspacing="0">
<tr>
<td><label for="repeat_mode">图片平铺:</label></td>
<td>
<select id="repeat_mode" name="repeat_mode" class="ps" onclick="spaceDiy.setBgRepeat(this.value);">
<option value="0" selected="selected">平铺</option>
<option value="1">直接使用</option>
<option value="2">横向平铺</option>
<option value="3">纵向平铺</option>
</select>
</td>
</tr>
<tr>
<td>图片位置:</td>
<td>
<table cellpadding="0" cellspacing="0" id="positiontable">
<tr>
<td id="bgimgposition0" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
<td id="bgimgposition1" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
<td id="bgimgposition2" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
</tr>
<tr>
<td id="bgimgposition3" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
<td id="bgimgposition4" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
<td id="bgimgposition5" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
</tr>
<tr>
<td id="bgimgposition6" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
<td id="bgimgposition7" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
<td id="bgimgposition8" onclick="spaceDiy.setBgPosition(this.id)">&nbsp;</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
<div class="z diywin" style="padding-left: 7px; width: 160px;">
<table cellpadding="0" cellspacing="0">
<tr>
<td>背景滚动:</td>
<td>
<label for="rabga0"><input type="radio" id="rabga0" name="attachment_mode" onclick="spaceDiy.setBgAttachment(0);" class="pr" />滚动</label>
<label for="rabga1"><input type="radio" id="rabga1" name="attachment_mode" onclick="spaceDiy.setBgAttachment(1);" class="pr" />固定</label>
</td>
</tr>
<tr>
<td>背景颜色:</td>
<td><input type="text" id="colorValue" value="" size="6" onchange="spaceDiy.setBgColor(this.value);" class="px vm" style="font-size: 12px; padding: 2px;" />
<input id="cbpb" onclick="createPalette('bpb', 'colorValue', 'spaceDiy.setBgColor');" type="button" class="pn colorwd" value="" />
</td>
</tr>
</table>
</div>
<div class="z diywin" style="padding-left: 7px; width: 160px;">
<table cellpadding="0" cellspacing="0">
<tr>
<td>文字颜色:</td>
<td><input type="text" id="textColorValue" value="" size="6" onchange="spaceDiy.setTextColor(this.value);" class="px vm" style="font-size: 12px; padding: 2px;" />
<input id="ctpb" onclick="createPalette('tpb', 'textColorValue', 'spaceDiy.setTextColor');" type="button" class="pn colorwd" value="" />
</td>
</tr>
<tr>
<td>链接颜色:</td>
<td><input type="text" id="linkColorValue" value="" size="6" onchange="spaceDiy.setLinkColor(this.value);" class="px vm" style="font-size: 12px; padding: 2px;" />
<input id="clpb" onclick="createPalette('lpb', 'linkColorValue', 'spaceDiy.setLinkColor');" type="button" class="colorwd" value="" style="background: #fff;" />
</td>
</tr>
</table>
</div>
  </dd>
</dl>
<?php } elseif($op == 'import') { ?>
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">导入框架</em>
<span>
<?php if($_G['inajax']) { ?><a href="javascript:;" class="flbc" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');return false;" title="关闭">关闭</a><?php } ?>
</span>
</h3>
<ul class="tb cl">
<li<?php if(empty($_GET['type'])) { ?> class="a"<?php } ?> id="li_import_upload"><a onclick="showWindow('showimport', this.getAttribute('href'));" href="portal.php?mod=portalcp&amp;ac=diy&amp;op=import&amp;type=0&amp;tpl=<?php echo $_GET['tpl'];?>">上传文件</a></li>
<li<?php if($_GET['type'] == 1) { ?> class="a"<?php } ?> id="li_import_system"><a onclick="showWindow('showimport', this.getAttribute('href'));" href="portal.php?mod=portalcp&amp;ac=diy&amp;op=import&amp;type=1&amp;tpl=<?php echo $_GET['tpl'];?>">系统内置</a></li>
</ul>

<form name="frameimport" id="frameimport" enctype="multipart/form-data" method="post" autocomplete="off" action="portal.php?mod=portalcp&amp;ac=diy&amp;op=import" onsubmit="ajaxpost('frameimport','return_<?php echo $_GET['handlekey'];?>','','onerror',$('frameimportbutton'));">
<div class="c" style="width:420px;line-height:100px; overflow-y: auto; ">
<?php if($_GET['type'] == 1) { if($xmlarr) { ?>
选择要导入的文件:
<select id="importfilename" name="importfilename" class=""><?php if(is_array($xmlarr)) foreach($xmlarr as $key => $value) { ?><option value="<?php echo $key;?>"><?php echo $value;?></option>
<?php } ?>
</select>
<?php } else { ?>
<center>没有找到内置文件</center>
<?php } } else { ?>
文本文件的位置: <input type="file" id="importfile" name="importfile" style="margin: 5px;">
<?php } ?>
</div>
<div class="o pns">
<input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" />
<input type="hidden" name="importsubmit" value="true" />
<input type="hidden" name="tpl" value="<?php echo $_GET['tpl'];?>" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<button type="submit" class="pn pnc" id="frameimportbutton"><strong>导入</strong></button>
</div>
</form>
<script type="text/javascript" reload="1">
function succeedhandle_<?php echo $_GET['handlekey'];?> (url, message, values) {
if (values['status'] == '1') {
if (values['css']) spaceDiy.initDiyStyle(values['css']);

var areaArr = values['html'];
var dom = document.createElement("div");
for (var i in areaArr) {
var html = areaArr[i].replace(/\[script/g, '<script').replace(/\[\/script\]/g, '<\/script>');
var area = $(i) ? $(i) : drag.moveableArea[0];
dom.innerHTML = html;
var arr = [];
for (var i=0, l=dom.childNodes.length; i < l; i++) {
arr.push(dom.childNodes[i]);
}
var one = '';
while(one = arr.pop()) {
Util.insertBefore(one,area.firstChild);
}
}
drag.initPosition();
drag.isChange = true;
drag.setClose();
var blocks = values['bids'].split(',');
drag.blockForceUpdateBatch(blocks);
}

hideWindow('<?php echo $_GET['handlekey'];?>');
}
</script>
<?php } include template('common/footer'); ?>