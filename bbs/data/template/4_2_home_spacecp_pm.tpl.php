<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('spacecp_pm');
0
|| checktplrefresh('./template/mahjong/home/spacecp_pm.htm', './template/mahjong/common/seditor.htm', 1511190413, '2', './data/template/4_2_home_spacecp_pm.tpl.php', './template/mahjong', 'home/spacecp_pm')
|| checktplrefresh('./template/mahjong/home/spacecp_pm.htm', './template/mahjong/common/seditor.htm', 1511190413, '2', './data/template/4_2_home_spacecp_pm.tpl.php', './template/mahjong', 'home/spacecp_pm')
|| checktplrefresh('./template/mahjong/home/spacecp_pm.htm', './template/mahjong/common/seditor.htm', 1511190413, '2', './data/template/4_2_home_spacecp_pm.tpl.php', './template/mahjong', 'home/spacecp_pm')
|| checktplrefresh('./template/mahjong/home/spacecp_pm.htm', './template/mahjong/home/space_prompt_nav.htm', 1511190413, '2', './data/template/4_2_home_spacecp_pm.tpl.php', './template/mahjong', 'home/spacecp_pm')
;?><?php include template('common/header'); if(!$_G['inajax']) { ?>
<div id="pt" class="bm cl">
<div class="z">
<a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em>
<span>通知</span> <em>&rsaquo;</em>
<a href="home.php?mod=space&amp;do=pm">消息</a>
</div>
</div>
<div id="ct" class="ct2_a wp cl">
<div class="mn">
<div class="bm bw0">
<?php } if($_GET['op'] == 'delete') { ?>

<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">删除短消息</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<?php if($uid) { ?>
<div id="<?php echo $uid;?>">
<form id="delpmform_<?php echo $uid;?>" name="delpmform_<?php echo $uid;?>" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=pm&amp;op=delete&amp;deletepm_deluid[]=<?php echo $uid;?>">
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="deletesubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">确定删除与该用户的所有私人短消息吗？</div>
<p class="o pns">
<button type="submit" name="deletesubmit_btn" value="true" class="pn pnc"><strong>确定</strong></button>
</p>
</form>
</div>
<?php } elseif($plid && $delplid) { ?>
<div id="<?php echo $plid;?>">
<form id="delpmform_<?php echo $plid;?>" name="delpmform_<?php echo $plid;?>" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=pm&amp;op=delete&amp;deletepm_delplid[]=<?php echo $plid;?>">
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="deletesubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">确定删除该群聊消息吗？</div>
<p class="o pns">
<button type="submit" name="deletesubmit_btn" value="true" class="pn pnc"><strong>确定</strong></button>
</p>
</form>
</div>
<?php } elseif($plid && $quitplid) { ?>
<div id="<?php echo $plid;?>">
<form id="delpmform_<?php echo $plid;?>" name="delpmform_<?php echo $plid;?>" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=pm&amp;op=delete&amp;deletepm_quitplid[]=<?php echo $plid;?>">
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="deletesubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">确定退出该群聊消息吗？</div>
<p class="o pns">
<button type="submit" name="deletesubmit_btn" value="true" class="pn pnc"><strong>退出</strong></button>
</p>
</form>
</div>
<?php } elseif($pmid && $delpmid) { ?>
<div id="<?php echo $pmid;?>">
<form id="delpmform_<?php echo $pmid;?>" name="delpmform_<?php echo $pmid;?>" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=pm&amp;op=delete&amp;deletepm_pmid[]=<?php echo $pmid;?>&amp;touid=<?php echo $touid;?>&amp;daterange=<?php echo $daterange;?>">
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="deletesubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">确定删除该短消息吗？</div>
<p class="o pns">
<button type="submit" name="deletesubmit_btn" value="true" class="pn pnc"><strong>确定</strong></button>
</p>
</form>
</div>
<?php } elseif($pmid && $gpmid) { ?>
<div id="<?php echo $pmid;?>">
<form id="delpmform_<?php echo $pmid;?>" name="delpmform_<?php echo $pmid;?>" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=pm&amp;op=delete&amp;deletepm_gpmid[]=<?php echo $pmid;?>">
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="deletesubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">确定删除该公共短消息吗？</div>
<p class="o pns">
<button type="submit" name="deletesubmit_btn" value="true" class="pn pnc"><strong>确定</strong></button>
</p>
</form>
</div>
<?php } if($_G['inajax']) { ?>
<script type="text/javascript">
function succeedhandle_<?php echo $_GET['handlekey'];?>(url, msg, values) {
if($('pmlist_'+values['plid'])) {
$('pmlist_'+values['plid']).style.display = 'none';
}
if($('gpmlist_'+values['gpmid'])) {
$('gpmlist_'+values['gpmid']).style.display = 'none';
}
}
</script>
<?php } } elseif($_GET['op'] == 'pm_report') { ?>

<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">短消息举报</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<div id="<?php echo $pmid;?>">
<form id="pmreportform_<?php echo $pmid;?>" name="pmreportform_<?php echo $pmid;?>" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=pm&amp;op=pm_report&amp;pmid=<?php echo $pmid;?>"  <?php if($_G['inajax']) { ?>onsubmit="ajaxpost(this.id, 'return_<?php echo $_GET['handlekey'];?>');"<?php } ?>>
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="pmreportsubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">确定举报该短消息吗？</div>
<p class="o pns">
<button type="submit" name="pmreportsubmit_btn" value="true" class="pn pnc"><strong>确定</strong></button>
</p>
</form>
</div>

<?php } elseif($_GET['op'] == 'pm_ignore') { ?>

<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">忽略</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<div id="<?php echo $plid;?>">
<form id="pmignoreform_<?php echo $plid;?>" name="pmignoreform_<?php echo $plid;?>" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=pm&amp;op=pm_ignore&amp;plid=<?php echo $plid;?>&amp;username=<?php echo $username;?>"  <?php if($_G['inajax']) { ?>onsubmit="ajaxpost(this.id, 'return_<?php echo $_GET['handlekey'];?>');"<?php } ?>>
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="pmignoresubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">确定把该用户加入忽略列表吗？</div>
<p class="o pns">
<button type="submit" name="pmignoresubmit_btn" value="true" class="pn pnc"><strong>确定</strong></button>
</p>
</form>
</div>

<?php } elseif($_GET['op'] == 'kickmember') { ?>

<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">踢出</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<div id="<?php echo $memberuid;?>">
<form id="kickmemberform_<?php echo $memberuid;?>" name="kickmemberform_<?php echo $memberuid;?>" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=pm&amp;op=kickmember&amp;plid=<?php echo $plid;?>&amp;memberuid=<?php echo $memberuid;?>"  <?php if($_G['inajax']) { ?>onsubmit="ajaxpost(this.id, 'return_<?php echo $_GET['handlekey'];?>');"<?php } ?>>
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="pmkickmembersubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">确定把该用户踢出群聊吗？</div>
<p class="o pns">
<button type="submit" name="pmkickmembersubmit_btn" value="true" class="pn pnc"><strong>确定</strong></button>
</p>
</form>
</div>

<?php } elseif($_GET['op'] == 'appendmember') { ?>

<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">加入</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<div id="appendmember">
<form id="appendmemberform" name="appendmemberform" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=pm&amp;op=appendmember&amp;plid=<?php echo $plid;?>&amp;memberusername=<?php echo $memberusername;?>"  <?php if($_G['inajax']) { ?>onsubmit="ajaxpost(this.id, 'return_<?php echo $_GET['handlekey'];?>');"<?php } ?>>
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="pmappendmembersubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">确定把该用户加入群聊吗？</div>
<p class="o pns">
<button type="submit" name="pmappendmembersubmit_btn" value="true" class="pn pnc"><strong>确定</strong></button>
</p>
</form>
</div>

<?php } elseif($_GET['op'] == 'getpmuser') { ?>
<?php echo $jsstr;?>
<?php } elseif($_GET['op'] == 'ignore') { ?>

<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">屏蔽<?php echo $username;?></em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<form id="ignoreuserform" name="ignoreuserform" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=pm&amp;op=ignore&amp;only=1"  <?php if($_G['inajax']) { ?>onsubmit="ajaxpost(this.id, 'return_<?php echo $_GET['handlekey'];?>');"<?php } ?>>
<?php if($_G['inajax']) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="ignoresubmit" value="true" />
<input type="hidden" name="ignoreuser" value="<?php echo $_GET['username'];?>" />
<input type="hidden" name="single" value="1" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">确定屏蔽该用户吗？</div>
<p class="o pns">
<button type="submit" name="deletesubmit_btn" value="true" class="pn pnc"><strong>确定</strong></button>
</p>
</form>
<?php } elseif($_GET['op'] == 'showmsg') { if($msgonly) { if(is_array($msglist)) foreach($msglist as $day => $msgarr) { ?><li class="cl">
<h4 class="xg1"><?php echo $day;?></h4>
</li><?php if(is_array($msgarr)) foreach($msgarr as $key => $value) { $class=$value['touid']==$_G['uid']?'cl':'cl pmm';?><li class="<?php echo $class;?>">
<div class="pmt"><?php echo $value['author'];?>: </div>
<div class="pmd"><?php echo $value['message'];?></div>
</li>
<?php } } } else { ?>
<div class="pm">
<h3 class="flb">
<em>正在与<?php echo $msguser;?>聊天中……<?php if($online) { ?>[在线]<?php } else { ?>[离线]<?php } ?></em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<div class="pm_tac bbda cl">
<a href="home.php?mod=space&amp;do=pm&amp;subop=view&amp;touid=<?php echo $touid;?>#last" class="y" target="_blank">查看与<?php echo $msguser;?>的聊天记录</a>
<a href="home.php?mod=space&amp;uid=<?php echo $touid;?>" target="_blank">访问<?php echo $msguser;?>的空间</a>
</div>
<div class="c">
<ul class="pmb" id="msglist"><?php if(is_array($msglist)) foreach($msglist as $day => $msgarr) { ?><li class="cl">
<h4 class="xg1"><?php echo $day;?></h4>
</li><?php if(is_array($msgarr)) foreach($msgarr as $key => $value) { $class=$value['touid']==$_G['uid']?'cl':'cl pmm';?><li class="<?php echo $class;?>">
<div class="pmt"><?php echo $value['author'];?>: </div>
<div class="pmd"><?php echo $value['message'];?></div>
</li>
<?php } } ?>
</ul>
<script type="text/javascript">
var refresh = true;
var refreshHandle = -1;
</script>
<div class="pmfm">
<form id="pmform_<?php echo $touid;?>" name="pmform_<?php echo $touid;?>" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=pm&amp;op=send&amp;touid=<?php echo $touid;?>" onsubmit="this.message.value = parseurl(this.message.value);<?php if($_G['inajax']) { ?>ajaxpost(this.id,  'return_<?php echo $_GET['handlekey'];?>');refreshMsg();<?php } ?>">
<input type="hidden" name="pmsubmit" value="true" />
<input type="hidden" name="touid" value="<?php echo $touid;?>" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<?php if($_G['inajax']) { ?>
<div id="return_<?php echo $_GET['handlekey'];?>" class="xi1" style="margin-bottom:5px"></div>
<input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" />
<?php } ?>
<div class="tedt">
<div class="bar"><?php $seditor = array('pm', array('bold', 'color', 'img', 'link', 'quote', 'code', 'smilies'));?><script src="<?php echo $_G['setting']['jspath'];?>seditor.js?<?php echo VERHASH;?>" type="text/javascript"></script>
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
<textarea rows="3" cols="80" name="message" class="pt" id="pmmessage" onkeydown="ctrlEnter(event, 'pmsubmit_btn');" autofocus></textarea>
<input type="hidden" name="messageappend" id="messageappend" value="<?php echo $messageappend;?>" />
<script type="text/javascript">$('pmmessage').focus();</script>
</div>
</div>
<div class="mtn pns cl">
 					<button type="submit" class="pn pnc" id="pmsubmit_btn"><strong>发送</strong></button>
 					<div class="pma mtn z">
<a href="javascript:;" title="刷新" onclick="refreshMsg();"><img src="<?php echo IMGDIR;?>/pm-ico5.png" alt="刷新" class="vm" /> 刷新</a>
 					</div>
</div>
</form>
<script type="text/javascript">var forumallowhtml = 0,allowhtml = 0,allowsmilies = true,allowbbcode = parseInt('<?php echo $_G['group']['allowsigbbcode'];?>'),allowimgcode = parseInt('<?php echo $_G['group']['allowsigimgcode'];?>');var DISCUZCODE = [];DISCUZCODE['num'] = '-1';DISCUZCODE['html'] = [];</script>
<script src="<?php echo $_G['setting']['jspath'];?>bbcode.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<script type="text/javascript">
var msgListObj = $('msglist');
msgListObj.scrollTop = msgListObj.scrollHeight;
function succeedhandle_<?php echo $_GET['handlekey'];?>(url, msg, values) {
var liObj = document.createElement("li");
var pmMsg = $('pmmessage');
liObj.className = 'cl pmm';
pmMsg.value = ($('messageappend').value ? $('messageappend').value + '\n' : '') + pmMsg.value;
$('messageappend').value = '';
liObj.innerHTML = '<div class="pmt"><?php echo $_G['username'];?>: </div><div class="pmd">'+bbcode2html(parseurl(pmMsg.value))+'</div>';
msgListObj.appendChild(liObj);
msgListObj.scrollTop = msgListObj.scrollHeight;
pmMsg.value = "";
showCreditPrompt();
}

function refreshMsg() {
if(refresh) {
var x = new Ajax();
x.get('home.php?mod=spacecp&ac=pm&op=showmsg&msgonly=1&touid=<?php echo $touid;?>&pmid=<?php echo $pmid;?>&inajax=1&daterange=<?php echo $daterange;?>', function(s){
msgListObj.innerHTML = s;
msgListObj.scrollTop = msgListObj.scrollHeight;
   						});
} else {
window.clearInterval(refreshHandle);
}
}
refreshHandle = window.setInterval('refreshMsg();', 8000);
hideMenu();
</script>
</div>
</div>
</div>
<?php } } elseif($_GET['op'] == 'showchatmsg') { if(is_array($list)) foreach($list as $key => $value) { ?><p class="xg1 mbn"><a href="home.php?mod=space&amp;uid=<?php echo $value['authorid'];?>" target="_blank" class="xi2"><?php echo $value['author'];?></a> &nbsp; <?php echo dgmdate($value[dateline], 'u');?></p>
<p class="mbm"><?php echo $value['message'];?></p>
<?php } } else { if(!$_G['inajax']) { ?>
<h1 class="mt"><img class="vm" src="<?php echo STATICURL;?>image/feed/pm.gif" alt="send pm" /> 发送消息</h1>
<ul class="tb cl">
<li class="y"><a href="home.php?mod=space&amp;do=pm&amp;view=inbox" class="xi2">返回消息列表</a></li>
<li<?php if(!$type) { ?> class="a"<?php } ?>><a href="home.php?mod=spacecp&amp;ac=pm">私人消息</a></li>
<li<?php if($type == 1) { ?> class="a"<?php } ?>><a href="home.php?mod=spacecp&amp;ac=pm&amp;type=1">群聊</a></li>
</ul>
<?php } else { ?>
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>">发送消息</em>
<?php if($_G['inajax']) { ?><span><a href="javascript:;" onclick="hideWindow('<?php echo $_GET['handlekey'];?>');" class="flbc" title="关闭">关闭</a></span><?php } ?>
</h3>
<?php } if(!$type) { ?>
<div id="__pmform_<?php echo $pmid;?>">
<form id="pmform_<?php echo $pmid;?>" name="pmform_<?php echo $pmid;?>" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=pm&amp;op=send&amp;touid=<?php echo $touid;?>&amp;pmid=<?php echo $pmid;?>" onsubmit="this.message.value = parseurl(this.message.value);<?php if($_G['inajax']) { ?>ajaxpost(this.id,  'return_<?php echo $_GET['handlekey'];?>');<?php } ?>">
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="pmsubmit" value="true" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<?php if($_G['inajax']) { ?>
<input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" />
<?php } ?>
<div class="c">
<script src="<?php echo $_G['setting']['jspath'];?>home_friendselector.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<script type="text/javascript">
var fs;
var clearlist = 0;
</script>
<table cellspacing="0" cellpadding="0" class="tfm pmform mtm">
<?php if(!$touid) { ?>
<tr>
<th><label for="username">收件人:</label></th>
<td>
<div class="cl">
<div class="un_selector px z cl" onclick="$('username').focus();">
<input type="text" name="username" id="username" autocomplete="off" />
</div>
<a href="javascript:;" id="showSelectBox" class="z mtn xi2 showmenu" onclick="showMenu({'showid':this.id, 'duration':3, 'pos':'34!'});fs.showPMFriend('showSelectBox_menu','selectorBox', this);" title="从好友列表中选择">选择好友</a>
</div>
<div id="username_menu" style="display: none;">
<ul id="friends" class="pmfrndl"></ul>
</div>
<div class="p_pof" id="showSelectBox_menu" unselectable="on" style="display:none;">
<div class="pbm">
<select class="ps" onchange="clearlist=1;getUser(1, this.value)">
<option value="-1">全部好友</option><?php if(is_array($friendgrouplist)) foreach($friendgrouplist as $groupid => $group) { ?><option value="<?php echo $groupid;?>"><?php echo $group;?></option>
<?php } ?>
</select>
</div>
<div id="selBox" class="ptn pbn">
<ul id="selectorBox" class="xl xl2 cl"></ul>
</div>
<div class="cl">
<button type="button" class="y pn" onclick="fs.showPMFriend('showSelectBox_menu','selectorBox', $('showSelectBox'));doane(event)"><span>关闭</span></button>
</div>
</div>

<script type="text/javascript">

var page = 1;
var gid = -1;
var showNum = 0;
var haveFriend = true;
function getUser(pageId, gid) {
page = parseInt(pageId);
gid = isUndefined(gid) ? -1 : parseInt(gid);
var x = new Ajax();
x.get('home.php?mod=spacecp&ac=friend&op=getinviteuser&inajax=1&page='+ page + '&gid=' + gid + '&' + Math.random(), function(s) {
var data = eval('('+s+')');
var singlenum = parseInt(data['singlenum']);
var maxfriendnum = parseInt(data['maxfriendnum']);
fs.addDataSource(data, clearlist);
haveFriend = singlenum && singlenum == 20 ? true : false;
if(singlenum && fs.allNumber < 20 && fs.allNumber < maxfriendnum && maxfriendnum > 20 && haveFriend) {
page++;
getUser(page);
}
});
}
function selector() {
var parameter = {'searchId':'username', 'showId':'friends', 'formId':'', 'showType':3, 'handleKey':'fs', 'selBox':'selectorBox', 'selBoxMenu':'showSelectBox_menu', 'maxSelectNumber':'20', 'selectTabId':'selectNum', 'unSelectTabId':'unSelectTab', 'maxSelectTabId':'remainNum'};
fs = new friendSelector(parameter);
var listObj = $('selBox');
listObj.onscroll = function() {
clearlist = 0;
if(this.scrollTop >= this.scrollHeight/5) {
page++;
gid = isUndefined(gid) ? -1 : parseInt(gid);
if(haveFriend) {
getUser(page, gid);
}
}
}
getUser(page);
}
selector();
</script>

<p class="d">多个用户使用逗号、分号或回车提示系统分开</p>
</td>
</tr>

<?php } ?>
<tr>
<th><label for="sendmessage">内容:</label></th>
<td>
<div class="tedt">
<div class="bar"><?php $seditor = array('send', array('bold', 'color', 'img', 'link', 'quote', 'code', 'smilies'));?><script src="<?php echo $_G['setting']['jspath'];?>seditor.js?<?php echo VERHASH;?>" type="text/javascript"></script>
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
<textarea rows="8" cols="40" name="message" class="pt" id="sendmessage" onkeydown="ctrlEnter(event, 'pmsubmit_btn');"></textarea>
</div>
</div>
</td>
</tr>
<?php if($_G['inajax']) { ?>
</table>
</div>
<p class="o pns">
<button type="submit" name="pmsubmit_btn" id="pmsubmit_btn" value="true" class="pn pnc"><strong>发送</strong></button>
</p>
<?php } else { ?>
<tr>
<th>&nbsp;</th>
<td>
<button type="submit" name="pmsubmit_btn" id="pmsubmit_btn" value="true" class="pn pnc"><strong>发送</strong></button>
</td>
</tr>
</table>
</div>
<?php } ?>
</form>
</div>
<?php } elseif($type == 1) { ?>
<div id="__pmform_<?php echo $pmid;?>">
<form id="pmform_<?php echo $pmid;?>" name="pmform_<?php echo $pmid;?>" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=pm&amp;op=send&amp;touid=<?php echo $touid;?>&amp;pmid=<?php echo $pmid;?>" onsubmit="this.message.value = parseurl(this.message.value);<?php if($_G['inajax']) { ?>ajaxpost(this.id,  'return_<?php echo $_GET['handlekey'];?>');<?php } ?>">
<input type="hidden" name="referer" value="<?php echo dreferer(); ?>" />
<input type="hidden" name="pmsubmit" value="true" />
<input type="hidden" name="type" value="1" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">
<script src="<?php echo $_G['setting']['jspath'];?>home_friendselector.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<script type="text/javascript">
var fs;
var clearlist = 0;
</script>
<table cellspacing="0" cellpadding="0" class="tfm pmform mtm">
<?php if(!$touid) { ?>
<tr>
<th><label for="subject">标题:</label></th>
<td>
<div class="un_selector px">
<input type="text" name="subject" id="subject" />
</div>
</td>
</tr>
<tr>
<th><label for="username">参与人:</label></th>
<td>
<div class="cl">
<div class="un_selector px z cl" onclick="$('username').focus();">
<input type="text" name="username" id="username" autocomplete="off" />
</div>
<a href="javascript:;" id="showSelectBox" class="z mtn xi2 showmenu" onclick="showMenu({'showid':this.id, 'duration':3, 'pos':'34!'});fs.showPMFriend('showSelectBox_menu','selectorBox', this);" title="从好友列表中选择">选择好友</a>
</div>
<div id="username_menu" style="display: none;">
<ul id="friends" class="pmfrndl"></ul>
</div>
<div class="p_pof" id="showSelectBox_menu" unselectable="on" style="display:none;">
<div class="pbm">
<select class="ps" onchange="clearlist=1;getUser(1, this.value)">
<option value="-1">全部好友</option><?php if(is_array($friendgrouplist)) foreach($friendgrouplist as $groupid => $group) { ?><option value="<?php echo $groupid;?>"><?php echo $group;?></option>
<?php } ?>
</select>
</div>
<div id="selBox" class="ptn pbn">
<ul id="selectorBox" class="xl xl2 cl"></ul>
</div>
<div class="cl">
<button type="button" class="y pn" onclick="fs.showPMFriend('showSelectBox_menu','selectorBox', $('showSelectBox'));doane(event)"><span>关闭</span></button>
</div>
</div>

<script type="text/javascript">

var page = 1;
var gid = -1;
var showNum = 0;
var haveFriend = true;
function getUser(pageId, gid) {
page = parseInt(pageId);
gid = isUndefined(gid) ? -1 : parseInt(gid);
var x = new Ajax();
x.get('home.php?mod=spacecp&ac=friend&op=getinviteuser&inajax=1&page='+ page + '&gid=' + gid + '&' + Math.random(), function(s) {
var data = eval('('+s+')');
var singlenum = parseInt(data['singlenum']);
var maxfriendnum = parseInt(data['maxfriendnum']);
fs.addDataSource(data, clearlist);
haveFriend = singlenum && singlenum == 20 ? true : false;
if(singlenum && fs.allNumber < 20 && fs.allNumber < maxfriendnum && maxfriendnum > 20 && haveFriend) {
page++;
getUser(page);
}
});
}
function selector() {
var parameter = {'searchId':'username', 'showId':'friends', 'formId':'', 'showType':3, 'handleKey':'fs', 'selBox':'selectorBox', 'selBoxMenu':'showSelectBox_menu', 'maxSelectNumber':'20', 'selectTabId':'selectNum', 'unSelectTabId':'unSelectTab', 'maxSelectTabId':'remainNum'};
fs = new friendSelector(parameter);
var listObj = $('selBox');
listObj.onscroll = function() {
clearlist = 0;
if(this.scrollTop >= this.scrollHeight/5) {
page++;
gid = isUndefined(gid) ? -1 : parseInt(gid);
if(haveFriend) {
getUser(page, gid);
}
}
}
getUser(page);
}
selector();
</script>

<p class="d">多个用户使用逗号、分号或回车提示系统分开</p>
</td>
</tr>

<?php } ?>
<tr>
<th><label for="sendmessage">内容:</label></th>
<td>
<div class="tedt">
<div class="bar"><?php $seditor = array('send', array('bold', 'color', 'img', 'link', 'quote', 'code', 'smilies'));?><script src="<?php echo $_G['setting']['jspath'];?>seditor.js?<?php echo VERHASH;?>" type="text/javascript"></script>
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
<textarea rows="8" cols="40" name="message" class="pt" id="sendmessage" onkeydown="ctrlEnter(event, 'pmsubmit_btn');"></textarea>
</div>
</div>
</td>
</tr>
<tr>
<th>&nbsp;</th>
<td>
<button type="submit" name="pmsubmit_btn" id="pmsubmit_btn" value="true" class="pn pnc"><strong>发送</strong></button>
</td>
</tr>
</table>
</div>
</form>
</div>
<?php } } if(!$_G['inajax']) { ?>
</div>
</div>
<div class="appl"><div class="tbn">
<h2 class="mt bbda">通知</h2>
<ul>
<li <?php echo $opactives['pm'];?>><em class="notice_pm"></em><a href="home.php?mod=space&amp;do=pm">消息 <?php if($newpmcount) { ?><strong class="xi1">(<?php echo $newpmcount;?>)</strong><?php } ?></a></li><?php if(is_array($_G['notice_structure'])) foreach($_G['notice_structure'] as $key => $type) { ?><li <?php echo $opactives[$key];?>><em class="notice_<?php echo $key;?>"></em><a href="home.php?mod=space&amp;do=notice&amp;view=<?php echo $key;?>"><?php echo lang('template', 'notice_'.$key)?><?php if($_G['member']['category_num'][$key]) { ?>(<?php echo $_G['member']['category_num'][$key];?>)<?php } ?></a></li>
<?php } if($_G['setting']['my_app_status']) { ?>
<li<?php echo $actives['userapp'];?>><em class="notice_userapp"></em><a href="home.php?mod=space&amp;do=notice&amp;view=userapp">应用消息<?php if($mynotice) { ?>(<?php echo $mynotice;?>)<?php } ?></a></li>
<?php } ?>
</ul>
</div></div>
</div>
<?php } include template('common/footer'); ?>