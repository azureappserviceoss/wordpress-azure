<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('modcp');?><?php include template('common/header'); ?><div id="pt" class="bm cl">
<div class="z"><a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em>
<a href="<?php echo $_G['setting']['navs']['2']['filename'];?>"><?php echo $_G['setting']['navs']['2']['navname'];?></a> <em>&rsaquo;</em>
<a href="forum.php?mod=modcp"><?php echo $_G['setting']['navs']['2']['navname'];?>管理</a>
</div>
</div>
<div id="ct" class="ct2_a wp cl">
<div class="mn">
<?php if($script == 'noperm') { ?>
<div class="bm bw0">
<h1 class="mt">系统错误</h1>
<p>抱歉，您无此权限</p>
<p class="notice">论坛管理员在“管理面板”中权限和超级版主基本相同，如果需要更多功能，请进入 <a href="admin.php?mod=forum" target="_blank"><u>管理中心</u></a> </p>
</div>
<?php } elseif(!empty($modtpl)) { include(template($modtpl));?><?php } ?>
</div>
<div class="appl">
<div class="tbn">
<h2 class="mt bbda"><?php echo $_G['setting']['navs']['2']['navname'];?>管理</h2>
<ul>
<li<?php if($_GET['action'] == 'home') { ?> class="a cl"<?php } else { ?> class="cl"<?php } ?>><span class="y mtn"><?php echo $notenum;?></span><a href="<?php echo $cpscript;?>?mod=modcp&action=home<?php echo $forcefid;?>">内部留言</a></li>
<?php if($modforums['fids']) { if($_G['group']['allowmodpost'] || $_G['group']['allowmoduser']) { ?>
<li<?php if($_GET['action'] == 'moderate') { ?> class="a cl"<?php } else { ?> class="cl"<?php } ?>><span class="y mtn"><?php echo $modnum;?></span><a href="<?php echo $cpscript;?>?mod=modcp&action=moderate&op=<?php if($_G['group']['allowmodpost']) { ?>threads<?php echo $forcefid;?><?php } else { ?>members<?php } ?>">审核</a></li>
<?php } } if(!empty($_G['setting']['plugins']['modcp_base'])) { if(is_array($_G['setting']['plugins']['modcp_base'])) foreach($_G['setting']['plugins']['modcp_base'] as $id => $module) { ?><li<?php if($_GET['id'] == $id) { ?> class="a"<?php } ?>><a href="<?php echo $cpscript;?>?mod=modcp&action=plugin&op=base&id=<?php echo $id;?><?php echo $forcefid;?>"><?php echo $module['name'];?></a></li>
<?php } } if($_G['group']['allowedituser'] || $_G['group']['allowbanuser'] || $_G['group']['allowbanvisituser'] || $_G['group']['allowbanip']) { if($_G['group']['allowbanuser'] || $_G['group']['allowbanvisituser']) { ?><li<?php if($_GET['action'] == 'member' && $op == 'ban') { ?> class="a"<?php } ?>><a href="<?php echo $cpscript;?>?mod=modcp&action=member&op=ban<?php echo $forcefid;?>">禁止用户</a></li><?php } if($_G['group']['allowbanip']) { ?><li<?php if($_GET['action'] == 'member' && $op == 'ipban') { ?> class="a"<?php } ?>><a href="<?php echo $cpscript;?>?mod=modcp&action=member&op=ipban<?php echo $forcefid;?>">禁止 IP</a></li><?php } if($modforums['fids']) { ?><li<?php if($_GET['action'] == 'forumaccess') { ?> class="a"<?php } ?>><a href="<?php echo $cpscript;?>?mod=modcp&action=forumaccess<?php echo $forcefid;?>">用户权限</a></li><?php } if($_G['group']['allowedituser']) { ?><li<?php if($_GET['action'] == 'member' && $op == 'edit') { ?> class="a"<?php } ?>><a href="<?php echo $cpscript;?>?mod=modcp&action=member&op=edit<?php echo $forcefid;?>">编辑用户</a></li><?php } } if($modforums['fids']) { ?>
<li<?php if($_GET['action'] == 'thread' || $_GET['action'] == 'recyclebin') { ?> class="a"<?php } ?>><a href="<?php echo $cpscript;?>?mod=modcp&action=thread&op=thread<?php echo $forcefid;?>">主题管理</a></li>
<?php if($_G['group']['allowrecommendthread']) { ?><li<?php if($_GET['action'] == 'forum' && $op == 'recommend') { ?> class="a"<?php } ?>><a href="<?php echo $cpscript;?>?mod=modcp&action=forum&op=recommend&show=all<?php echo $forcefid;?>">推荐主题</a></li><?php } if($_G['group']['alloweditforum']) { ?><li<?php if($_GET['action'] == 'forum' && $op == 'editforum') { ?> class="a"<?php } ?>><a href="<?php echo $cpscript;?>?mod=modcp&action=forum&op=editforum<?php echo $forcefid;?>">版块编辑</a></li><?php } } if($_G['group']['allowpostannounce'] || $_G['group']['allowviewlog']) { if($_G['group']['allowpostannounce']) { ?><li<?php if($_GET['action'] == 'announcement') { ?> class="a"<?php } ?>><a href="<?php echo $cpscript;?>?mod=modcp&action=announcement<?php echo $forcefid;?>">公告</a></li><?php } if($_G['group']['allowviewlog']) { ?><li<?php if($_GET['action'] == 'log') { ?> class="a"<?php } ?>><a href="<?php echo $cpscript;?>?mod=modcp&action=log<?php echo $forcefid;?>">管理日志</a></li><?php } } if(!empty($_G['setting']['plugins']['modcp_tools'])) { if(is_array($_G['setting']['plugins']['modcp_tools'])) foreach($_G['setting']['plugins']['modcp_tools'] as $id => $module) { ?><li<?php if($_GET['id'] == $id) { ?> class="a"<?php } ?>><a href="<?php echo $cpscript;?>?mod=modcp&action=plugin&op=tools&id=<?php echo $id;?>"><?php echo $module['name'];?></a></li>
<?php } } ?>
<li<?php if($_GET['action'] == 'report') { ?> class="a"<?php } ?>><a href="<?php echo $cpscript;?>?mod=modcp&action=report<?php echo $forcefid;?>">管理举报</a></li>
<li><a href="<?php if($forcefid) { ?>forum.php?mod=forumdisplay<?php echo $forcefid;?><?php } else { ?>forum.php<?php } ?>">返回论坛</a></li>
<li><a href="<?php echo $cpscript;?>?mod=modcp&action=logout">退出</a></li>
</ul>
</div>
</div>
</div><?php include template('common/footer'); ?>