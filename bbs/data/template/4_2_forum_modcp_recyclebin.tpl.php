<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<script src="<?php echo $_G['setting']['jspath'];?>forum_moderate.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<div class="bm bw0 mdcp">
<h1 class="mt">主题管理</h1>
<ul class="tb cl">
<li><a href="<?php echo $cpscript;?>?mod=modcp&action=thread&op=thread<?php echo $forcefid;?>" hidefocus="true">版块主题</a></li>
<li><a href="<?php echo $cpscript;?>?mod=modcp&action=thread&op=post<?php echo $forcefid;?>" hidefocus="true">帖子管理</a></li>
<li class="a"><a href="<?php echo $cpscript;?>?mod=modcp&action=recyclebin<?php echo $forcefid;?>" hidefocus="true">主题回收站</a></li>
<li><a href="<?php echo $cpscript;?>?mod=modcp&action=recyclebinpost<?php echo $forcefid;?>" hidefocus="true">回帖回收站</a></li>
</ul>
<script src="<?php echo $_G['setting']['jspath'];?>calendar.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<div class="datalist">
<form method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>&op=search">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>">
<div class="exfm">
<table cellspacing="0" cellpadding="0">
<tr>
<th width="15%">版块选择:</th>
<td width="35%">
<span class="ftid">
<select name="fid" id="fid" class="ps" width="168" change="getthreadclass();">
<option value="">请选择版块</option><?php if(is_array($modforums['list'])) foreach($modforums['list'] as $id => $name) { if($modforums['recyclebins'][$id]) { ?>
<option value="<?php echo $id;?>" <?php if($id == $_G['fid']) { ?>selected<?php } ?>><?php echo $name;?></option>
<?php } } ?>
</select>
</span>
</td>
<th width="15%">帖子类型:</th>
<td width="35%">
<span class="ftid">
<select name="threadoption" id="threadoption" class="ps" width="168">
<option value="0" <?php echo $threadoptionselect['0'];?>>全部</option>
<option value="1" <?php echo $threadoptionselect['1'];?>>投票</option>
<option value="2" <?php echo $threadoptionselect['2'];?>>商品</option>
<option value="3" <?php echo $threadoptionselect['3'];?>>悬赏</option>
<option value="4" <?php echo $threadoptionselect['4'];?>>活动</option>
<option value="5" <?php echo $threadoptionselect['5'];?>>辩论</option>
<option value="999" <?php echo $threadoptionselect['999'];?>>精华</option>
<option value="888" <?php echo $threadoptionselect['888'];?>>置顶</option>
</select>
</span>
</td>
</tr>
<tr>
<th>帖子作者:</th>
<td><input type="text" name="users" class="px" size="20" value="<?php echo $result['users'];?>" style="width: 180px"/></td>
<th>发表时间范围:</th>
<td><input type="text" name="starttime" class="px" size="10" value="<?php echo $result['starttime'];?>" onclick="showcalendar(event, this);" /> 至 <input type="text" name="endtime" class="px" size="10" value="<?php echo $result['endtime'];?>" onclick="showcalendar(event, this);" /></td>
</tr>
<tr>
<th>标题关键字:</th>
<td><input type="text" name="keywords" class="px" size="20" value="<?php echo $result['keywords'];?>" style="width: 180px"/></td>
<th>点击次数范围:</th>
<td><input type="text" name="viewsmore" class="px" size="10" value="<?php echo $result['viewsmore'];?>" /> 至 <input type="text" name="viewsless" class="px" size="10" value="<?php echo $result['viewsless'];?>" /></td>
</tr>
<tr>
<th>多少天内无回复:</th>
<td><input type="text" name="noreplydays" class="px" size="20" value="<?php echo $result['noreplydays'];?>" style="width: 180px"/></td>
<th>回复次数范围:</th>
<td><input type="text" name="repliesmore" class="px" size="10" value="<?php echo $result['repliesmore'];?>" /> 至 <input type="text" name="repliesless" class="px" size="10" value="<?php echo $result['repliesless'];?>" /></td>
</tr>
<tr>
<th width="15%">主题分类:</th>
<td width="35%">
<span class="ftid" id="threadclass">
<select name="typeid" id="typeid" width="168" class="ps">
<?php if($threadclasslist) { ?>
<option value="">请选择主题分类</option><?php if(is_array($threadclasslist)) foreach($threadclasslist as $threadclass) { ?><option value="<?php echo $threadclass['typeid'];?>" <?php if($_GET['typeid'] == $threadclass['typeid']) { ?>selected<?php } ?>><?php echo $threadclass['name'];?></option>
<?php } } else { ?>
<option value="">无</option>
<?php } ?>
</select>
</span>
</td>
<th></th><td></td>
</tr>
<tr>
<td>&nbsp;</td>
<td colspan="3">
<button type="submit" name="searchsubmit" id="searchsubmit" class="pn" value="true"><strong>提交</strong></button>
</td>
</tr>
</table>
</div>
</form>
</div>

<?php if($_G['fid']) { ?>
<h2 class="mtm mbm">当前版块: <a href="forum.php?mod=forumdisplay&amp;fid=<?php echo $_G['fid'];?>" target="_blank" class="xi2"><?php echo $_G['forum']['name'];?></a></h2>
<?php if($postlist) { ?>
<div id="threadlist" class="tl">
<form method="post" autocomplete="off" name="moderate" id="moderate" action="<?php echo $cpscript;?>?mod=modcp&fid=<?php echo $_G['fid'];?>&action=<?php echo $_GET['action'];?>">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="op" value="" />
<input type="hidden" name="oldop" value="<?php echo $op;?>" />
<input type="hidden" name="dosubmit" value="submit" />
<table cellspacing="0" cellpadding="0">
<tr class="th">
<td class="icn">&nbsp;</td>
<td class="o">&nbsp;</td>
<td>&nbsp;</td>
<td class="by">作者</td>
<td class="num">回复/查看</td>
<td class="by">最后发表</td>
<td class="by">理由</td>
</tr><?php if(is_array($postlist)) foreach($postlist as $thread) { ?><tbody id="<?php echo $thread['id'];?>">
<tr>
<td class="icn">
<?php if($thread['special'] == 1) { ?>
<img src="<?php echo IMGDIR;?>/pollsmall.gif" alt="投票" />
<?php } elseif($thread['special'] == 2) { ?>
<img src="<?php echo IMGDIR;?>/tradesmall.gif" alt="商品" />
<?php } elseif($thread['special'] == 3) { ?>
<img src="<?php echo IMGDIR;?>/rewardsmall.gif" alt="悬赏" <?php if($thread['price'] < 0) { ?>class="solved"<?php } ?> />
<?php } elseif($thread['special'] == 4) { ?>
<img src="<?php echo IMGDIR;?>/activitysmall.gif" alt="活动" />
<?php } elseif($thread['special'] == 5) { ?>
<img src="<?php echo IMGDIR;?>/debatesmall.gif" alt="辩论" />
<?php } else { ?>
<img src="<?php echo IMGDIR;?>/folder_<?php echo $thread['folder'];?>.gif" />
<?php } ?>
</td>
<td class="o"><input class="pc" type="checkbox" name="moderate[]" value="<?php echo $thread['tid'];?>" /></td>
<th class="<?php echo $thread['folder'];?>">
<span id="thread_<?php echo $thread['tid'];?>"><a href="forum.php?mod=viewthread&amp;tid=<?php echo $thread['tid'];?>&amp;modthreadkey=<?php echo $thread['modthreadkey'];?>" target="_blank" <?php echo $thread['highlight'];?>><?php echo $thread['subject'];?></a></span>
<?php if($thread['readperm']) { ?> - [阅读权限 <span class="xw1"><?php echo $thread['readperm'];?></span>]<?php } if($thread['price'] > 0) { if($thread['special'] == '3') { ?>
- <span style="color: #C60">[悬赏 <span class="xw1"><?php echo $thread['price'];?></span> <?php echo $_G['setting']['extcredits'][$_G['setting']['creditstransextra']['2']]['unit'];?><?php echo $_G['setting']['extcredits'][$_G['setting']['creditstransextra']['2']]['title'];?>]</span>
<?php } else { ?>
- [售价 <span class="xw1"><?php echo $thread['price'];?></span> <?php echo $_G['setting']['extcredits'][$_G['setting']['creditstransextra']['1']]['unit'];?><?php echo $_G['setting']['extcredits'][$_G['setting']['creditstransextra']['1']]['title'];?>]
<?php } } elseif($thread['special'] == '3' && $thread['price'] < 0) { ?>
- <span style="color: #269F11">[已解决]</span>
<?php } if($thread['displayorder'] == 1) { ?>
- <span style="color: #C60">置顶I</span>
<?php } elseif($thread['displayorder'] == 2) { ?>
- <span style="color: #C60">置顶II</span>
<?php } elseif($thread['displayorder'] == 3) { ?>
- <span style="color: #C60">置顶III</span>
<?php } if($thread['attachment'] == 2) { ?>
<img src="<?php echo STATICURL;?>image/filetype/image_s.gif" alt="图片附件" align="absmiddle" />
<?php } elseif($thread['attachment'] == 1) { ?>
<img src="<?php echo STATICURL;?>image/filetype/common.gif" alt="附件" align="absmiddle" />
<?php } ?>
</th>
<td class="by">
<cite>
<?php if($thread['authorid'] && $thread['author']) { ?>
<a href="home.php?mod=space&amp;uid=<?php echo $thread['authorid'];?>" target="_blank"><?php echo $thread['author'];?></a>
<?php } else { ?>
<a href="home.php?mod=space&amp;uid=<?php echo $thread['authorid'];?>" target="_blank">匿名</a>
<?php } ?>
</cite>
<em><?php echo $thread['dateline'];?></em>
</td>
<td class="num"><strong><?php echo $thread['replies'];?></strong><em><?php echo $thread['views'];?></em></td>
<td class="by">
<cite><?php if($thread['lastposter']) { ?><a target="_blank" href="home.php?mod=space&amp;username=<?php echo $thread['lastposterenc'];?>"><?php echo $thread['lastposter'];?></a><?php } else { ?>匿名<?php } ?></cite>
<em><a target="_blank" href="forum.php?mod=redirect&amp;tid=<?php echo $thread['tid'];?>&amp;goto=lastpost"><?php echo $thread['lastpost'];?></a></em>
</td>
<td class="by"><?php echo $thread['reason'];?></td>
</tr>
</tbody>
<?php } ?>
<tbody>
<tr class="bw0_all">
<td>&nbsp;</td>
<td><input class="pc" type="checkbox" onclick="checkall(this.form, 'moderate')" name="chkall" id="chkall"/></td>
<td colspan="5" class="ptm">
<?php if($multipage) { ?><?php echo $multipage;?><?php } if($_G['group']['allowclearrecycle']) { ?>
<button onclick="modthreads('delete')" class="pn"><strong>删除</strong></button>
<?php } ?>
<button onclick="modthreads('restore')" class="pn"><strong>恢复</strong></button>
</td>
</tr>
</tbody>
</table>
</form>
</div>
<?php } if(!$total) { ?>
<p class="emp">没有找到相关主题</p>
<?php } ?>
<script type="text/javascript">
function modthreads(operation) {
document.moderate.op.value = operation;
document.moderate.submit();
}
</script>

<?php } else { ?>
<p class="xi1">请选择版块进行管理</p>
<?php } ?>
</div>
<script type="text/javascript" reload="1">
simulateSelect('fid');
simulateSelect('typeid');
simulateSelect('threadoption');
</script>