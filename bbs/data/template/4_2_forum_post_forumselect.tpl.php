<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('post_forumselect');?><?php include template('common/header'); if(empty($_GET['infloat'])) { ?>
<div id="pt" class="bm cl">
<div class="z"><a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em> <?php echo $navigation;?></div>
</div>
<div id="ct" class="wp cl">
<div class="mn">
<div class="bm bw0">
<?php } ?>

<div style="display: none">
<ul id="fs_group"><?php echo $grouplist;?></ul>
<ul id="fs_forum_common"><?php echo $commonlist;?></ul><?php if(is_array($forumlist)) foreach($forumlist as $forumid => $forum) { ?><ul id="fs_forum_<?php echo $forumid;?>"><?php echo $forum;?></ul>
<?php } if(is_array($subforumlist)) foreach($subforumlist as $forumid => $forum) { ?><ul id="fs_subforum_<?php echo $forumid;?>"><?php echo $forum;?></ul>
<?php } ?>
</div>
<h3 class="flb">
<?php if($special === null) { ?>
<em>论坛导航</em>
<?php } else { ?><?php
$actiontitle = <<<EOF

EOF;
 if($special == 1) { 
$actiontitle .= <<<EOF
发起投票
EOF;
 } elseif($special == 2) { 
$actiontitle .= <<<EOF
出售商品
EOF;
 } elseif($special == 3) { 
$actiontitle .= <<<EOF
发布悬赏
EOF;
 } elseif($special == 4) { 
$actiontitle .= <<<EOF
发起活动
EOF;
 } elseif($special == 5) { 
$actiontitle .= <<<EOF
发起辩论
EOF;
 } else { 
$actiontitle .= <<<EOF
发新帖
EOF;
 } 
$actiontitle .= <<<EOF

EOF;
?>
<em><?php echo $actiontitle;?></em>
<?php } if(!empty($_GET['infloat'])) { ?>
<span>
<a href="javascript:;" class="flbc" onclick="hideWindow('nav')" title="关闭">关闭</a>
</span>
<?php } ?>
</h3>
<div class="c"<?php if(empty($_GET['infloat'])) { ?> style="width: 620px;"<?php } ?>>
<p class="cl">
<?php if($_G['group']['allowpost'] || !$_G['uid']) { if($special === null) { ?>
<button id="postbtn" class="pn pnc y" onclick="hideWindow('nav');showWindow('newthread', 'forum.php?mod=post&action=newthread&fid=' + selectfid)" disabled="disabled"><span>发新帖</span></button>
<?php } else { ?>
<button id="postbtn" class="pn pnc y" onclick="hideWindow('nav');window.location.href='forum.php?mod=post&action=newthread&fid=' + selectfid + '&special=<?php echo $special;?>'" disabled="disabled"><span><?php echo $actiontitle;?></span></button>
<?php } } ?>
<span class="pbnv"><?php echo $_G['setting']['bbname'];?><span id="pbnv"></span> <a id="enterbtn" class="xg1" href="javascript:;" onclick="locationforums(currentblock, currentfid)">[进入版块]</a></span>
</p>
<ul class="pbl cl">
<li id="block_group"></li>
<li id="block_forum"></li>
<li id="block_subforum"></li>
</ul>
</div>

<script type="text/javascript" reload="1">
var s = '<?php if($commonfids) { ?><p><a id="commonforum" href="javascript:;" onclick="switchforums(this, 1, \'common\')" class="pbsb lightlink">常用版块</a></p><?php } ?>';
var lis = $('fs_group').getElementsByTagName('LI');
for(i = 0;i < lis.length;i++) {
var gid = lis[i].getAttribute('fid');
if($('fs_forum_' + gid)) {
s += '<p><a href="javascript:;" ondblclick="locationforums(1, ' + gid + ')" onclick="switchforums(this, 1, ' + gid + ')" class="pbsb">' + lis[i].innerHTML + '</a></p>';
}

}
$('block_group').innerHTML = s;
var lastswitchobj = null;
var selectfid = 0;
var switchforum = switchsubforum = '';

switchforums($('commonforum'), 1, 'common');

function switchforums(obj, block, fid) {
if(lastswitchobj != obj) {
if(lastswitchobj) {
lastswitchobj.parentNode.className = '';
}
obj.parentNode.className = 'pbls';
}
var s = '';
if(fid != 'common') {
$('enterbtn').className = 'xi2';
currentblock = block;
currentfid = fid;
} else {
$('enterbtn').className = 'xg1';
}
if(block == 1) {
var lis = $('fs_forum_' + fid).getElementsByTagName('LI');
for(i = 0;i < lis.length;i++) {
fid = lis[i].getAttribute('fid');
if(fid != '') {
s += '<p><a href="javascript:;" ondblclick="locationforums(2, ' + fid + '\)" onclick="switchforums(this, 2, ' + fid + ')"' + ($('fs_subforum_' + fid) ?  ' class="pbsb"' : '') + '>' + lis[i].innerHTML + '</a></p>';
}
}
$('block_forum').innerHTML = s;
$('block_subforum').innerHTML = '';
switchforum = switchsubforum = '';
selectfid = 0;
$('postbtn').setAttribute("disabled", "disabled");
$('postbtn').className = 'pn xg1 y';
} else if(block == 2) {
selectfid = fid;
if($('fs_subforum_' + fid)) {
var lis = $('fs_subforum_' + fid).getElementsByTagName('LI');
for(i = 0;i < lis.length;i++) {
fid = lis[i].getAttribute('fid');
s += '<p><a href="javascript:;" ondblclick="locationforums(3, ' + fid + ')" onclick="switchforums(this, 3, ' + fid + ')">' + lis[i].innerHTML + '</a></p>';
}
$('block_subforum').innerHTML = s;
} else {
$('block_subforum').innerHTML = '';
}
switchforum = obj.innerHTML;
switchsubforum = '';
$('postbtn').removeAttribute("disabled");
$('postbtn').className = 'pn pnc y';
} else {
selectfid = fid;
switchsubforum = obj.innerHTML;
$('postbtn').removeAttribute("disabled");
$('postbtn').className = 'pn pnc y';
}
lastswitchobj = obj;
$('pbnv').innerHTML = switchforum ? '&nbsp;&gt;&nbsp;' + switchforum + (switchsubforum ? '&nbsp;&gt;&nbsp;' + switchsubforum : '') : '';
}

function locationforums(block, fid) {
location.href = block == 1 ? 'forum.php?gid=' + fid : 'forum.php?mod=forumdisplay&fid=' + fid;
}

</script>

<?php if(empty($_GET['infloat'])) { ?>
</div>
</div>
</div>
<?php } include template('common/footer'); ?>