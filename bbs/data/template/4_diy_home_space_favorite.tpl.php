<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('space_favorite');?>
<?php if(empty($diymode)) { include template('common/header'); ?><div id="pt" class="bm cl">
<div class="z">
<a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em>
<a href="home.php?mod=space&amp;do=favorite">收藏</a>
</div>
</div>

<style id="diy_style" type="text/css"></style>
<div class="wp">
<!--[diy=diy1]--><div id="diy1" class="area"></div><!--[/diy]-->
</div>

<div id="ct" class="ct2_a wp cl">
<div class="mn">
<!--[diy=diycontenttop]--><div id="diycontenttop" class="area"></div><!--[/diy]-->
<div class="bm bw0">
<h1 class="mt bbs">
<img alt="favorite" src="<?php echo STATICURL;?>image/feed/favorite.gif" class="vm" />
<?php if($_GET['type'] == "thread") { ?>帖子
<?php } elseif($_GET['type'] == "forum") { ?>版块
<?php } elseif($_GET['type'] == "group") { ?><?php echo $_G['setting']['navs']['3']['navname'];?>
<?php } elseif($_GET['type'] == "blog") { ?>日志
<?php } elseif($_GET['type'] == "album") { ?>相册
<?php } elseif($_GET['type'] == "article") { ?>文章
<?php } else { ?>全部收藏</a><?php } ?>
</h1>

<?php } else { include template('home/space_header'); ?><div id="ct" class="wp n cl">
<div class="mn">
<div class="bm">
<h1 class="mt">收藏</h1>
<?php } if($list) { ?>
<form method="post" autocomplete="off" name="delform" id="delform" action="home.php?mod=spacecp&amp;ac=favorite&amp;op=delete&amp;type=<?php echo $_GET['type'];?>&amp;checkall=1" onsubmit="showDialog('确定要删除选中的收藏吗？', 'confirm', '', '$(\'delform\').submit();'); return false;">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="delfavorite" value="true" />
<ul id="favorite_ul" class=""><?php if(is_array($list)) foreach($list as $k => $value) { ?><li id="fav_<?php echo $k;?>" class="bbda ptm pbm">
<a class="y" id="a_delete_<?php echo $k;?>" href="home.php?mod=spacecp&amp;ac=favorite&amp;op=delete&amp;favid=<?php echo $k;?>" onclick="showWindow(this.id, this.href, 'get', 0);">删除</a>
<input type="checkbox" name="favorite[]" class="pc" value="<?php echo $k;?>" vid="<?php echo $value['id'];?>" />
<?php if($_GET['type'] == 'all') { ?><span><?php echo $value['icon'];?></span><?php } ?>
<a href="<?php echo $value['url'];?>" target="_blank"><?php echo $value['title'];?></a> <span class="xg1"><?php echo dgmdate($value[dateline], 'u');?></span>
<?php if($value['description']) { ?>
<div class="quote">
<blockquote id="quote_preview"><?php echo $value['description'];?></blockquote>
</div>
<?php } ?>
</li>
<?php } ?>
</ul>
<p class="mtm pns">
<label for="chkall" onclick="checkall(this.form, 'favorite')"><input type="checkbox" name="chkall" id="chkall" class="pc vm" />全选</label>
<button type="submit" name="delsubmit" value="true" class="pn vm"><em>删除选中收藏</em></button>
<?php if($_GET['type'] == "thread") { ?>
<button type="button" name="collectionsubmit" value="true" class="pnc vm" onclick="collection_favorite()"><em>添加到淘帖</em></button>
<?php } ?>
</p>
</form>
<?php if($multi) { ?><div class="pgs cl mtm"><?php echo $multi;?></div><?php } } else { ?>
<p class="emp">您还没有添加任何收藏</p>
<?php } if(empty($diymode)) { ?>
</div>
<!--[diy=diycontentbottom]--><div id="diycontentbottom" class="area"></div><!--[/diy]-->
</div>
<div class="appl">
<div class="tbn">
<h2 class="mt bbda">收藏</h2>
<ul>
<li<?php echo $actives['all'];?>><a href="home.php?mod=space&amp;do=favorite&amp;type=all">全部收藏</a></li>
<li<?php echo $actives['thread'];?>><a href="home.php?mod=space&amp;do=favorite&amp;type=thread">帖子</a></li>
<li<?php echo $actives['forum'];?>><a href="home.php?mod=space&amp;do=favorite&amp;type=forum">版块</a></li>
<?php if(helper_access::check_module('group')) { ?><li<?php echo $actives['group'];?>><a href="home.php?mod=space&amp;do=favorite&amp;type=group"><?php echo $_G['setting']['navs']['3']['navname'];?></a></li><?php } if(helper_access::check_module('blog')) { ?><li<?php echo $actives['blog'];?>><a href="home.php?mod=space&amp;do=favorite&amp;type=blog">日志</a></li><?php } if(helper_access::check_module('album')) { ?><li<?php echo $actives['album'];?>><a href="home.php?mod=space&amp;do=favorite&amp;type=album">相册</a></li><?php } if(helper_access::check_module('portal')) { ?><li<?php echo $actives['article'];?>><a href="home.php?mod=space&amp;do=favorite&amp;type=article">文章</a></li><?php } ?>
<?php if(!empty($_G['setting']['pluginhooks']['space_favorite_nav_extra'])) echo $_G['setting']['pluginhooks']['space_favorite_nav_extra'];?>
</ul>
</div>
</div>
</div>

<div class="wp mtn">
<!--[diy=diy3]--><div id="diy3" class="area"></div><!--[/diy]-->
</div>

<?php } else { ?>
</div>
</div>
</div>
<?php } ?>
<script type="text/javascript">
function favorite_delete(favid) {
var el = $('fav_' + favid);
if(el) {
el.style.display = "none";
}
}
<?php if($_GET['type'] == "thread") { ?>
function collection_favorite() {
var form = $('delform');
var prefix = '^favorite';
var tids = '';
for(var i = 0; i < form.elements.length; i++) {
var e = form.elements[i];		
if(e.name.match(prefix) && e.checked) {
tids += 'tids[]=' + e.getAttribute('vid') + '&';
}
}
if(tids) {
showWindow(null, 'forum.php?mod=collection&action=edit&op=addthread&' + tids);
}
}
function update_collection() {}
<?php } ?>
</script><?php include template('common/footer'); ?>