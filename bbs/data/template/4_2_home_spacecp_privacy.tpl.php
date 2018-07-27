<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('spacecp_privacy');
0
|| checktplrefresh('./template/mahjong/home/spacecp_privacy.htm', './template/mahjong/home/spacecp_header.htm', 1512621004, '2', './data/template/4_2_home_spacecp_privacy.tpl.php', './template/mahjong', 'home/spacecp_privacy')
|| checktplrefresh('./template/mahjong/home/spacecp_privacy.htm', './template/mahjong/home/spacecp_footer.htm', 1512621004, '2', './data/template/4_2_home_spacecp_privacy.tpl.php', './template/mahjong', 'home/spacecp_privacy')
|| checktplrefresh('./template/mahjong/home/spacecp_privacy.htm', './template/mahjong/home/spacecp_header_name.htm', 1512621004, '2', './data/template/4_2_home_spacecp_privacy.tpl.php', './template/mahjong', 'home/spacecp_privacy')
|| checktplrefresh('./template/mahjong/home/spacecp_privacy.htm', './template/mahjong/home/spacecp_header_name.htm', 1512621004, '2', './data/template/4_2_home_spacecp_privacy.tpl.php', './template/mahjong', 'home/spacecp_privacy')
;?><?php include template('common/header'); ?><div id="pt" class="bm cl">
<div class="z">
<a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em>
<a href="home.php?mod=spacecp">设置</a> <em>&rsaquo;</em><?php if($actives['profile']) { ?>
个人资料
<?php } elseif($actives['verify']) { ?>
认证
<?php } elseif($actives['avatar']) { ?>
修改头像
<?php } elseif($actives['credit']) { ?>
积分
<?php } elseif($actives['usergroup']) { ?>
用户组
<?php } elseif($actives['privacy']) { ?>
隐私筛选
<?php } elseif($actives['sendmail']) { ?>
邮件提醒
<?php } elseif($actives['password']) { ?>
密码安全
<?php } elseif($actives['promotion']) { ?>
访问推广
<?php } elseif($actives['plugin']) { ?>
<?php echo $_G['setting']['plugins'][$pluginkey][$_GET['id']]['name'];?>
<?php } ?></div>
</div>
<div id="ct" class="ct2_a wp cl">
<div class="mn">
<div class="bm bw0">
<h1 class="mt"><?php if($actives['profile']) { ?>
个人资料
<?php } elseif($actives['verify']) { ?>
认证
<?php } elseif($actives['avatar']) { ?>
修改头像
<?php } elseif($actives['credit']) { ?>
积分
<?php } elseif($actives['usergroup']) { ?>
用户组
<?php } elseif($actives['privacy']) { ?>
隐私筛选
<?php } elseif($actives['sendmail']) { ?>
邮件提醒
<?php } elseif($actives['password']) { ?>
密码安全
<?php } elseif($actives['promotion']) { ?>
访问推广
<?php } elseif($actives['plugin']) { ?>
<?php echo $_G['setting']['plugins'][$pluginkey][$_GET['id']]['name'];?>
<?php } ?></h1>
<!--don't close the div here--><?php if(!empty($_G['setting']['pluginhooks']['spacecp_privacy_top'])) echo $_G['setting']['pluginhooks']['spacecp_privacy_top'];?>
<ul class="tb cl">
<li<?php echo $opactives['base'];?>><a href="home.php?mod=spacecp&amp;ac=privacy&amp;op=base">个人隐私设置</a></li>
<?php if(helper_access::check_module('feed')) { ?>
<li<?php echo $opactives['feed'];?>><a href="home.php?mod=spacecp&amp;ac=privacy&amp;op=feed">个人动态发布设置</a></li>
<li<?php echo $opactives['filter'];?>><a href="home.php?mod=spacecp&amp;ac=privacy&amp;op=filter">动态筛选</a></li>
<?php } ?>
</ul>
<form method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=privacy&amp;op=<?php echo $operation;?>">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />

<?php if($operation == 'base') { ?>
<p class="tbmu mbm">您可以完全控制哪些人可以看到您的主页上面的内容</p>
<table cellspacing="0" cellpadding="0" class="tfm">

<tr>
<th>好友列表</th>
<td>
<select name="privacy[view][friend]">
<option value="0"<?php echo $sels['view']['friend']['0'];?>>公开</option>
<option value="1"<?php echo $sels['view']['friend']['1'];?>>好友可见</option>
<option value="2"<?php echo $sels['view']['friend']['2'];?>>保密</option>
<option value="3"<?php echo $sels['view']['friend']['3'];?>>仅注册用户可见</option>
</select>
</td>
</tr>
<?php if(helper_access::check_module('wall')) { ?>
<tr>
<th>留言板</th>
<td>
<select name="privacy[view][wall]">
<option value="0"<?php echo $sels['view']['wall']['0'];?>>公开</option>
<option value="1"<?php echo $sels['view']['wall']['1'];?>>好友可见</option>
<option value="2"<?php echo $sels['view']['wall']['2'];?>>保密</option>
<option value="3"<?php echo $sels['view']['wall']['3'];?>>仅注册用户可见</option>
</select>
</td>
</tr>
<?php } if(helper_access::check_module('feed')) { ?>
<tr>
<th>动态</th>
<td>
<select name="privacy[view][home]">
<option value="0"<?php echo $sels['view']['home']['0'];?>>公开</option>
<option value="1"<?php echo $sels['view']['home']['1'];?>>好友可见</option>
<option value="3"<?php echo $sels['view']['home']['3'];?>>仅注册用户可见</option>
</select>
</td>
</tr>
<?php } if(helper_access::check_module('doing')) { ?>
<tr>
<th>记录</th>
<td>
<select name="privacy[view][doing]">
<option value="0"<?php echo $sels['view']['doing']['0'];?>>公开</option>
<option value="1"<?php echo $sels['view']['doing']['1'];?>>好友可见</option>
<option value="3"<?php echo $sels['view']['doing']['3'];?>>仅注册用户可见</option>
</select>
<p class="d">本隐私设置仅在其他用户查看您主页时有效<br />在全站的记录列表中可能会出现您的记录</p>
</td>
</tr>
<?php } if(helper_access::check_module('blog')) { ?>
<tr>
<th>日志</th>
<td>
<select name="privacy[view][blog]">
<option value="0"<?php echo $sels['view']['blog']['0'];?>>公开</option>
<option value="1"<?php echo $sels['view']['blog']['1'];?>>好友可见</option>
<option value="3"<?php echo $sels['view']['blog']['3'];?>>仅注册用户可见</option>
</select>
<p class="d">本隐私设置仅在其他用户查看您主页时有效<br />相关浏览权限需要在发表时单独设置方可完全生效</p>
</td>
</tr>
<?php } if(helper_access::check_module('album')) { ?>
<tr>
<th>相册</th>
<td>
<select name="privacy[view][album]">
<option value="0"<?php echo $sels['view']['album']['0'];?>>公开</option>
<option value="1"<?php echo $sels['view']['album']['1'];?>>好友可见</option>
<option value="3"<?php echo $sels['view']['album']['3'];?>>仅注册用户可见</option>
</select>
<p class="d">本隐私设置仅在其他用户查看您主页时有效<br />相关浏览权限需要在发表时单独设置方可完全生效</p>
</td>
</tr>
<?php } if(helper_access::check_module('share')) { ?>
<tr>
<th>分享</th>
<td>
<select name="privacy[view][share]">
<option value="0"<?php echo $sels['view']['share']['0'];?>>公开</option>
<option value="1"<?php echo $sels['view']['share']['1'];?>>好友可见</option>
<option value="3"<?php echo $sels['view']['share']['3'];?>>仅注册用户可见</option>
</select>
<p class="d">本隐私设置仅在其他用户查看您主页时有效<br />相关浏览权限需要在发表时单独设置方可完全生效</p>
</td>
</tr>
<?php } if($_G['setting']['videophoto'] && $space['videophotostatus']) { ?>
<tr>
<th>&nbsp;</th>
<td><img src="<?php echo IMGDIR;?>/videophoto.gif" alt="" class="vm" /> 您已经通过视频认证，对于没有通过视频认证的用户，您可以设置以下权限 :</td>
</tr>
<tr>
<th>查看认证照片</th>
<td>
<select name="privacy[view][videoviewphoto]">
<option value="0"<?php echo $sels['view']['videoviewphoto']['0'];?>>站点默认设置</option>
<option value="1"<?php echo $sels['view']['videoviewphoto']['1'];?>>允许 </option>
<option value="2"<?php echo $sels['view']['videoviewphoto']['2'];?>>禁止</option>
</select>
</td>
</tr>
<?php } ?>
<?php if(!empty($_G['setting']['pluginhooks']['spacecp_privacy_base_extra'])) echo $_G['setting']['pluginhooks']['spacecp_privacy_base_extra'];?>
<tr>
<th>&nbsp;</th>
<td><button type="submit" name="privacysubmit" value="true" class="pn pnc" /><strong>保存</strong></button></td>
</tr>
</table>

<?php } elseif($operation == 'feed') { ?>
<p class="tbmu mbm">系统会将您的各项动作反映到个人动态里，方便朋友了解您的动态。<br />您可以控制是否在下列动作发生时，在个人动态里发布相关信息 </p>
<table cellspacing="0" cellpadding="0" id="feed" class="tfm">
<tr>
<th>&nbsp;</th>
<td class="pcl">
<label><input type="checkbox" class="pc" name="privacy[feed][doing]" value="1"<?php echo $sels['feed']['doing'];?> />记录</label>
<label><input type="checkbox" class="pc" name="privacy[feed][blog]" value="1"<?php echo $sels['feed']['blog'];?> />撰写日志</label>
<label><input type="checkbox" class="pc" name="privacy[feed][upload]" value="1"<?php echo $sels['feed']['upload'];?> />上传图片</label>
<label><input type="checkbox" class="pc" name="privacy[feed][share]" value="1"<?php echo $sels['feed']['share'];?> />添加分享</label>
<label><input type="checkbox" class="pc" name="privacy[feed][friend]" value="1"<?php echo $sels['feed']['friend'];?> />添加好友</label>
<label><input type="checkbox" class="pc" name="privacy[feed][comment]" value="1"<?php echo $sels['feed']['comment'];?> />发表评论/留言</label>
<label><input type="checkbox" class="pc" name="privacy[feed][show]" value="1"<?php echo $sels['feed']['show'];?> />竞价排名</label>
<label><input type="checkbox" class="pc" name="privacy[feed][credit]" value="1"<?php echo $sels['feed']['credit'];?> />积分消费</label>
<label><input type="checkbox" class="pc" name="privacy[feed][invite]" value="1"<?php echo $sels['feed']['invite'];?> />邀请好友</label>
<label><input type="checkbox" class="pc" name="privacy[feed][task]" value="1"<?php echo $sels['feed']['task'];?> />完成任务</label>
<label><input type="checkbox" class="pc" name="privacy[feed][profile]" value="1"<?php echo $sels['feed']['profile'];?> />更新个人资料</label>
<label><input type="checkbox" class="pc" name="privacy[feed][click]" value="1"<?php echo $sels['feed']['click'];?> />对日志/图片表态</label>
<label><input type="checkbox" class="pc" name="privacy[feed][newthread]" value="1"<?php echo $sels['feed']['newthread'];?> />论坛发帖</label>
<label><input type="checkbox" class="pc" name="privacy[feed][newreply]" value="1"<?php echo $sels['feed']['newreply'];?> />论坛回帖</label>
</td>
</tr>
<?php if(!empty($_G['setting']['pluginhooks']['spacecp_privacy_feed_extra'])) echo $_G['setting']['pluginhooks']['spacecp_privacy_feed_extra'];?>
<tr>
<th>&nbsp;</th>
<td><button type="submit" name="privacysubmit" value="true" class="pn pnc" /><strong>保存</strong></button></td>
</tr>
</table>

<?php } else { $iconnames['wall'] = '留言板';
$iconnames['piccomment'] = '图片评论';
$iconnames['blogcomment'] = '日志评论';
$iconnames['sharecomment'] = '分享评论';
$iconnames['magic'] = '道具';
$iconnames['sharenotice'] = '分享通知';
$iconnames['clickblog'] = '日志表态';
$iconnames['clickpic'] = '图片表态';
$iconnames['credit'] = '积分';
$iconnames['doing'] = '记录';
$iconnames['pcomment'] = '话题点评';
$iconnames['post'] = '话题回复';
$iconnames['show'] = '排行榜';
$iconnames['task'] = '任务';
$iconnames['goods'] = '商品';
$iconnames['group'] = $_G[setting][navs][3][navname];
$iconnames['thread'] = '话题';
$iconnames['system'] = '系统';
$iconnames['friend'] = '好友';
$iconnames['debate'] = '辩论';
$iconnames['album'] = '相册';
$iconnames['blog'] = '日志';
$iconnames['poll'] = '投票';
$iconnames['activity'] = '活动';
$iconnames['reward'] = '悬赏';
$iconnames['share'] = '分享';
$iconnames['profile'] = '更新个人资料';
$iconnames['pusearticle'] = '生成文章';?><table cellspacing="0" cellpadding="0" class="tfm bbda">
<caption>
<h2 class="ptw pbn xs2">筛选规则一：屏蔽指定用户组的动态</h2>
<p class="xg1">您可以决定屏蔽哪些用户组的动态，屏蔽用户组内的组员所发布的动态都将被屏蔽掉(仅限查看好友的动态时生效) </p>
</caption>
<tr>
<th>&nbsp;</th>
<td class="pcl"><?php if(is_array($groups)) foreach($groups as $key => $value) { ?><label><input type="checkbox" class="pc" name="privacy[filter_gid][<?php echo $key;?>]" value="<?php echo $key;?>"<?php if(isset($space['privacy']['filter_gid'][$key])) { ?> checked="checked"<?php } ?> /><?php echo $value;?></label>
<?php } ?>
</td>
</tr>
<tr>
<th>&nbsp;</th>
<td>
<button type="submit" name="privacy2submit" value="true" class="pn pnc" /><strong>保存</strong></button>
<p class="d">您可以在自己的<a href="home.php?mod=space&amp;do=friend">好友列表</a>中，对好友进行分组，并可以对用户组进行改名 </span>
</td>
</tr>
</table>

<table cellspacing="0" cellpadding="0" class="tfm bbda">
<caption>
<h2 class="ptw pbn xs2">筛选规则二：屏蔽指定好友指定类型的动态</h2>
<p class="xg1">点击一下首页好友动态列表后面的屏蔽标志，就可以屏蔽指定好友指定类型的动态了。<br />下面列出的是您已经屏蔽的动态类型识别名和好友名，您可以选择是否取消屏蔽 </p>
</caption>
<?php if($icons) { ?>
<tr>
<th>&nbsp;</th>
<td class="pcl"><?php if(is_array($icons)) foreach($icons as $key => $icon) { $uid = $uids[$key];$icon_uid="$icon|$uid";?><label>
<?php if(is_numeric($icon)) { ?>
<img src="http://appicon.manyou.com/icons/<?php echo $icon;?>" alt="" class="vm" />
<?php } else { ?>
<img src="<?php echo STATICURL;?>image/feed/<?php echo $icon;?>.gif" alt="" class="vm" />
<?php } ?>
<input type="checkbox" class="pc" name="privacy[filter_icon][<?php echo $icon_uid;?>]" value="1" checked="checked" /> 
<?php if(isset($iconnames[$icon])) { ?><?php echo $iconnames[$icon];?><?php } else { ?><?php echo $icon;?><?php } ?> (<?php if($users[$uid]) { ?><a href="home.php?mod=space&amp;uid=<?php echo $uid;?>" target="_blank"><?php echo $users[$uid];?></a><?php } else { ?>全部好友<?php } ?>)
</label>
<?php } ?>
</td>
</tr>
<tr>
<th>&nbsp;</th>
<td><button type="submit" name="privacy2submit" value="true" class="pn pnc" /><strong>保存</strong></button></td>
</tr>
<?php } else { ?>
<tr>
<th>&nbsp;</th>
<td class="d">现在还没有屏蔽的动态类型</td>
</tr>
<?php } ?>
</table>

<table cellspacing="0" cellpadding="0" class="tfm">
<caption>
<h2 class="ptw pbn xs2">筛选规则三：屏蔽指定好友指定类型的提醒</h2>
<p class="xg1">点击一下通知列表后面的屏蔽标志，就可以屏蔽指定好友指定类型的通知了。<br />下面列出的是您已经屏蔽的通知类型和好友名，您可以选择是否取消屏蔽 </p>
</caption>
<?php if($types) { ?>
<tr>
<th>&nbsp;</th>
<td><?php if(is_array($types)) foreach($types as $key => $type) { $uid = $uids[$key];$type_uid="$type|$uid";?><label>
<input type="checkbox" class="pc" name="privacy[filter_note][<?php echo $type_uid;?>]" value="1" checked="checked" />
<?php if(isset($iconnames[$type])) { ?><?php echo $iconnames[$type];?><?php } else { ?><?php echo $type;?><?php } ?> (<?php if($users[$uid]) { ?><a href="home.php?mod=space&amp;uid=<?php echo $uid;?>" target="_blank"><?php echo $users[$uid];?></a><?php } else { ?>全部好友<?php } ?>)
</label>
<?php } ?>
</td>
</tr>
<tr>
<th>&nbsp;</th>
<td><button type="submit" name="privacy2submit" value="true" class="pn pnc" /><strong>保存</strong></button></td>
</tr>
<?php } else { ?>
<tr>
<th>&nbsp;</th>
<td class="d">现在还没有屏蔽的动态类型</td>
</tr>
<?php } ?>
</table>
<?php } ?>
</form>
<?php if(!empty($_G['setting']['pluginhooks']['spacecp_privacy_bottom'])) echo $_G['setting']['pluginhooks']['spacecp_privacy_bottom'];?>
</div>
</div>
<div class="appl"><div class="tbn">
<h2 class="mt bbda">设置</h2>
<ul>
<li<?php echo $actives['avatar'];?>><a href="home.php?mod=spacecp&amp;ac=avatar">修改头像</a></li>
<li<?php echo $actives['profile'];?>><a href="home.php?mod=spacecp&amp;ac=profile">个人资料</a></li>
<?php if($_G['setting']['verify']['enabled'] && allowverify() || $_G['setting']['my_app_status'] && $_G['setting']['videophoto']) { ?>
<li<?php echo $actives['verify'];?>><a href="<?php if($_G['setting']['verify']['enabled']) { ?>home.php?mod=spacecp&ac=profile&op=verify<?php } else { ?>home.php?mod=spacecp&ac=videophoto<?php } ?>">认证</a></li>
<?php } ?>
<li<?php echo $actives['credit'];?>><a href="home.php?mod=spacecp&amp;ac=credit">积分</a></li>
<li<?php echo $actives['usergroup'];?>><a href="home.php?mod=spacecp&amp;ac=usergroup">用户组</a></li>
<li<?php echo $actives['privacy'];?>><a href="home.php?mod=spacecp&amp;ac=privacy">隐私筛选</a></li>

<?php if($_G['setting']['sendmailday']) { ?><li<?php echo $actives['sendmail'];?>><a href="home.php?mod=spacecp&amp;ac=sendmail">邮件提醒</a></li><?php } ?>
<li<?php echo $actives['password'];?>><a href="home.php?mod=spacecp&amp;ac=profile&amp;op=password">密码安全</a></li>

<?php if($_G['setting']['creditspolicy']['promotion_visit'] || $_G['setting']['creditspolicy']['promotion_register']) { ?>
<li<?php echo $actives['promotion'];?>><a href="home.php?mod=spacecp&amp;ac=promotion">访问推广</a></li>
<?php } if(!empty($_G['setting']['plugins']['spacecp'])) { if(is_array($_G['setting']['plugins']['spacecp'])) foreach($_G['setting']['plugins']['spacecp'] as $id => $module) { if(!$module['adminid'] || ($module['adminid'] && $_G['adminid'] > 0 && $module['adminid'] >= $_G['adminid'])) { ?><li<?php if($_GET['id'] == $id) { ?> class="a"<?php } ?>><a href="home.php?mod=spacecp&amp;ac=plugin&amp;id=<?php echo $id;?>"><?php echo $module['name'];?></a></li><?php } } } ?>
</ul>
</div></div>
</div><?php include template('common/footer'); ?>