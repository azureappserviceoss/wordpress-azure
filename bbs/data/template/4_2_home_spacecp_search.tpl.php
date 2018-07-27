<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('spacecp_search');
0
|| checktplrefresh('./template/mahjong/home/spacecp_search.htm', './template/mahjong/home/space_friend_nav.htm', 1512621098, '2', './data/template/4_2_home_spacecp_search.tpl.php', './template/mahjong', 'home/spacecp_search')
;?><?php include template('common/header'); ?><div id="pt" class="bm cl">
<div class="z">
<a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em>
<a href="home.php?mod=space&amp;do=friend">好友</a> <em>&rsaquo;</em>
查找好友
</div>
</div>
<div id="ct" class="ct2_a wp cl">
<div class="mn">
<div class="bm bw0">
<h1 class="mt"><img alt="friend" src="<?php echo STATICURL;?>image/feed/friend.gif" class="vm" /> 查找好友</h1>
<ul class="tb cl">
<li<?php if($_GET['op'] == 'sex') { ?> class="a"<?php } ?>><a href="home.php?mod=spacecp&amp;ac=search&amp;op=sex">查找男女朋友</a></li>
<li<?php if($_GET['op'] == 'reside') { ?> class="a"<?php } ?>><a href="home.php?mod=spacecp&amp;ac=search&amp;op=reside">查找同城的人</a></li>
<li<?php if($_GET['op'] == 'birth') { ?> class="a"<?php } ?>><a href="home.php?mod=spacecp&amp;ac=search&amp;op=birth">查找老乡</a></li>
<li<?php if($_GET['op'] == 'birthyear') { ?> class="a"<?php } ?>><a href="home.php?mod=spacecp&amp;ac=search&amp;op=birthyear">查找同年同月同日生的人</a></li>
<?php if($fields['graduateschool'] || $fields['education']) { ?>
<li<?php if($_GET['op'] == 'edu') { ?> class="a"<?php } ?>><a href="home.php?mod=spacecp&amp;ac=search&amp;op=edu">查找您的同学</a></li>
<?php } if($fields['occupation'] || $fields['title']) { ?>
<li<?php if($_GET['op'] == 'work') { ?> class="a"<?php } ?>><a href="home.php?mod=spacecp&amp;ac=search&amp;op=work">查找您的同事</a></li>
<?php } ?>
<li<?php if($_GET['op'] == '') { ?> class="a"<?php } ?>><a href="home.php?mod=spacecp&amp;ac=search">高级方式查找</a></li>
</ul>
<?php if(!empty($_GET['searchsubmit'])) { if(empty($list)) { ?>
<div class="emp">没有找到相关用户<a href="home.php?mod=spacecp&amp;ac=search">换个搜索条件试试</a></div>
<?php } else { ?>
<div class="tbmu">以下是查找到的用户列表(最多显示 100 个)，您还可以<a href="home.php?mod=spacecp&amp;ac=search">换个搜索条件试试</a></div><?php include template('home/space_list'); } } else { ?>
<div class="ptm scf">
<?php if($_GET['op'] == 'sex') { ?>
<h2>查找男女朋友</h2>
<div id="s_sex" class="bm bmn">
<form action="home.php" method="get">
<table cellpadding="0" cellspacing="0" class="tfm"><?php if(is_array(array('affectivestatus','lookingfor','zodiac','constellation'))) foreach(array('affectivestatus','lookingfor','zodiac','constellation') as $key) { if($fields[$key]) { ?>
<tr>
<th><?php echo $fields[$key]['title'];?></th>
<td><?php echo $fields[$key]['html'];?></td>
</tr>
<?php } } ?>
<tr>
<th>性别:</th>
<td>
<select id="gender" name="gender">
<option value="0">任意</option>
<option value="1">男</option>
<option value="2">女</option>
</select>
</td>
</tr>
<tr>
<th>年龄段</th>
<td><input type="text" name="startage" value="" size="10" class="px" style="width: 114px;" /> ~ <input type="text" name="endage" value="" size="10" class="px" style="width: 114px;" /></td>
</tr>
<tr>
<th>上传头像</th>
<td class="pcl"><label><input type="checkbox" name="avatarstatus" value="1" class="pc" />已经上传头像</label></td>
</tr>
<tr>
<th>用户名</th>
<td><input type="text" name="username" value="" class="px" /></td>
</tr>
<tr>
<th>&nbsp;</th>
<td>
<input type="hidden" name="searchsubmit" value="true" />
<button type="submit" class="pn"><em>查找</em></button>
</td>
</tr>
</table>
<input type="hidden" name="op" value="<?php echo $_GET['op'];?>" />
<input type="hidden" name="mod" value="spacecp" />
<input type="hidden" name="ac" value="search" />
<input type="hidden" name="type" value="base" />
</form>
</div>
<?php } elseif($_GET['op'] == 'reside' ) { ?>
<h2>查找同城的人</h2>
<div id="s_reside" class="bm bmn">
<form action="home.php" method="get">
<table cellpadding="0" cellspacing="0" class="tfm">
<tr>
<th>居住地</th>
<td id="residecitybox"><?php echo $residecityhtml;?></td>
</tr>
<tr>
<th>用户名</th>
<td><input type="text" name="username" value="" class="px" /></td>
</tr>
<tr>
<th>&nbsp;</th>
<td>
<input type="hidden" name="searchsubmit" value="true" />
<button type="submit" class="pn"><em>查找</em></button>
</td>
</tr>
</table>
<input type="hidden" name="op" value="<?php echo $_GET['op'];?>" />
<input type="hidden" name="mod" value="spacecp">
<input type="hidden" name="ac" value="search">
<input type="hidden" name="type" value="base">
</form>
</div>
<?php } elseif($_GET['op'] == 'birth' ) { ?>
<h2>查找老乡</h2>
<div id="s_birth" class="bm bmn">
<form action="home.php" method="get">
<table cellpadding="0" cellspacing="0" class="tfm">
<tr>
<th>出生地</th>
<td id="birthcitybox"><?php echo $birthcityhtml;?></td>
</tr>
<tr>
<th>用户名</th>
<td><input type="text" name="username" value="" class="px" /></td>
</tr>
<tr>
<th>&nbsp;</th>
<td>
<input type="hidden" name="searchsubmit" value="true" />
<button type="submit" class="pn"><em>查找</em></button>
</td>
</tr>
</table>
<input type="hidden" name="op" value="<?php echo $_GET['op'];?>" />
<input type="hidden" name="mod" value="spacecp" />
<input type="hidden" name="ac" value="search" />
<input type="hidden" name="type" value="base" />
</form>
</div>
<?php } elseif($_GET['op'] == 'birthyear' ) { ?>
<h2>查找同年同月同日生的人</h2>
<div id="s_birthyear" class="bm bmn">
<form action="home.php" method="get">
<table cellpadding="0" cellspacing="0" class="tfm">
<tr>
<th>生日</th>
<td>
<select id="birthyear" name="birthyear" onchange="showbirthday();" class="ps">
<option value="0">&nbsp;</option>
<?php echo $birthyeayhtml;?>
</select> 年
<select id="birthmonth" name="birthmonth" onchange="showbirthday();" class="ps">
<option value="0">&nbsp;</option>
<?php echo $birthmonthhtml;?>
</select> 月
<select id="birthday" name="birthday" class="ps">
<option value="0">&nbsp;</option>
<?php echo $birthdayhtml;?>
</select> 日
</td>
</tr>
<tr>
<th>用户名</th>
<td><input type="text" name="username" value="" class="px" /></td>
</tr>
<tr>
<th>&nbsp;</th>
<td>
<input type="hidden" name="searchsubmit" value="true" />
<button type="submit" class="pn"><em>查找</em></button>
</td>
</tr>
</table>
<input type="hidden" name="op" value="<?php echo $_GET['op'];?>" />
<input type="hidden" name="mod" value="spacecp" />
<input type="hidden" name="ac" value="search" />
<input type="hidden" name="type" value="base" />
</form>
</div>
<?php } elseif($_GET['op'] == 'edu' ) { if($fields['graduateschool'] || $fields['education']) { ?>
<h2>查找您的同学</h2>
<div id="s_edu" class="bm bmn">
<form action="home.php" method="get">
<table cellpadding="0" cellspacing="0" class="tfm"><?php if(is_array(array('graduateschool','education'))) foreach(array('graduateschool','education') as $key) { if($fields[$key]) { ?>
<tr>
<th><?php echo $fields[$key]['title'];?></th>
<td><?php echo $fields[$key]['html'];?></td>
</tr>
<?php } } ?>
<tr>
<th>用户名</th>
<td><input type="text" name="username" value="" class="px"></td>
</tr>
<tr>
<th>&nbsp;</th>
<td>
<input type="hidden" name="searchsubmit" value="true" />
<button type="submit" class="pn"><em>查找</em></button>
</td>
</tr>
</table>
<input type="hidden" name="op" value="<?php echo $_GET['op'];?>" />
<input type="hidden" name="mod" value="spacecp" />
<input type="hidden" name="ac" value="search" />
<input type="hidden" name="type" value="edu" />
</form>
</div>
<?php } } elseif($_GET['op'] == 'work' ) { if($fields['occupation'] || $fields['title']) { ?>
<h2>查找您的同事</h2>
<div id="s_work" class="bm bmn">
<form action="home.php" method="get">
<table cellpadding="0" cellspacing="0" class="tfm"><?php if(is_array(array('occupation','title'))) foreach(array('occupation','title') as $key) { if($fields[$key]) { ?>
<tr>
<th><?php echo $fields[$key]['title'];?></th>
<td><?php echo $fields[$key]['html'];?></td>
</tr>
<?php } } ?>
<tr>
<th>用户名</th>
<td><input type="text" name="username" value="" class="px" /></td>
</tr>
<tr>
<th>&nbsp;</th>
<td>
<input type="hidden" name="searchsubmit" value="true" />
<button type="submit" class="pn"><em>查找</em></button>
</td>
</tr>
</table>
<input type="hidden" name="op" value="<?php echo $_GET['op'];?>" />
<input type="hidden" name="mod" value="spacecp" />
<input type="hidden" name="ac" value="search" />
<input type="hidden" name="type" value="work" />
</form>
</div>
<?php } } else { ?>
<h2>高级方式查找</h2>
<div class="bm bmn">
<form action="home.php" method="get">
<table cellpadding="0" cellspacing="0" class="tfm">
<tr>
<th>用户名</th>
<td><input type="text" name="username" value="" class="px" /> <label><input type="checkbox" name="precision" class="pc" value="1">精确搜索</label></td>
</tr>
<tr>
<th>用户 UID</th>
<td><input type="text" name="uid" value="" class="px" /></td>
</tr>
<tr>
<th>性别:</th>
<td>
<select id="gender" name="gender">
<option value="0">任意</option>
<option value="1">男</option>
<option value="2">女</option>
</select>
</td>
</tr>
<tr>
<th>年龄段</th>
<td><input type="text" name="startage" value="" size="10" class="px" style="width: 114px;" /> ~ <input type="text" name="endage" value="" size="10" class="px" style="width: 114px;" /></td>
</tr>
<tr>
<th>上传头像</th>
<td class="pcl"><label><input type="checkbox" name="avatarstatus" value="1" class="pc" />已经上传头像</label></td>
</tr>
<tr>
<th>居住地</th>
<td id="residecitybox"><?php echo $residecityhtml;?></td>
</tr>
<tr>
<th>出生地</th>
<td id="birthcitybox"><?php echo $birthcityhtml;?></td>
</tr>
<tr>
<th>生日</th>
<td>
<select id="birthyear" name="birthyear" onchange="showbirthday();" class="ps">
<option value="0">&nbsp;</option>
<?php echo $birthyeayhtml;?>
</select> 年
<select id="birthmonth" name="birthmonth" onchange="showbirthday();" class="ps">
<option value="0">&nbsp;</option>
<?php echo $birthmonthhtml;?>
</select> 月
<select id="birthday" name="birthday" class="ps">
<option value="0">&nbsp;</option>
<?php echo $birthdayhtml;?>
</select> 日
</td>
</tr><?php if(is_array($fields)) foreach($fields as $fkey => $fvalue) { ?><tr>
<th><?php echo $fvalue['title'];?></th>
<td><?php echo $fvalue['html'];?></td>
</tr>
<?php } ?>
<tr>
<th>&nbsp;</th>
<td>
<input type="hidden" name="searchsubmit" value="true" />
<button type="submit" class="pn"><em>查找</em></button>
</td>
</tr>
</table>
<input type="hidden" name="op" value="<?php echo $_GET['op'];?>" />
<input type="hidden" name="mod" value="spacecp" />
<input type="hidden" name="ac" value="search" />
<input type="hidden" name="type" value="all" />
</form>
</div>
<?php } ?>
</div>
<?php } ?>
</div>
</div>
<div class="appl"><div class="tbn">
<h2 class="mt bbda">好友</h2>
<ul>
<li<?php echo $actives['me'];?>><a href="home.php?mod=space&amp;do=friend">好友列表</a></li>
<li<?php echo $actives['search'];?>><a href="home.php?mod=spacecp&amp;ac=search">查找好友</a></li>
<li<?php echo $actives['find'];?>><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=find">可能认识的人</a></li>
<?php if($_G['setting']['regstatus'] > 1) { ?>
<li<?php echo $actives['invite'];?>><a href="home.php?mod=spacecp&amp;ac=invite">邀请好友</a></li>
<?php } ?>
<li<?php echo $actives['request'];?>><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=request">好友请求</a></li>	
<li<?php echo $actives['group'];?>><a href="home.php?mod=spacecp&amp;ac=friend&amp;op=group">好友分组</a></li>
</ul>
</div></div>
</div><?php include template('common/footer'); ?>