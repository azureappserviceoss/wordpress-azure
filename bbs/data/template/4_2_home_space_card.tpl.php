<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('space_card');?><?php include template('common/header'); ?><div class="card_gender_<?php echo $space['gender'];?>"><?php $encodeusername = rawurlencode($space['username']);?><?php if(!empty($_G['setting']['pluginhooks']['space_card_top'])) echo $_G['setting']['pluginhooks']['space_card_top'];?>
<div class="card_mn">
<div class="avt">
<a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>" target="_blank" title="进入<?php echo $space['username'];?>的空间"><?php echo avatar($space[uid],small);?></a>
</div>
<div class="c">
<p class="pbn cl">
<span class="y xg1" style="color:<?php echo $space['group']['color'];?>"<?php if($upgradecredit !== false) { ?> title="积分 <?php echo $space['credits'];?>, 距离下一级还需 <?php echo $upgradecredit;?> 积分"<?php } ?>><?php echo $space['group']['grouptitle'];?></span>
<strong><a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>"><?php echo $space['username'];?></a></strong>
<?php if($_G['ols'][$space['uid']]) { ?>
<img src="<?php echo IMGDIR;?>/ol.gif" alt="online" title="在线" class="vm" />&nbsp;
<?php } if($_G['setting']['verify']['enabled']) { if(is_array($_G['setting']['verify'])) foreach($_G['setting']['verify'] as $vid => $verify) { if($verify['available'] && $verify['showicon']) { if($space['verify'.$vid] == 1) { ?>
<a href="home.php?mod=spacecp&amp;ac=profile&amp;op=verify&amp;vid=<?php echo $vid;?>" target="_blank"><?php if($verify['icon']) { ?><img src="<?php echo $verify['icon'];?>" class="vm" alt="<?php echo $verify['title'];?>" title="<?php echo $verify['title'];?>" /><?php } else { ?><?php echo $verify['title'];?><?php } ?></a>&nbsp;
<?php } elseif(!empty($verify['unverifyicon'])) { ?>
<a href="home.php?mod=spacecp&amp;ac=profile&amp;op=verify&amp;vid=<?php echo $vid;?>" target="_blank"><?php if($verify['unverifyicon']) { ?><img src="<?php echo $verify['unverifyicon'];?>" class="vm" alt="<?php echo $verify['title'];?>" title="<?php echo $verify['title'];?>" /><?php } ?></a>&nbsp;
<?php } } } } ?>
</p><?php $isfriendinfo = 'home_friend_info_'.$space['uid'].'_'.$_G[uid];?><?php if($_G[$isfriendinfo]['note']) { ?>
<p class="xg1"><?php echo $_G[$isfriendinfo]['note'];?></p>
<?php } ?>
<?php if(!empty($_G['setting']['pluginhooks']['space_card_baseinfo_middle'])) echo $_G['setting']['pluginhooks']['space_card_baseinfo_middle'];?>
<div<?php if($allowupdatedoing) { $scdoingid='scdoing'.random(4);?> id="return_<?php echo $scdoingid;?>" onclick="cardUpdatedoing('<?php echo $scdoingid;?>', 0)"<?php } ?>><?php echo $space['spacenote'];?><?php if(helper_access::check_module('doing') && $allowupdatedoing) { ?> <a href="javascript:;" class="xi2">[更新记录]</a><?php } ?></div>
<?php if(helper_access::check_module('doing') && $allowupdatedoing) { ?>
<form id="<?php echo $scdoingid;?>" method="post" action="home.php?mod=spacecp&amp;ac=doing&amp;inajax=1" onsubmit="return false;" style="display:none">
<input type="hidden" name="addsubmit" value="true" />
<input type="hidden" name="fromcard" value="1" />
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<textarea name="message" class="card_msg pt xs1"><?php echo strip_tags($space['spacenote']); ?></textarea>
<p class="ptn pns cl">
<button type="button" onclick="cardSubmitdoing('<?php echo $scdoingid;?>');" class="pn"><span>保存</span></button>
<span class="pipe">|</span>
<a href="javascript:;" onclick="cardUpdatedoing('<?php echo $scdoingid;?>', 1)">取消</a>
</p>
</form>
<?php } ?>
<?php if(!empty($_G['setting']['pluginhooks']['space_card_baseinfo_bottom'])) echo $_G['setting']['pluginhooks']['space_card_baseinfo_bottom'];?>
</div>
</div>
<?php if($profiles) { ?>
<ul class="card_info"><?php if(is_array($profiles)) foreach($profiles as $value) { ?><li>
<div class="avt xg1"><?php echo $value['title'];?></div>
<p><?php echo $value['value'];?></p>
</li>
<?php } ?>
</ul>
<?php } ?>
<div class="o cl">
<?php if($space['self']) { if($_G['setting']['homepagestyle']) { ?>
<a href="home.php?mod=space&amp;diy=yes" class="xi2">装扮空间</a>
<?php } if(helper_access::check_module('wall')) { ?>
<a href="home.php?mod=space&amp;do=wall" class="xi2">查看留言</a>
<?php } ?>
<a href="home.php?mod=spacecp&amp;ac=avatar" class="xi2">编辑头像</a>
<a href="home.php?mod=spacecp&amp;ac=profile" class="xi2">更新资料</a>
<?php } else { if(helper_access::check_module('follow')) { ?>
<a href="home.php?mod=spacecp&amp;ac=follow&amp;op=<?php if(!empty($follow)) { ?>del<?php } else { ?>add<?php } ?>&amp;hash=<?php echo FORMHASH;?>&amp;fuid=<?php echo $space['uid'];?>" id="card_followmod_<?php echo $space['uid'];?>" onclick="showWindow(this.id, this.href, 'get', 0)" class="xi2"><?php if(!empty($follow)) { ?>取消收听<?php } else { ?>收听TA<?php } ?></a>
<?php } require_once libfile('function/friend');$isfriend=friend_check($space[uid]);?><?php if(!$isfriend) { ?>
<a href="home.php?mod=spacecp&amp;ac=friend&amp;op=add&amp;uid=<?php echo $space['uid'];?>&amp;handlekey=addfriendhk_<?php echo $space['uid'];?>" id="a_friend_li_<?php echo $space['uid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);" class="xi2">加为好友</a>
<?php } else { ?>
<a href="home.php?mod=spacecp&amp;ac=friend&amp;op=ignore&amp;uid=<?php echo $space['uid'];?>&amp;handlekey=ignorefriendhk_<?php echo $space['uid'];?>" id="a_ignore_<?php echo $space['uid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);" class="xi2">解除好友</a>
<?php } ?>
<a href="home.php?mod=spacecp&amp;ac=pm&amp;op=showmsg&amp;handlekey=showmsg_<?php echo $space['uid'];?>&amp;touid=<?php echo $space['uid'];?>&amp;pmid=0&amp;daterange=2" id="a_sendpm_<?php echo $space['uid'];?>" onclick="showWindow('showMsgBox', this.href, 'get', 0)" class="xi2">发送消息</a>
<a href="home.php?mod=spacecp&amp;ac=poke&amp;op=send&amp;uid=<?php echo $space['uid'];?>&amp;handlekey=propokehk_<?php echo $space['uid'];?>" id="a_poke_<?php echo $space['uid'];?>" onclick="showWindow(this.id, this.href, 'get', 0);" class="xi2">打个招呼</a>
<?php if(helper_access::check_module('wall')) { ?>
<a href="home.php?mod=space&amp;uid=<?php echo $space['uid'];?>&amp;do=wall" class="xi2">给我留言</a>
<?php } ?>

<script type="text/javascript">
function succeedhandle_card_followmod_<?php echo $space['uid'];?>(url, msg, values) {
var linkObj = $('card_followmod_'+values['fuid']);
if(linkObj) {
if(values['type'] == 'add') {
linkObj.innerHTML = '取消收听';
linkObj.href = 'home.php?mod=spacecp&ac=follow&op=del&fuid='+values['fuid'];
} else if(values['type'] == 'del') {
linkObj.innerHTML = '收听TA';
linkObj.href = 'home.php?mod=spacecp&ac=follow&op=add&hash=<?php echo FORMHASH;?>&fuid='+values['fuid'];
}
}
}
</script>
<?php } if(checkperm('allowbanuser') || checkperm('allowedituser')) { if(checkperm('allowedituser')) { ?>
<a href="<?php if($_G['adminid'] == 1) { ?>admin.php?action=members&operation=search&username=<?php echo $encodeusername;?>&submit=yes&frames=yes<?php } else { ?>forum.php?mod=modcp&action=member&op=edit&uid=<?php echo $space['uid'];?><?php } ?>" target="_blank" class="xi1">编辑用户</a>
<?php } if(checkperm('allowbanuser')) { ?>
<a href="<?php if($_G['adminid'] == 1) { ?>admin.php?action=members&operation=ban&username=<?php echo $encodeusername;?>&frames=yes<?php } else { ?>forum.php?mod=modcp&action=member&op=ban&uid=<?php echo $space['uid'];?><?php } ?>" target="_blank" class="xi1">禁止用户</a>
<?php } ?>
<a href="forum.php?mod=modcp&amp;action=thread&amp;op=post&amp;do=search&amp;searchsubmit=1&amp;users=<?php echo $encodeusername;?>" target="_blank" class="xi1">管理帖子</a>
<?php } ?>
<?php if(!empty($_G['setting']['pluginhooks']['space_card_option'])) echo $_G['setting']['pluginhooks']['space_card_option'];?>
</div>
<?php if($_G['setting']['magicstatus']) { ?>
<div class="mgc">
<?php if(!empty($_G['setting']['magics']['showip'])) { ?>
<a href="home.php?mod=magic&amp;mid=showip&amp;idtype=user&amp;id=<?php echo $encodeusername;?>" id="a_showip_li_<?php echo $space['pid'];?>" onclick="showWindow(this.id, this.href)"><img src="<?php echo STATICURL;?>/image/magic/showip.small.gif" class="vm" title="<?php echo $_G['setting']['magics']['showip'];?>" /> <?php echo $_G['setting']['magics']['showip'];?></a>
<?php } if(!empty($_G['setting']['magics']['checkonline']) && $space['uid'] != $_G['uid']) { ?>
<a href="home.php?mod=magic&amp;mid=checkonline&amp;idtype=user&amp;id=<?php echo $encodeusername;?>" id="a_repent_<?php echo $space['pid'];?>" onclick="showWindow(this.id, this.href)"><img src="<?php echo STATICURL;?>/image/magic/checkonline.small.gif" class="vm" title="<?php echo $_G['setting']['magics']['checkonline'];?>" /> <?php echo $_G['setting']['magics']['checkonline'];?></a>
<?php } ?>
<?php if(!empty($_G['setting']['pluginhooks']['space_card_magic_user'])) echo $_G['setting']['pluginhooks']['space_card_magic_user'];?>
</div>
<?php } ?>
<div class="f cl"><?php if(!empty($_G['setting']['pluginhooks']['space_card_bottom'])) echo $_G['setting']['pluginhooks']['space_card_bottom'];?></div>

<?php if($allowupdatedoing) { ?>
<script type="text/javascript">
function cardUpdatedoing(scdoing, op) {
if($(scdoing)) {
if(!op) {
$('return_' + scdoing).style.display = 'none';
$(scdoing).style.display = '';
} else {
$('return_' + scdoing).style.display = '';
$(scdoing).style.display = 'none';
}
}
}
function cardSubmitdoing(scdoing) {
ajaxpost(scdoing, 'return_' + scdoing);
$('return_' + scdoing).style.display = '';
$(scdoing).style.display = 'none';
}
</script>
<?php } ?>
</div><?php include template('common/footer'); ?>