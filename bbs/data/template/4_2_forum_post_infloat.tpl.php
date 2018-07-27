<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('post_infloat');
0
|| checktplrefresh('./template/mahjong/forum/post_infloat.htm', './template/mahjong/common/seditor.htm', 1513747898, '2', './data/template/4_2_forum_post_infloat.tpl.php', './template/mahjong', 'forum/post_infloat')
|| checktplrefresh('./template/mahjong/forum/post_infloat.htm', './template/mahjong/forum/seccheck_post.htm', 1513747898, '2', './data/template/4_2_forum_post_infloat.tpl.php', './template/mahjong', 'forum/post_infloat')
|| checktplrefresh('./template/mahjong/forum/post_infloat.htm', './template/mahjong/common/seccheck.htm', 1513747898, '2', './data/template/4_2_forum_post_infloat.tpl.php', './template/mahjong', 'forum/post_infloat')
;?><?php include template('common/header'); ?><h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">
<?php if($_GET['action'] == 'newthread') { ?>发表帖子<?php } elseif($_GET['action'] == 'reply') { ?>参与/回复主题<?php } ?>
</em>
<?php if($_GET['action'] == 'newthread' && $modnewthreads) { ?><span class="needverify">需审核</span><?php } if($_GET['action'] == 'reply' && $modnewreplies) { ?><span class="needverify">需审核</span><?php } ?>
<span>
<a href="javascript:;" class="flbc" onclick="hideWindow('<?php echo $_GET['handlekey'];?>')" title="关闭">关闭</a>
</span>
</h3>

<form method="post" autocomplete="off" id="postform" action="forum.php?mod=post&amp;infloat=yes&amp;action=<?php echo $_GET['action'];?>&amp;fid=<?php echo $_G['fid'];?>&amp;extra=<?php echo $extra;?><?php if($_GET['action'] == 'newthread') { ?>&amp;topicsubmit=yes<?php } elseif($_GET['action'] == 'reply') { ?>&amp;tid=<?php echo $_G['tid'];?>&amp;replysubmit=yes<?php } ?>" onsubmit="this.message.value = parseurl(this.message.value);<?php if(!empty($_GET['infloat'])) { ?>ajaxpost('postform', 'return_<?php echo $_GET['handlekey'];?>', 'return_<?php echo $_GET['handlekey'];?>', 'onerror');return false;<?php } ?>">
<div class="c" id="floatlayout_<?php echo $_GET['action'];?>">
<div class="p_c">
<input type="hidden" name="formhash" id="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" />
<?php if($_GET['action'] == 'reply') { ?>
<input type="hidden" name="noticeauthor" value="<?php echo $noticeauthor;?>" />
<input type="hidden" name="noticetrimstr" value="<?php echo $noticetrimstr;?>" />
<input type="hidden" name="noticeauthormsg" value="<?php echo $noticeauthormsg;?>" />
<input type="hidden" name="usesig" value="<?php if($_G['group']['maxsigsize']) { ?>1<?php } else { ?>0<?php } ?>"/>
<?php if($reppid) { ?>
<input type="hidden" name="reppid" value="<?php echo $reppid;?>" />
<?php } if($_GET['reppost']) { ?>
<input type="hidden" name="reppost" value="<?php echo $_GET['reppost'];?>" />
<?php } elseif($_GET['repquote']) { ?>
<input type="hidden" name="reppost" value="<?php echo $_GET['repquote'];?>" />
<?php } } ?>
<?php if(!empty($_G['setting']['pluginhooks']['post_infloat_top'])) echo $_G['setting']['pluginhooks']['post_infloat_top'];?>
<div class="pbt cl">
<?php if($_GET['action'] == 'newthread' && ($threadsorts = $_G['forum']['threadsorts'])) { ?>
<div class="ftid">
<select name="sortid" id="sortid" width="80" change="if($('sortid').value) {switchAdvanceMode('forum.php?mod=post&action=<?php echo $_GET['action'];?>&fid=<?php echo $_G['fid'];?><?php if(!empty($_G['tid'])) { ?>&tid=<?php echo $_G['tid'];?><?php } if(!empty($pid)) { ?>&pid=<?php echo $pid;?><?php } if(!empty($modelid)) { ?>&modelid=<?php echo $modelid;?><?php } ?>&extra=<?php echo $extra;?>&sortid=' + $('sortid').value)}">
<?php if(!$sortid) { ?><option value="0">分类信息</option><?php } if(is_array($threadsorts['types'])) foreach($threadsorts['types'] as $tsortid => $name) { if(!empty($modelid) && $threadsorts['modelid'][$tsortid] == $modelid || empty($modelid)) { ?>
<option value="<?php echo $tsortid;?>"<?php if($sortid == $tsortid) { ?> selected="selected"<?php } ?>><?php echo strip_tags($name);; ?></option>
<?php } } ?>
</select>
</div>
<script type="text/javascript" reload="1">simulateSelect('sortid');</script>
<?php } if($isfirstpost && $_G['forum']['threadtypes']['types']) { ?>
<div class="ftid">
<select name="typeid" id="typeid_float" width="80">
<option value="0">选择主题分类</option><?php if(is_array($_G['forum']['threadtypes']['types'])) foreach($_G['forum']['threadtypes']['types'] as $typeid => $name) { if(empty($_G['forum']['threadtypes']['moderators'][$typeid]) || $_G['forum']['ismoderator']) { ?>
<option value="<?php echo $typeid;?>"<?php if($thread['typeid'] == $typeid) { ?> selected="selected"<?php } ?>><?php echo strip_tags($name);; ?></option>
<?php } } ?>
</select>
</div>
<script type="text/javascript" reload="1">simulateSelect('typeid_float');</script>
<?php } if($_GET['action'] != 'reply') { ?>
<span><input name="subject" id="subject" class="px" value="<?php echo $postinfo['subject'];?>" tabindex="21" style="width: 25em" /></span>
<?php } else { ?>
<span id="subjecthide" class="z">RE: <?php echo $thread['subject'];?> [<a href="javascript:;" onclick="display('subjecthide');display('subjectbox');$('subject').value='RE: <?php echo dhtmlspecialchars(str_replace('\'', '\\\'', $thread['subject'])); ?>'">修改</a>]</span>
<span id="subjectbox" style="display:none"><input name="subject" id="subject" class="px" value="" tabindex="21" style="width: 25em" /></span>
<?php } ?>
</div>
<?php if(!$isfirstpost && $thread['special'] == 5 && empty($firststand)) { ?>
<div class="pbt cl">
<div class="ftid sslt">
<select id="stand" name="stand">
<option value="">选择观点</option>
<option value="0">中立</option>
<option value="1"<?php if($stand == 1) { ?> selected<?php } ?>>正方</option>
<option value="2"<?php if($stand == 2) { ?> selected<?php } ?>>反方</option>
</select>
</div>
<script type="text/javascript" reload="1">simulateSelect('stand');</script>
</div>
<?php } if($_GET['action'] == 'reply' && $quotemessage) { ?>
<div class="pbt cl"><?php echo $quotemessage;?></div>
<?php } ?>

<div class="tedt">
<div class="bar">
<span class="y">
<a href="forum.php?mod=post&amp;action=<?php echo $_GET['action'];?>&amp;fid=<?php echo $_G['fid'];?>&amp;extra=<?php echo $extra;?><?php if($_GET['action'] == 'reply') { ?>&amp;tid=<?php echo $_G['tid'];?><?php if(!empty($_GET['reppost'])) { ?>&amp;reppost=<?php echo $_GET['reppost'];?><?php } if(!empty($_GET['repquote'])) { ?>&amp;repquote=<?php echo $_GET['repquote'];?><?php } if(!empty($page)) { ?>&amp;page=<?php echo $page;?><?php } } if($stand) { ?>&amp;stand=<?php echo $stand;?><?php } ?>" onclick="switchAdvanceMode(this.href);doane(event);">高级模式</a>
</span><?php $seditor = array('post', array('bold', 'color', 'img', 'link', 'quote', 'code', 'smilies', 'at'));?><script src="<?php echo $_G['setting']['jspath'];?>seditor.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<div class="fpd">
<?php if(in_array('bold', $seditor['1'])) { ?>
<a href="javascript:;" title="文字加粗" class="fbld"<?php if(empty($seditor['2'])) { ?> onclick="seditor_insertunit('<?php echo $seditor['0'];?>', '[b]', '[/b]');doane(event);"<?php } ?>>B</a>
<?php } if(in_array('color', $seditor['1'])) { ?>
<a href="javascript:;" title="设置文字颜色" class="fclr" id="<?php echo $seditor['0'];?>forecolor"<?php if(empty($seditor['2'])) { ?> onclick="showColorBox(this.id, 2, '<?php echo $seditor['0'];?>');doane(event);"<?php } ?>>Color</a>
<?php } if(in_array('img', $seditor['1'])) { ?>
<a id="<?php echo $seditor['0'];?>img" href="javascript:;" title="图片" class="fmg"<?php if(empty($seditor['2'])) { ?> onclick="seditor_menu('<?php echo $seditor['0'];?>', 'img');doane(event);"<?php } ?>>Image</a>
<?php } if(in_array('link', $seditor['1'])) { ?>
<a id="<?php echo $seditor['0'];?>url" href="javascript:;" title="添加链接" class="flnk"<?php if(empty($seditor['2'])) { ?> onclick="seditor_menu('<?php echo $seditor['0'];?>', 'url');doane(event);"<?php } ?>>Link</a>
<?php } if(in_array('quote', $seditor['1'])) { ?>
<a id="<?php echo $seditor['0'];?>quote" href="javascript:;" title="引用" class="fqt"<?php if(empty($seditor['2'])) { ?> onclick="seditor_menu('<?php echo $seditor['0'];?>', 'quote');doane(event);"<?php } ?>>Quote</a>
<?php } if(in_array('code', $seditor['1'])) { ?>
<a id="<?php echo $seditor['0'];?>code" href="javascript:;" title="代码" class="fcd"<?php if(empty($seditor['2'])) { ?> onclick="seditor_menu('<?php echo $seditor['0'];?>', 'code');doane(event);"<?php } ?>>Code</a>
<?php } if(in_array('smilies', $seditor['1'])) { ?>
<a href="javascript:;" class="fsml" id="<?php echo $seditor['0'];?>sml"<?php if(empty($seditor['2'])) { ?> onclick="showMenu({'ctrlid':this.id,'evt':'click','layer':2});return false;"<?php } ?>>Smilies</a>
<?php if(empty($seditor['2'])) { ?>
<script type="text/javascript" reload="1">smilies_show('<?php echo $seditor['0'];?>smiliesdiv', <?php echo $_G['setting']['smcols'];?>, '<?php echo $seditor['0'];?>');</script>
<?php } } if(in_array('at', $seditor['1']) && $_G['group']['allowat']) { ?>
<script src="<?php echo $_G['setting']['jspath'];?>at.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<a id="<?php echo $seditor['0'];?>at" href="javascript:;" title="@朋友" class="fat"<?php if(empty($seditor['2'])) { ?> onclick="seditor_menu('<?php echo $seditor['0'];?>', 'at');doane(event);"<?php } ?>>@朋友</a>
<?php } ?>
<?php echo $seditor['3'];?>
</div></div>
<div class="area">
<textarea rows="7" cols="80" name="message" id="postmessage" onKeyDown="seditor_ctlent(event, '$(\'postsubmit\').click();')" tabindex="22" class="pt"><?php echo $message;?></textarea>
</div>
</div>
<div id="seccheck_<?php echo $_GET['action'];?>">
<?php if($secqaacheck || $seccodecheck) { ?><?php
$sectpl = <<<EOF
<sec> <span id="sec<hash>" onclick="showMenu(
EOF;
 if(!empty($_G['gp_infloat'])) { 
$sectpl .= <<<EOF
{'ctrlid':this.id,'win':'{$_GET['handlekey']}'}
EOF;
 } else { 
$sectpl .= <<<EOF
this.id
EOF;
 } 
$sectpl .= <<<EOF
)"><sec></span><div id="sec<hash>_menu" class="p_pop p_opt" style="display:none"><sec></div>
EOF;
?>
<div class="mtm"><?php $sechash = !isset($sechash) ? 'S'.($_G['inajax'] ? 'A' : '').$_G['sid'] : $sechash.random(3);
$sectpl = str_replace("'", "\'", $sectpl);?><?php if($secqaacheck) { ?>
<span id="secqaa_q<?php echo $sechash;?>"></span>		
<script type="text/javascript" reload="1">updatesecqaa('q<?php echo $sechash;?>', '<?php echo $sectpl;?>', '<?php echo $_G['basescript'];?>::<?php echo CURMODULE;?>');</script>
<?php } if($seccodecheck) { ?>
<span id="seccode_c<?php echo $sechash;?>"></span>		
<script type="text/javascript" reload="1">updateseccode('c<?php echo $sechash;?>', '<?php echo $sectpl;?>', '<?php echo $_G['basescript'];?>::<?php echo CURMODULE;?>');</script>
<?php } ?></div><?php } ?>
</div>
</div>
</div>
<?php if(!empty($_G['setting']['pluginhooks']['post_infloat_middle'])) echo $_G['setting']['pluginhooks']['post_infloat_middle'];?>
<div class="o pns" id="moreconf">
<?php if($_GET['action'] == 'newthread' && $_G['setting']['sitemessage']['newthread'] || $_GET['action'] == 'reply' && $_G['setting']['sitemessage']['reply']) { ?>
<a href="javascript:;" id="custominfo" class="y" style="margin-left:5px"><img src="<?php echo IMGDIR;?>/info_small.gif" alt="帮助" /></a>
<?php } ?>
<a href="home.php?mod=spacecp&amp;ac=credit&amp;op=rule&amp;fid=<?php echo $_G['fid'];?>" class="y" target="_blank">本版积分规则</a>
<button type="submit" id="postsubmit" class="pn pnc z" value="true"<?php if(!$seccodecheck) { ?> onmouseover="checkpostrule('seccheck_<?php echo $_GET['action'];?>', 'ac=<?php echo $_GET['action'];?>&infloat=yes&handlekey=<?php echo $_GET['handlekey'];?>');this.onmouseover=null"<?php } ?> name="<?php if($_GET['action'] == 'newthread') { ?>topicsubmit<?php } elseif($_GET['action'] == 'reply') { ?>replysubmit<?php } ?>" tabindex="23"><span><?php if($_GET['action'] == 'newthread') { ?>发表帖子<?php } elseif($_GET['action'] == 'reply') { ?>参与/回复主题<?php } ?></span></button>
<?php if(!empty($_G['setting']['pluginhooks']['post_infloat_btn_extra'])) echo $_G['setting']['pluginhooks']['post_infloat_btn_extra'];?>
</div>
</form>

<script type="text/javascript" reload="1">
function succeedhandle_<?php echo $_GET['action'];?>(locationhref, message) {
<?php if($_GET['action'] == 'reply') { ?>
try {
var pid = locationhref.lastIndexOf('#pid');
if(pid != -1) {
pid = locationhref.substr(pid + 4);
ajaxget('forum.php?mod=viewthread&tid=<?php echo $_G['tid'];?>&viewpid=' + pid<?php if($_GET['from']) { ?> + '&from=<?php echo $_GET['from'];?>'<?php } ?>, 'post_new', 'ajaxwaitid', '', null, 'appendreply()');
if(replyreload) {
var reloadpids = replyreload.split(',');
for(i = 1;i < reloadpids.length;i++) {
ajaxget('forum.php?mod=viewthread&tid=<?php echo $_G['tid'];?>&viewpid=' + reloadpids[i]<?php if($_GET['from']) { ?> + '&from=<?php echo $_GET['from'];?>'<?php } ?>, 'post_' + reloadpids[i]);
}
}
} else {
showDialog(message, 'notice', '', 'location.href="' + locationhref + '"');
}
} catch(e) {
location.href = locationhref;
}
<?php } elseif($_GET['action'] == 'newthread') { ?>
var hastid = locationhref.lastIndexOf('tid=');
if(hastid == -1) {
showDialog(message, 'notice', '', 'location.href="' + locationhref + '"');
} else {
location.href = locationhref;
}
<?php } ?>
hideWindow('<?php echo $_GET['action'];?>');
}
<?php if($_GET['action'] == 'newthread' && $_G['setting']['sitemessage']['newthread'] || $_GET['action'] == 'reply' && $_G['setting']['sitemessage']['reply']) { ?>
showPrompt('custominfo', 'mouseover', '<?php if($_GET['action'] == 'newthread') { echo trim($_G['setting']['sitemessage']['newthread'][array_rand($_G['setting']['sitemessage']['newthread'])]); } elseif($_GET['action'] == 'reply') { echo trim($_G['setting']['sitemessage']['reply'][array_rand($_G['setting']['sitemessage']['reply'])]); } ?>', <?php echo $_G['setting']['sitemessage']['time'];?>);
<?php } ?>

if($('subjectbox')) {
$('postmessage').focus();
} else if($('subject')) {
$('subject').select();
$('subject').focus();
}
</script><?php include template('common/footer'); ?>