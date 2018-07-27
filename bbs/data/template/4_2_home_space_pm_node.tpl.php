<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); if(count($list)-1 == $key && empty($lastanchor)) { ?><a name="last"></a><?php $lastanchor=1;?><?php } ?>
<dl id="pmlist_<?php echo $value['pmid'];?>" class="bbda cl">
<?php if($value['pmtype'] == 1 || $value['authorid'] && $value['authorid'] != $_G['uid'] && $_G['setting']['pmreportuser']) { ?>
<dd class="y mtm pm_o">
<a href="javascript:;" id="pm_o_<?php echo $value['pmid'];?>" class="o" onmouseover="showMenu({'ctrlid':this.id, 'pos':'34'})">菜单</a>
<div id="pm_o_<?php echo $value['pmid'];?>_menu" class="p_pop" style="display: none;">
<ul>
<?php if($value['pmtype'] == 1) { ?>
<li><a href="javascript:;" id="a_pmdelete_<?php echo $value['pmid'];?>" onclick="ajaxget('home.php?mod=spacecp&ac=pm&op=delete&deletepm_pmid[]=<?php echo $value['pmid'];?>&touid=<?php echo $touid;?>&deletesubmit=1&handlekey=pmdeletehk_<?php echo $value['pmid'];?>', '', 'ajaxwaitid', '', 'none', 'changedeletedpm(<?php echo $value['pmid'];?>)');" title="删除">删除</a></li>
<?php } if($value['authorid'] && $value['authorid'] != $_G['uid'] && $_G['setting']['pmreportuser']) { ?>
<li><a href="home.php?mod=spacecp&amp;ac=pm&amp;op=pm_report&amp;pmid=<?php echo $value['pmid'];?>&amp;handlekey=pmreporthk_<?php echo $value['pmid'];?>" id="a_pmreport_<?php echo $value['pmid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);" title="举报">举报</a></li>
<?php } ?>
</ul>
</div>
</dd>
<?php } ?>
<dd class="m avt" <?php if(count($list)-1 == $key) { ?>id="bottom"<?php } ?>>
<?php if($value['authorid']) { ?>
<a href="home.php?mod=space&amp;uid=<?php echo $value['authorid'];?>" target="_blank"><?php echo avatar($value[authorid],small);?></a>
<?php } ?>
</dd>
<dd class="ptm">
<?php if($value['authorid']) { if($value['authorid'] == $_G['uid']) { ?>
<span class="xi2 xw1">您</span>
<?php } else { ?>
<a href="home.php?mod=space&amp;uid=<?php echo $value['authorid'];?>" target="_blank" class="xw1"><?php echo $value['author'];?></a>
<?php } ?>
 &nbsp; 
<?php } ?><br />
<?php echo $value['message'];?><br />
<span class="xg1"><?php echo dgmdate($value[dateline], 'u');?></span>
</dd>

</dl>