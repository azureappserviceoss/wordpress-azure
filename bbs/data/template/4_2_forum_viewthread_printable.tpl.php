<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<html>
<head>
<title><?php echo $_G['forum_thread']['subject'];?> - <?php echo $_G['setting']['bbname'];?> - Powered by Discuz!</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>" />
<style type="text/css">
body 	   {margin: 10px 80px;}
body,table {font-size: <?php echo FONTSIZE;?>; font-family: <?php echo FONT;?>;}
h1 { font-size: 24px; margin-bottom: 20px; color: #999; }
</style>
<script src="<?php echo $_G['setting']['jspath'];?>common.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<script src="<?php echo $_G['setting']['jspath'];?>forum_viewthread.js?<?php echo VERHASH;?>" type="text/javascript"></script>
<script type="text/javascript">var STYLEID = '<?php echo STYLEID;?>', STATICURL = '<?php echo STATICURL;?>', IMGDIR = '<?php echo IMGDIR;?>', VERHASH = '<?php echo VERHASH;?>', charset = '<?php echo CHARSET;?>', discuz_uid = '<?php echo $_G['uid'];?>', cookiepre = '<?php echo $_G['config']['cookie']['cookiepre'];?>', cookiedomain = '<?php echo $_G['config']['cookie']['cookiedomain'];?>', cookiepath = '<?php echo $_G['config']['cookie']['cookiepath'];?>', showusercard = '<?php echo $_G['setting']['showusercard'];?>', attackevasive = '<?php echo $_G['config']['security']['attackevasive'];?>', disallowfloat = '<?php echo $_G['setting']['disallowfloat'];?>', creditnotice = '<?php if($_G['setting']['creditnotice']) { ?><?php echo $_G['setting']['creditnames'];?><?php } ?>', defaultstyle = '<?php echo $_G['style']['defaultextstyle'];?>', REPORTURL = '<?php echo $_G['currenturl_encode'];?>', SITEURL = '<?php echo $_G['siteurl'];?>', JSPATH = '<?php echo $_G['setting']['jspath'];?>';</script>
</head>

<body>
<h1><?php echo $_G['setting']['bbname'];?></h1>
<b>标题: </b><?php echo $_G['forum_thread']['subject'];?> <b><a href="###" onclick="this.style.visibility='hidden';window.print();this.style.visibility='visible'">[打印本页]</a></b></span><br />
<script type="text/javascript">var zoomstatus = 0;var aimgcount = new Array();</script><?php if(is_array($postlist)) foreach($postlist as $post) { ?><hr noshade size="2" width="100%" color="#808080">
<b>作者: </b><?php if($post['author'] && !$post['anonymous']) { ?><?php echo $post['author'];?><?php } else { ?>匿名<?php } ?>&nbsp; &nbsp; <b>时间: </b><?php echo $post['dateline'];?>
<br />
<?php if($_G['adminid'] != 1 && $_G['setting']['bannedmessages'] && (($post['authorid'] && !$userinfo[$post['authorid']]['username']) || ($userinfo[$post['authorid']]['groupid'] == 4 || $userinfo[$post['authorid']]['groupid'] == 5))) { ?>
提示: <em>作者被禁止或删除 内容自动屏蔽</em>
<?php } elseif($_G['adminid'] != 1 && $post['status'] & 1) { ?>
提示: <em>该帖被管理员或版主屏蔽</em>
<?php } elseif($post['first'] && $_G['forum_threadpay']) { ?>
本主题需向作者支付 <strong><?php echo $thread['price'];?> <?php echo $_G['setting']['extcredits'][$_G['setting']['creditstransextra']['1']]['unit'];?><?php echo $_G['setting']['extcredits'][$_G['setting']['creditstransextra']['1']]['title'];?></strong> 才能浏览
<?php } else { if($post['subject']) { ?><b>标题: </b><?php echo $post['subject'];?><br /><?php } ?>
<?php echo $post['message'];?>
<?php if($post['imagelist']) { echo showattach($post, 1); } if($post['attachlist']) { echo showattach($post); } } if(!empty($aimgs[$post['pid']])) { ?>
<script type="text/javascript" reload="1">
aimgcount[<?php echo $post['pid'];?>] = [<?php echo dimplode($aimgs[$post['pid']]);; ?>];
attachimggroup(<?php echo $post['pid'];?>);
<?php if(empty($_G['setting']['lazyload'])) { if(!$post['imagelistthumb']) { ?>
attachimgshow(<?php echo $post['pid'];?>);
<?php } else { ?>
attachimgshow(<?php echo $post['pid'];?>, 1);
<?php } } if($post['imagelistthumb']) { ?>
attachimglstshow(<?php echo $post['pid'];?>, <?php echo intval($_G['setting']['lazyload']), 0, 0; ?>);
<?php } if(!IS_ROBOT && !empty($_G['setting']['lazyload'])) { ?>
new lazyload();
<?php } ?>
</script>
<?php } } ?>

<br /><br /><br /><br /><hr noshade size="2" width="100%" color="<?php echo BORDERCOLOR;?>">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center" style="font-size: <?php echo SMFONTSIZE;?>; font-family: <?php echo SMFONT;?>">
<tr><td>欢迎光临 <?php echo $_G['setting']['bbname'];?> (<?php echo $_G['siteurl'];?>)</td>
<td align="right">
Powered by Discuz! <?php echo $_G['setting']['version'];?></td></tr></table>

</body>
</html>