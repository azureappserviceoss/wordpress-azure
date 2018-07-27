<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('switch_login');?><?php include template('common/header'); if(empty($_GET['infloat'])) { ?>
<div id="ct" class="wp w cl">
<div class="mn mw">
<?php } ?>

<div class="blr" id="main_messaqge_myrepeats" style="width:260px">
<div id="layer_login_myrepeats">
<h3 class="flb">
<em>请输入密码</em>
<span><?php if(!empty($_GET['infloat'])) { ?><a href="javascript:;" class="flbc" onclick="hideWindow('<?php echo $_GET['handlekey'];?>', 0, 1);" title="关闭">关闭</a><?php } ?></span>
</h3>
<form method="post" autocomplete="off" name="myrepeats" id="loginform_myrepeats" class="cl" action="plugin.php?id=myrepeats:switch&amp;myrepeatssubmit=yes&amp;handlekey=myrepeat" onsubmit="ajaxpost('loginform_myrepeats', 'returnmessage_myrepeats', 'returnmessage_myrepeats', 'onerror');return false;">
<div class="c cl">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="authorfirst" value="yes" />
<input type="hidden" name="referer" value="<?php echo $referer;?>" />
<div class="lgfm nlf" style="border:0;width:240px">
<p class="notice">
你是首次切换到此帐号，请输入密码
</p>
<div class="ftid sipt lpsw" id="account_myrepeats">
<label for="username">用户名</label>
<input type="text" name="username" id="username_myrepeats" autocomplete="off" size="36" class="txt" tabindex="1" value="<?php echo $username;?>" readonly />
</div>
<p class="sipt lpsw">
<label for="password3_myrepeats">密码</label>
<input type="password" id="password3_myrepeats" name="password" size="36" class="txt" tabindex="1" />
</p>

<div class="ftid sltp">
<select id="loginquestionid_myrepeats" width="213" name="questionid" change="if($('loginquestionid_myrepeats').value > 0) {$('loginanswer_myrepeats').style.display='';} else {$('loginanswer_myrepeats').style.display='none';}">
<option value="0">安全提问</option>
<option value="1">母亲的名字</option>
<option value="2">爷爷的名字</option>
<option value="3">父亲出生的城市</option>
<option value="4">您其中一位老师的名字</option>
<option value="5">您个人计算机的型号</option>
<option value="6">您最喜欢的餐馆名称</option>
<option value="7">驾驶执照的最后四位数字</option>
</select>
</div>
<p><input type="text" name="answer" id="loginanswer_myrepeats" style="display:none" autocomplete="off" size="36" class="sipt" tabindex="1" /></p>
<p id="returnmessage_myrepeats"></p>
</div>

</div>
<p class="fsb pns cl">
<button class="y pn pnc" type="submit" name="myrepeatssubmit" value="true" tabindex="1"><span>确定</span></button>
</p>
</form>
</div>
</div>

<script type="text/javascript" reload="1">
function initinput_login() {
document.body.focus();
if($('loginform_myrepeats')) {
$('loginform_myrepeats').password.focus();
}
simulateSelect('loginquestionid_myrepeats');
}
if(BROWSER.ie && BROWSER.ie < 7) {
setTimeout('initinput_login()', 500);
} else {
initinput_login();
}
</script>

<?php if(empty($_GET['infloat'])) { ?>
</div></div>
</div>
<?php } include template('common/footer'); ?>