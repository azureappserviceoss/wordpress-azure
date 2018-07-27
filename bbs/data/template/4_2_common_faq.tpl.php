<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('faq');?><?php include template('common/header'); ?><div id="pt" class="bm cl">
<div class="z">
<a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em>
<?php if(empty($_GET['action'])) { ?>
帮助
<?php } else { ?>
<a href="misc.php?mod=faq">帮助</a><?php echo $navigation;?>
<?php } ?>
</div>
</div>

<div id="ct" class="ct2_a wp cl">
<div class="mn">
<div class="bm bw0">
<form method="post" autocomplete="off" action="misc.php?mod=faq&amp;action=search" class="y mtn pns">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="searchtype" value="all" />
<input type="text" name="keyword" size="16" value="<?php echo $keyword;?>" class="px vm" />
<button type="submit" name="searchsubmit" class="pn vm" value="yes"><em>搜索</em></button>
</form>
<?php if(empty($_GET['action'])) { ?>
<h1 class="mt mbm">全部帮助</h1>
<div class="lum"><?php if(is_array($faqparent)) foreach($faqparent as $fpid => $parent) { ?><h2 class="blocktitle"><a href="misc.php?mod=faq&amp;action=faq&amp;id=<?php echo $fpid;?>"><?php echo $parent['title'];?></a></h2>
<ul name="<?php echo $parent['title'];?>"><?php if(is_array($faqsub[$parent['id']])) foreach($faqsub[$parent['id']] as $sub) { ?><li><a href="misc.php?mod=faq&amp;action=faq&amp;id=<?php echo $sub['fpid'];?>&amp;messageid=<?php echo $sub['id'];?>"><?php echo $sub['title'];?></a></li>
<?php } ?>
</ul>
<?php } ?>
</div>
<?php } elseif($_GET['action'] == 'faq') { ?>
<h1 class="mt mbm"><?php echo $ctitle;?></h1><?php if(is_array($faqlist)) foreach($faqlist as $faq) { ?><div id="messageid<?php echo $faq['id'];?>_c" class="umh<?php if($messageid != $faq['id']) { ?> umn<?php } ?>">
<h3 onclick="toggle_collapse('messageid<?php echo $faq['id'];?>', 1, 1);"><?php echo $faq['title'];?></h3>
<div class="umh_act">
<p class="umh_cb" onclick="toggle_collapse('messageid<?php echo $faq['id'];?>', 1, 1);">[ 展开 ]</p>
</div>
</div>
<div class="um" id="messageid<?php echo $faq['id'];?>" style="<?php if($messageid != $faq['id']) { ?> display: none <?php } ?>"><?php echo $faq['message'];?></div>
<?php } } elseif($_GET['action'] == 'search') { ?>
<h1 class="mt mbm">关键字为“<span class="xi1"><?php echo $keyword;?></span>”的帮助</h1>
<?php if($faqlist) { if(is_array($faqlist)) foreach($faqlist as $faq) { ?><div class="umh schfaq"><h3><?php echo $faq['title'];?></h3></div>
<div class="um"><?php echo $faq['message'];?></div>
<?php } } else { ?>
<p class="emp">对不起，没有找到匹配结果</p>
<?php } } elseif($_GET['action'] == 'plugin' && !empty($_GET['id'])) { include(template($_GET['id']));?><?php } ?>
</div>
</div>
<div class="appl">
<div class="tbn">
<h2 class="mt bbda">帮助</h2>
<ul>
<li class="cl<?php if(empty($_GET['action'])) { ?> a<?php } ?>"><a href="misc.php?mod=faq">全部</a></li><?php if(is_array($faqparent)) foreach($faqparent as $fpid => $parent) { ?><li name="<?php echo $parent['title'];?>" class="cl<?php if($_GET['id'] == $fpid) { ?> a<?php } ?>"><a href="misc.php?mod=faq&amp;action=faq&amp;id=<?php echo $fpid;?>"><?php echo $parent['title'];?></a></li>
<?php } if(!empty($_G['setting']['plugins']['faq'])) { if(is_array($_G['setting']['plugins']['faq'])) foreach($_G['setting']['plugins']['faq'] as $id => $module) { ?><li class="cl<?php if($_GET['id'] == $id) { ?> a<?php } ?>"><a href="misc.php?mod=faq&amp;action=plugin&amp;id=<?php echo $id;?>"><?php echo $module['name'];?></a></li>
<?php } } ?>
</ul>
</div>
<?php if(!empty($_G['setting']['pluginhooks']['faq_extra'])) echo $_G['setting']['pluginhooks']['faq_extra'];?>
</div>
</div><?php include template('common/footer'); ?>