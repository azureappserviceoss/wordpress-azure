<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); 
0
|| checktplrefresh('./template/mahjong/forum/ajax.htm', './template/mahjong/common/header_ajax.htm', 1512965854, '2', './data/template/4_2_forum_ajax.tpl.php', './template/mahjong', 'forum/ajax')
|| checktplrefresh('./template/mahjong/forum/ajax.htm', './template/mahjong/common/footer_ajax.htm', 1512965854, '2', './data/template/4_2_forum_ajax.tpl.php', './template/mahjong', 'forum/ajax')
;?>
<?php ob_end_clean();
ob_start();
@header("Expires: -1");
@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
@header("Pragma: no-cache");
@header("Content-type: text/xml; charset=".CHARSET);
echo '<?xml version="1.0" encoding="'.CHARSET.'"?>'."\r\n";?><root><![CDATA[<?php if($_GET['action'] == 'quickclear') { ?>
<div class="tm_c">
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">快速清理</em>
<span>
<a href="javascript:;" class="flbc" onclick="hideWindow('<?php echo $_GET['handlekey'];?>')" title="关闭">关闭</a>
</span>
</h3>
<form id="qclearform" method="post" autocomplete="off" action="forum.php?mod=ajax&amp;action=quickclear&amp;inajax=1" onsubmit="ajaxpost(this.id, 'return_<?php echo $_GET['handlekey'];?>', 'return_<?php echo $_GET['handlekey'];?>');return false;">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="uid" value="<?php echo $uid;?>" />
<input type="hidden" name="redirect" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="qclearsubmit" value="1" />
<input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" />

<div class="c">

<ul>
<li><label><input type="checkbox" name="operations[]" class="pc" value="avatar" />清除头像</label></li>
<li><label><input type="checkbox" name="operations[]" class="pc" value="sightml" />清除签名</label></li>
<li><label><input type="checkbox" name="operations[]" class="pc" value="customstatus" />清除自定义头衔</label></li>
</ul>
<br />
<?php if($crimenum_avatar > 0) { ?>
<div style="clear: both; text-align: right;">用户 <?php echo $crimeauthor;?> 已被清除头像 <?php echo $crimenum_avatar;?> 次</div>
<?php } if($crimenum_sightml > 0) { ?>
<div style="clear: both; text-align: right;">用户 <?php echo $crimeauthor;?> 已被清除签名 <?php echo $crimenum_sightml;?> 次</div>
<?php } if($crimenum_customstatus > 0) { ?>
<div style="clear: both; text-align: right;">用户 <?php echo $crimeauthor;?> 已被清除自定义头衔 <?php echo $crimenum_customstatus;?> 次</div>
<?php } ?>
<div class="tpclg">
<h4 class="cl"><a onclick="showselect(this, 'reason', 'reasonselect')" class="dpbtn" href="javascript:;">^</a><span>操作原因:</span></h4>
<p>
<textarea id="reason" name="reason" class="pt" onkeyup="seditor_ctlent(event, '$(\'modsubmit\').click();')" rows="3"></textarea>
<ul id="reasonselect" style="display: none"><?php echo modreasonselect(); ?></ul>
</p>
</div>

</div>
<p class="o">
<label for="sendreasonpm"><input type="checkbox" name="sendreasonpm" id="sendreasonpm" class="pc"<?php if($_G['group']['reasonpm'] == 2 || $_G['group']['reasonpm'] == 3) { ?> checked="checked" disabled="disabled"<?php } ?> />通知作者</label>
<button type="submit" name="modsubmit" id="modsubmit" class="pn pnc" value="true" tabindex="2"><strong>提交</strong></button>
</p>
</form>
</div>
<?php } elseif($_GET['action'] == 'setnav') { ?>
<div class="tm_c">
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>"><?php echo $navtitle;?></em>
<span>
<a href="javascript:;" class="flbc" onclick="hideWindow('<?php echo $_GET['handlekey'];?>')" title="关闭">关闭</a>
</span>
</h3>
<form id="setnav" method="post" autocomplete="off" action="forum.php?mod=ajax&amp;action=setnav&amp;type=portal&amp;do=<?php echo $do;?>">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="type" value="<?php echo $type;?>" />
<input type="hidden" name="funcsubmit" value="1" />
<input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" />
<div class="c">
<?php if($do == 'open') { ?>
<ul>
<?php if($type != 'wall') { ?>
<li><label><input type="checkbox" name="location[header]" class="pc" value="1" />主导航</label></li>
<?php } ?>
<li><label><input type="checkbox" name="location[quick]" class="pc" value="1" />快捷导航</label></li>
</ul>
<?php } else { ?>
<?php echo $closeprompt;?>
<?php } ?>
</div>
<p class="o pns">
<input type="submit" name="funcsubmit_btn" class="btn" value="确定">
</p>
</form>
</div>
<?php } echo output_ajax(); ?>]]></root><?php exit;?>