<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); include template('common/header_ajax'); ?><div id="flsrchdiv">
<div class="mbm">搜索版块: <input type="text" class="px vm" onkeyup="forumlistsearch(this.value)" /></div>
<ul class="jump_bdl cl">
<li>
<p class="bbda xg1">所有版块</p><?php if(is_array($forumlist)) foreach($forumlist as $upfid => $gdata) { if($gdata['sub']) { ?>
<p class="xw1<?php if($_GET['jfid'] == $upfid) { ?> a<?php } ?>"><a href="forum.php?gid=<?php echo $upfid;?>"><?php echo $gdata['name'];?></a></p><?php if(is_array($gdata['sub'])) foreach($gdata['sub'] as $subfid => $name) { ?><p class="sub<?php if($_GET['jfid'] == $subfid) { ?> a<?php } ?>"><a href="forum.php?mod=forumdisplay&amp;fid=<?php echo $subfid;?>"><?php echo $name;?></a></p><?php if(is_array($gdata['child'][$subfid])) foreach($gdata['child'][$subfid] as $childfid => $name) { ?><p class="child<?php if($_GET['jfid'] == $childfid) { ?> a<?php } ?>"><a href="forum.php?mod=forumdisplay&amp;fid=<?php echo $childfid;?>"><?php echo $name;?></a></p>
<?php } } } } ?>
</li>

<li>
<p class="bbda xg1">最近浏览</p><?php if(is_array($visitedforums)) foreach($visitedforums as $fid => $forumname) { ?><p<?php if($_GET['jfid'] == $fid) { ?> class="a"<?php } ?>><a href="forum.php?mod=forumdisplay&amp;fid=<?php echo $fid;?>"><?php echo $forumname;?></a></p>
<?php } ?>
</li>

<li>
<p class="bbda xg1">我的收藏</p><?php if(is_array($favforums)) foreach($favforums as $forum) { ?><p<?php if($_GET['jfid'] == $forum['id']) { ?> class="a"<?php } ?>><a href="forum.php?mod=forumdisplay&amp;fid=<?php echo $forum['id'];?>"><?php echo $forum['title'];?></a></p>
<?php } ?>
</li>

</ul>
</div>
<script type="text/javascript">
function forumlistsearch(srch) {
srch = srch.toLowerCase();
var p = $('flsrchdiv').getElementsByTagName('p');
for(var i = 0;i < p.length;i++){
p[i].style.display = p[i].innerText.toLowerCase().indexOf(srch) !== -1 ? '' : 'none';
}
}
</script><?php include template('common/footer_ajax'); ?>