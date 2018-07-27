<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<div class="bm"><div class="bm_h cl"><a href="javascript:;" onclick="$('ip_notice').style.display='none';setcookie('lip', '', -1);" class="y" title="关闭">关闭</a>
<h2>安全提醒</h2></div><div class="bm_c">
<dl class="xld cl bbda">
<dd>本次登录位置 <?php echo $status['lastip'];?> <?php echo $nowipConvert;?><br />上次登录位置 <?php echo $lip['0'];?> <?php echo $lastipConvert;?><br />上次登录时间 <?php echo $lastipDate;?></dd>
</dl>
<p class="ptn cl"><a href="home.php?mod=spacecp&amp;ac=profile&amp;op=password" onclick="$('ip_notice').style.display='none';setcookie('lip', '', -1);" class="xi2 y" target="_blank">登录地点异常，建议立即更改密码 &raquo;</a></p>
</div>
</div>
