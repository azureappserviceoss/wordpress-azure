<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('tag');?><?php include template('common/header'); if($type != 'countitem') { ?>
<div id="ct" class="wp cl">
<h1 class="mt"><img class="vm" src="<?php echo IMGDIR;?>/tag.gif" alt="tag" /> 标签</h1>
<div class="bm">
<div class="bm_c">
<form method="post" action="misc.php?mod=tag" class="pns">
<input type="text" name="name" class="px vm" size="30" />&nbsp;
<button type="submit" class="pn vm"><em>搜索</em></button>
</form>
<div class="taglist mtm mbm">
<?php if($tagarray) { if(is_array($tagarray)) foreach($tagarray as $tag) { ?><a href="misc.php?mod=tag&amp;id=<?php echo $tag['tagid'];?>" title="<?php echo $tag['tagname'];?>" target="_blank" class="xi2"><?php echo $tag['tagname'];?></a>
<?php } } else { ?>
<p class="emp">还没有任何标签</p>
<?php } ?>
</div>
</div>
</div>
</div>
<?php } else { ?>
<?php echo $num;?>
<?php } include template('common/footer'); ?>