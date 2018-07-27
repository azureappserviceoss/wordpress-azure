<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('tag');?><?php include template('common/header'); if(($op == 'search')) { if($taglist) { if(is_array($taglist)) foreach($taglist as $var) { ?><a href="javascript:;" onclick="if(this.className == 'xi2') { window.onbeforeunload = null; parent.document.getElementById('tags').value += parent.document.getElementById('tags').value == '' ? '<?php echo $var['tagname'];?>' : ',<?php echo $var['tagname'];?>'; doane(); this.className += ' marked'; }" class="xi2"><?php echo $var['tagname'];?></a>
<?php } } else { ?>
<p class="emp">没有相关标签<p>
<?php } } elseif(($op == 'set')) { } elseif(($op == 'manage')) { ?>
<h3 class="flb">
<em>标签</em>
</h3>
<div class="c bart">
<p>
<input type="text" name="tags" id="tags" class="px vm" value="<?php echo $tags;?>" size="60" />
<img src="<?php echo IMGDIR;?>/faq.gif" alt="Tip" class="vm" tip="用逗号或空格隔开多个标签，最多可填写 5 个" onmouseover="showTip(this)" />
<input type="hidden" name="tid" id="tid" value="<?php echo $_GET['tid'];?>" />
</p>
<?php if($recent_use_tag) { ?>
<p class="mtn">最近使用标签:<?php $tagi = 0;?><?php if(is_array($recent_use_tag)) foreach($recent_use_tag as $var) { if($tagi) { ?>, <?php } ?><a href="javascript:;" class="xi2" onclick="$('tags').value == '' ? $('tags').value += '<?php echo $var;?>' : $('tags').value += ',<?php echo $var;?>';"><?php echo $var;?></a><?php $tagi++;?><?php } ?>
</p>
<?php } ?>
</div>
<p class="o pns">
<button type="button" name="search_button" class="pn" value="false" onclick="tagset();"><strong>提交</strong></button>
<button type="button" id="closebtn" class="pn" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');"><strong>关闭</strong></button>
</p>
<?php } else { ?>
<h3 class="flb">
<em>选择标签</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<div class="c bart">
<div class="pns mbn cl">
<input type="text" name="searchkey" id="searchkey" class="px vm" value="<?php echo $searchkey;?>" size="30" />&nbsp;
<button type="button" name="search_button" class="pn vm" value="false" onclick="tagsearch();"><em>搜索</em></button>
<img tip="搜索结果最多显示 50 个，点击结果链接可直接插入标签" onmouseover="showTip(this)" class="vm" alt="Tip" src="<?php echo IMGDIR;?>/faq.gif" />
</div>
<div id="taglistarea" style="width: 400px;"></div>
</div>
<p class="o pns">
<button type="button" class="pn pnc" id="closebtn" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');"><strong>关闭</strong></button>
</p>
<?php } ?>
<script type="text/javascript">
function tagsearch() {
$('taglistarea').innerHTML = '';
var searchkey = $('searchkey').value;
var url = 'forum.php?mod=tag&op=search&inajax=1&searchkey='+searchkey;
var x = new Ajax();
x.get(url, function(s){
if(s) {
$('taglistarea').innerHTML = s;
}
});
}

function tagset() {
var tags = $('tags').value;
var tid = $('tid').value;
tags = BROWSER.ie && document.charset == 'utf-8' ? encodeURIComponent(tags) : tags;
var url = 'forum.php?mod=tag&op=set&inajax=1&tags='+tags+'&tid='+tid+'&formhash=<?php echo FORMHASH;?>';
var x = new Ajax();
x.get(url, function(s){
if(s) {
hideWindow('<?php echo $_GET['handlekey'];?>');
window.location.reload();
}
});
}
</script><?php include template('common/footer'); ?>