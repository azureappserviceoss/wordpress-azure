<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<style type="text/css">
/* DIY MODE CSS STYLE */
.hide { display: none; }
.frame,.tab,.block { position: relative; zoom:1; min-height: 20px; }
.edit { position: absolute; top: 0; right: 0; z-index: 199; padding: 0 5px; background: red; line-height: 26px; color: #FFF; cursor: pointer; }
.block .edit { background: #369; }
.edit-menu { position: absolute; z-index: 300; border-style: solid; border-width: 0 1px 1px 1px; border-color: #DDD #999 #999 #CCC; background: #FFF; }
.mitem { padding: 4px 4px 4px 14px; width: 36px; border-top: 1px solid #DDD; cursor: pointer; }
.mitem:hover { background: #F2F2F2; color: #06C; }
.subtitle { margin: 0 4px; }
.frame-tab .title .move-span { float: left; margin: 0 3px 0 0; padding: 0; width: 100px; border-bottom: none; cursor: pointer; }
#samplepanel { background: #FFF; }
.block-name { display: block; visibility: hidden; background: #000; color: #FFF; position: absolute; top: 5px; left: 5px; padding: 2px; opacity: 0.85; filter: alpha(opacity=85); z-index: 1; }
</style>
<div id="button_more_menu" class="p_pop" style="display: none;">
<ul>
<li><a href="javascript:;" onclick="spaceDiy.recover();return false;" title="恢复备份">恢复备份</a></li>
<li><a href="javascript:;" onclick="drag.frameExport();return false;" title="导出当前页面中所有DIY数据">导出</a></li>
<li><a href="javascript:;" onclick="drag.openFrameImport();$('button_more_menu').style.display='none';return false;" title="将DIY数据导入到当前页面">导入</a></li>
<li><a href="javascript:;" onclick="drag.blockForceUpdateBatch();$('button_more_menu').style.display='none';return false;" title="更新当前页面所有模块的数据">更新</a></li>
<li><a href="javascript:;" onclick="drag.clearAll();$('button_more_menu').style.display='none';return false;" title="清空页面上所在DIY数据">清空</a></li>
</ul>
</div>
<div id="controlpanel" class="cl hide">
<div id="controlheader" class="cl">
<p class="y">
<span id="navcancel"><a href="javascript:;" id="diycancel" onclick="spaceDiy.cancel();return false;">关闭</a></span>
<a href="javascript:;" title="更多操作" id="button_more" onmouseover="showMenu(this.id);">More</a>
<span id="navsave"><a href="javascript:;" onclick="javascript:spaceDiy.save();return false;">保存</a></span>
<span id="button_redo" class="unusable"><a href="javascript:;" onclick="spaceDiy.redo();return false;" title="重做" onfocus="this.blur();">重做</a></span>
<span id="button_undo" class="unusable"><a href="javascript:;" onclick="spaceDiy.undo();return false;" title="撤销" onfocus="this.blur();">撤销</a></span>
<span id="preview" class="unusable"><a href="javascript:;" onclick="spaceDiy.save('preview');return false;" onfocus="this.blur();" title="预览DIY的效果" id="diy_preview">预览</a></span>
<span id="savecachemsg" class="xg1" style="display: none;"></span>
</p>
<ul id="controlnav">
<li id="navstart" class="current"><a href="javascript:" onclick="spaceDiy.getdiy('start');this.blur();return false;">开始</a></li>
<li id="navframe"><a href="javascript:;" onclick="spaceDiy.getdiy('frame');this.blur();return false;">框架</a></li>
<li id="navblockclass"><a href="javascript:;" onclick="spaceDiy.getdiy('blockclass');this.blur();return false;" id="hd_mod">模块</a></li>
<?php if(!empty($topic)) { ?>
<li id="navstyle"><a href="javascript:;" onclick="spaceDiy.getdiy('style');this.blur();return false;">风格</a></li>
<li id="navdiy"><a href="javascript:;" onclick="spaceDiy.getdiy('diy', 'topicid', '<?php echo $topic['topicid'];?>');this.blur();return false;">自定义</a></li>
<?php } ?>
</ul>
<div id="diy_backup_tip" class="tip tip_2" style="display: none;">
<div class="tip_horn"></div>
<div class="tip_c">您可以通过导出进行模板备份 &nbsp; <a href="javascript:;" class="xi2" onclick="drag.saveViewTip('diy_backup_tip');return false;">我知道了</a></div>
</div>
</div>
<div id="controlcontent" class="cl">
<ul id="contentstart" class="content">
  <li><a href="javascript:;" onclick="spaceDiy.getdiy('frame');return false;"><img src="<?php echo STATICURL;?>image/diy/layout.png" />添加框架</a></li>
  <li><a href="javascript:;" onclick="spaceDiy.getdiy('blockclass');return false;"><img src="<?php echo STATICURL;?>image/diy/module.png" />添加模块</a></li>
  <?php if(!empty($topic)) { ?>
  <li><a href="javascript:;" onclick="spaceDiy.getdiy('style');return false;"><img src="<?php echo STATICURL;?>image/diy/style.png" />风格</a></li>
  <li><a href="javascript:;" onclick="spaceDiy.getdiy('diy', 'topicid', '<?php echo $topic['topicid'];?>');return false;"><img src="<?php echo STATICURL;?>image/diy/diy.png" />自定义</a></li>
  <?php } ?>
</ul>
<ul id="contentframe" class="content hide">
<li><a href="javascript:;" id="frame_1" onmousedown="drag.createObj(event,'frame','1');" onfocus="this.blur();" data="<?php echo $widthstr;?>"><img src="<?php echo STATICURL;?>image/diy/layout-1.png" />100%框架</a></li>
<li><a href="javascript:;" id="frame_1_1" onmousedown="drag.createObj(event,'frame','1-1');" onfocus="this.blur();"><img src="<?php echo STATICURL;?>image/diy/layout-1-1.png" />1:1</a></li>
<li><a href="javascript:;" id="frame_1_2" onmousedown="drag.createObj(event,'frame','1-2');" onfocus="this.blur();"><img src="<?php echo STATICURL;?>image/diy/layout-1-2.png" />1:2</a></li>
<li><a href="javascript:;" id="frame_2_1" onmousedown="drag.createObj(event,'frame','2-1');" onfocus="this.blur();"><img src="<?php echo STATICURL;?>image/diy/layout-2-1.png" />2:1</a></li>
<li><a href="javascript:;" id="frame_1_3" onmousedown="drag.createObj(event,'frame','1-3');" onfocus="this.blur();"><img src="<?php echo STATICURL;?>image/diy/layout-1-3.png" />1:3</a></li>
<li><a href="javascript:;" id="frame_3_1" onmousedown="drag.createObj(event,'frame','3-1');" onfocus="this.blur();"><img src="<?php echo STATICURL;?>image/diy/layout-3-1.png" />3:1</a></li>
<li><a href="javascript:;" id="frame_1_1_1" onmousedown="drag.createObj(event,'frame','1-1-1');" onfocus="this.blur();" data="<?php echo $widthstr;?>"><img src="<?php echo STATICURL;?>image/diy/layout-1-1-1.png" />1:1:1</a></li>
<li><a href="javascript:;" id="frame_tab" onmousedown="drag.createObj(event,'tab');" onfocus="this.blur();" data="<?php echo $widthstr;?>"><img src="<?php echo STATICURL;?>image/diy/layout-tab.png" />tab框架</a></li>
</ul>
<div id="contentblockclass" class="content"></div>
</div>
<div id="cpfooter"><table cellpadding="0" cellspacing="0" width="100%"><tr><td class="l">&nbsp;</td><td class="c">&nbsp;</td><td class="r">&nbsp;</td></tr></table></div>
</div>
<div id="samplepanel" class="hide ptm pbm bbda hm">
<span class="y"><a href="javascript:;" onclick="spaceDiy.cancel();return false;" class="xi2">关闭</a>&nbsp;&nbsp;</span>
当前为<strong>简洁模式</strong>，您可以更新模块，修改模块属性和数据，要使用完整的拖拽功能，<a href="javascript:;" onclick="spaceDiy.init();" class="xw1 xi2">请点击进入高级模式</a>
</div>

<form method="post" autocomplete="off" name="diyform" id="diyform" action="<?php echo $_G['siteurl'];?>portal.php?mod=portalcp&ac=diy">
<input type="hidden" name="template" value="<?php echo $_G['style']['tplfile'];?>" />
<input type="hidden" name="tpldirectory" value="<?php echo $_G['style']['tpldirectory'];?>" />
<input type="hidden" name="diysign" value="<?php echo dsign($_G['style']['tpldirectory'].$_G['style']['tplfile']); ?>" />
<input type="hidden" name="prefile" id="prefile" value="<?php echo $_G['style']['prefile'];?>" />
<input type="hidden" name="savemod" value="<?php echo $_G['style']['tplsavemod'];?>" />
<input type="hidden" name="spacecss" value="" />
<input type="hidden" name="style" value="" />
<input type="hidden" name="rejs" value="" />
<input type="hidden" name="handlekey" value="" />
<input type="hidden" name="layoutdata" value="" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="gobackurl" id="gobackurl" value=""/>
<input type="hidden" name="recover" value=""/>
<input type="hidden" name="optype" value=""/>

<input type="hidden" name="diysubmit" value="true"/>
</form>