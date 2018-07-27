<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('spacecp_credit_base');
0
|| checktplrefresh('./template/mahjong/home/spacecp_credit_base.htm', './template/mahjong/home/spacecp_header.htm', 1512620997, '2', './data/template/4_2_home_spacecp_credit_base.tpl.php', './template/mahjong', 'home/spacecp_credit_base')
|| checktplrefresh('./template/mahjong/home/spacecp_credit_base.htm', './template/mahjong/home/spacecp_credit_header.htm', 1512620997, '2', './data/template/4_2_home_spacecp_credit_base.tpl.php', './template/mahjong', 'home/spacecp_credit_base')
|| checktplrefresh('./template/mahjong/home/spacecp_credit_base.htm', './template/mahjong/common/seccheck.htm', 1512620997, '2', './data/template/4_2_home_spacecp_credit_base.tpl.php', './template/mahjong', 'home/spacecp_credit_base')
|| checktplrefresh('./template/mahjong/home/spacecp_credit_base.htm', './template/mahjong/home/spacecp_footer.htm', 1512620997, '2', './data/template/4_2_home_spacecp_credit_base.tpl.php', './template/mahjong', 'home/spacecp_credit_base')
|| checktplrefresh('./template/mahjong/home/spacecp_credit_base.htm', './template/mahjong/home/spacecp_header_name.htm', 1512620997, '2', './data/template/4_2_home_spacecp_credit_base.tpl.php', './template/mahjong', 'home/spacecp_credit_base')
|| checktplrefresh('./template/mahjong/home/spacecp_credit_base.htm', './template/mahjong/home/spacecp_header_name.htm', 1512620997, '2', './data/template/4_2_home_spacecp_credit_base.tpl.php', './template/mahjong', 'home/spacecp_credit_base')
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
<!--don't close the div here--><?php if(!empty($_G['setting']['pluginhooks']['spacecp_credit_top'])) echo $_G['setting']['pluginhooks']['spacecp_credit_top'];?><ul class="tb cl">
<li <?php echo $opactives['base'];?>><a href="home.php?mod=spacecp&amp;ac=credit&amp;op=base">我的积分</a></li>
<?php if($_G['setting']['ec_ratio'] && ($_G['setting']['ec_account'] || $_G['setting']['ec_tenpay_opentrans_chnid'] || $_G['setting']['ec_tenpay_bargainor']) || $_G['setting']['card']['open']) { ?>
<li <?php echo $opactives['buy'];?>><a href="home.php?mod=spacecp&amp;ac=credit&amp;op=buy">充值</a></li>
<?php } if($_G['setting']['transferstatus'] && $_G['group']['allowtransfer']) { ?>
<li <?php echo $opactives['transfer'];?>><a href="home.php?mod=spacecp&amp;ac=credit&amp;op=transfer">转帐</a></li>
<?php } if($_G['setting']['exchangestatus']) { ?>
<li <?php echo $opactives['exchange'];?>><a href="home.php?mod=spacecp&amp;ac=credit&amp;op=exchange">兑换</a></li>
<?php } ?>
<li <?php echo $opactives['log'];?>><a href="home.php?mod=spacecp&amp;ac=credit&amp;op=log">积分记录</a></li>
<li <?php echo $opactives['rule'];?>><a href="home.php?mod=spacecp&amp;ac=credit&amp;op=rule">积分规则</a></li>
<?php if(!empty($_G['setting']['plugins']['spacecp_credit'])) { if(is_array($_G['setting']['plugins']['spacecp_credit'])) foreach($_G['setting']['plugins']['spacecp_credit'] as $id => $module) { if(!$module['adminid'] || ($module['adminid'] && $_G['adminid'] > 0 && $module['adminid'] >= $_G['adminid'])) { ?><li<?php if($_GET['id'] == $id) { ?> class="a"<?php } ?>><a href="home.php?mod=spacecp&amp;ac=plugin&amp;op=credit&amp;id=<?php echo $id;?>"><?php echo $module['name'];?></a></li><?php } } } if($op == 'rule') { ?>
<li class="y">
<select onchange="location.href='home.php?mod=spacecp&ac=credit&op=rule&fid='+this.value"><option value="">全局规则</option><?php echo $select;?></select>
</li>
<?php } ?>
</ul><?php if(in_array($_GET['op'], array('base', 'buy', 'transfer', 'exchange'))) { ?>
<ul class="creditl mtm bbda cl"><?php $creditid=0;?><?php if($_GET['op'] == 'base' && $_G['setting']['creditstrans']) { $creditid=$_G['setting']['creditstrans'];?><?php if($_G['setting']['extcredits'][$creditid]) { $credit=$_G['setting']['extcredits'][$creditid];?><li class="xi1 cl"><em><?php if($credit['img']) { ?> <?php echo $credit['img'];?><?php } ?> <?php echo $credit['title'];?>: </em><?php echo getuserprofile('extcredits'.$creditid);; ?> <?php echo $credit['unit'];?> &nbsp; <?php if(($_G['setting']['ec_ratio'] && ($_G['setting']['ec_tenpay_opentrans_chnid'] || $_G['setting']['ec_tenpay_bargainor'] || $_G['setting']['ec_account'])) || $_G['setting']['card']['open']) { ?><a href="home.php?mod=spacecp&amp;ac=credit&amp;op=buy" class="xi2">立即充值&raquo;</a><?php } ?></li>
<?php } } if(is_array($_G['setting']['extcredits'])) foreach($_G['setting']['extcredits'] as $id => $credit) { if($id!=$creditid) { ?>
<li><em><?php if($credit['img']) { ?> <?php echo $credit['img'];?><?php } ?> <?php echo $credit['title'];?>: </em><?php echo getuserprofile('extcredits'.$id);; ?> <?php echo $credit['unit'];?></li>
<?php } } if($_GET['op'] == 'base') { ?>
<li class="cl"><em>积分: </em><?php echo $_G['member']['credits'];?> <span class="xg1">( <?php echo $creditsformulaexp;?> )</span></li>
<?php } ?>
<?php if(!empty($_G['setting']['pluginhooks']['spacecp_credit_extra'])) echo $_G['setting']['pluginhooks']['spacecp_credit_extra'];?>
</ul>
<?php } if($_GET['op'] == 'base') { ?>
<table summary="转账与兑换" cellspacing="0" cellpadding="0" class="dt mtm">
<caption>
<h2 class="mbm xs2">
<a href="home.php?mod=spacecp&amp;ac=credit&amp;op=log" class="xi2 xs1 xw0 y">查看更多&raquo;</a>积分记录
</h2>
</caption>
<tr>
<th width="80">操作</th>
<th width="80">积分变更</th>
<th>详情</th>
<th width="100">变更时间</th>
</tr>
<?php if($loglist) { if(is_array($loglist)) foreach($loglist as $value) { $value = makecreditlog($value, $otherinfo);?><tr>
<td><?php if($value['operation']) { ?><a href="home.php?mod=spacecp&amp;ac=credit&amp;op=log&amp;optype=<?php echo $value['operation'];?>"><?php echo $value['optype'];?></a><?php } else { ?><?php echo $value['title'];?><?php } ?></td>
<td><?php echo $value['credit'];?></td>
<td><?php if($value['operation']) { ?><?php echo $value['opinfo'];?><?php } else { ?><?php echo $value['text'];?><?php } ?></td>
<td><?php echo $value['dateline'];?></td>
</tr>
<?php } } else { ?>
<tr><td colspan="4"><p class="emp">目前没有积分交易记录</p></td></tr>
<?php } ?>
</table>

<?php } elseif($_GET['op'] == 'buy') { if(($_G['setting']['ec_ratio'] && ($_G['setting']['ec_account'] || $_G['setting']['ec_tenpay_opentrans_chnid'] || $_G['setting']['ec_tenpay_bargainor'])) || $_G['setting']['card']['open']) { ?>
<form id="addfundsform" name="addfundsform" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=credit&amp;op=buy" onsubmit="ajaxpost(this.id, 'return_addfundsform');">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="addfundssubmit" value="true" />
<input type="hidden" name="handlekey" value="buycredit" />
<table cellspacing="0" cellpadding="0" class="tfm mtn">
<tr>
<th>支付方式</th>
<td colspan="2">
<?php if($_G['setting']['ec_ratio'] && ($_G['setting']['ec_tenpay_bargainor'] || $_G['setting']['ec_tenpay_opentrans_chnid'])) { ?>
<div class="mbm pbn bbda cl">
<div id="div#tenpayBankList"></div><span id="#bank_type_value"></span>
<link rel="stylesheet" type="text/css" href="http://union.tenpay.com/bankList/css_col3.css" />
<script type="text/javascript">
$('div#tenpayBankList').html = function(){$('div#tenpayBankList').innerHTML = htmlString.replace(/<span.+?\/span>/g, ''); };
$("#bank_type_value").val = function(){<?php if($_G['setting']['card']['open']) { ?>$('cardbox').style.display='none';if($('card_box_sec')){$('card_box_sec').style.display='none';}$('paybox').style.display='';<?php } ?>};
appendscript('http://union.tenpay.com/bankList/bank.js', '');
</script>
</div>
<?php } ?>
<div class="long-logo mbw">
<ul>
<?php if($_G['setting']['ec_ratio'] && $_G['setting']['ec_account']) { ?>
<li class="z">
<input name="bank_type" type="radio" value="alipay" class="vm" id="apitype_alipay" <?php echo $ecchecked;?> onclick="checkValue(this)" /><label class="vm" style="margin-right:18px;width:135px;height:32px;background:#FFF url(<?php echo STATICURL;?>image/common/alipay_logo.gif) no-repeat;border:1px solid #DDD;display:inline-block;" onclick="<?php if($_G['setting']['card']['open']) { ?>$('cardbox').style.display='none';if($('card_box_sec')){$('card_box_sec').style.display='none';}$('paybox').style.display='';<?php } ?>" for="apitype_alipay"></label>
</li>
<?php } if($_G['setting']['card']['open']) { ?>
<li>
<input name="bank_type" type="radio" value="card" id="apitype_card" class="vm" <?php echo $ecchecked;?>  onclick="activatecardbox();" /><label class="vm" style="padding-left:10px;width:125px;height:32px;line-height:32px;background:#FFF;border:1px solid #DDD;display:inline-block;" onclick="activatecardbox();"><span class="xs2">充值卡充值</span></label>
</li>
<?php } ?>
</ul>
</div>
</td>
</tr>
<tr id="paybox" style="<?php if(($_G['setting']['ec_tenpay_bargainor'] || $_G['setting']['ec_tenpay_opentrans_chnid'] || $_G['setting']['ec_account']) && empty($ecchecked) ) { ?>display:;<?php } else { ?>display:none;<?php } ?>">
<th>充值</th>
<td class="pns">
<input type="text" size="5" class="px" style="width: auto;" id="addfundamount" name="addfundamount" value="0" onkeyup="addcalcredit()" />
&nbsp;<?php echo $_G['setting']['extcredits'][$_G['setting']['creditstrans']]['title'];?>&nbsp;
所需&nbsp;人民币 <span id="desamount">0</span> 元
</td>
<td width="300" class="d">
人民币现金 <strong>1</strong> 元 =  <strong><?php echo $_G['setting']['ec_ratio'];?></strong> <?php echo $_G['setting']['extcredits'][$_G['setting']['creditstrans']]['unit'];?><?php echo $_G['setting']['extcredits'][$_G['setting']['creditstrans']]['title'];?>
<?php if($_G['setting']['ec_mincredits']) { ?><br />单次最低充值  <strong><?php echo $_G['setting']['ec_mincredits'];?></strong> <?php echo $_G['setting']['extcredits'][$_G['setting']['creditstrans']]['unit'];?><?php echo $_G['setting']['extcredits'][$_G['setting']['creditstrans']]['title'];?><?php } if($_G['setting']['ec_maxcredits']) { ?><br />单次最高充值  <strong><?php echo $_G['setting']['ec_maxcredits'];?></strong> <?php echo $_G['setting']['extcredits'][$_G['setting']['creditstrans']]['unit'];?><?php echo $_G['setting']['extcredits'][$_G['setting']['creditstrans']]['title'];?><?php } if($_G['setting']['ec_maxcreditspermonth']) { ?><br />最近 30 天最高充值  <strong><?php echo $_G['setting']['ec_maxcreditspermonth'];?></strong> <?php echo $_G['setting']['extcredits'][$_G['setting']['creditstrans']]['unit'];?><?php echo $_G['setting']['extcredits'][$_G['setting']['creditstrans']]['title'];?><?php } ?>
</td>
</tr>
<?php if($_G['setting']['card']['open']) { ?>
<tr id="cardbox" style="<?php if($_G['setting']['card']['open'] && $ecchecked) { ?>display:;<?php } else { ?>display:none;<?php } ?>">
<th>充值卡</th>
<td colspan="2">
<input type="text" class="px" id="cardid" name="cardid" />
</td>
</tr>
<?php if($seccodecheck) { ?>
</table><?php
$sectpl = <<<EOF
<table id="card_box_sec" style="
EOF;
 if($_G['setting']['card']['open'] && $ecchecked) { 
$sectpl .= <<<EOF
display:;
EOF;
 } else { 
$sectpl .= <<<EOF
display:none;
EOF;
 } 
$sectpl .= <<<EOF
" cellspacing="0" cellpadding="0" class="tfm mtn"><tr><th><sec></th><td colspan="2"><span id="sec<hash>" onclick="showMenu({'ctrlid':this.id,'win':'{$_GET['handlekey']}'})"><sec></span><div id="sec<hash>_menu" class="p_pop p_opt" style="display:none"><sec></div></td></tr></table>
EOF;
?><?php $sechash = !isset($sechash) ? 'S'.($_G['inajax'] ? 'A' : '').$_G['sid'] : $sechash.random(3);
$sectpl = str_replace("'", "\'", $sectpl);?><?php if($secqaacheck) { ?>
<span id="secqaa_q<?php echo $sechash;?>"></span>		
<script type="text/javascript" reload="1">updatesecqaa('q<?php echo $sechash;?>', '<?php echo $sectpl;?>', '<?php echo $_G['basescript'];?>::<?php echo CURMODULE;?>');</script>
<?php } if($seccodecheck) { ?>
<span id="seccode_c<?php echo $sechash;?>"></span>		
<script type="text/javascript" reload="1">updateseccode('c<?php echo $sechash;?>', '<?php echo $sectpl;?>', '<?php echo $_G['basescript'];?>::<?php echo CURMODULE;?>');</script>
<?php } ?><table cellspacing="0" cellpadding="0" class="tfm mtn">
<?php } } ?>
<tr>
<th>&nbsp;</th>
<td colspan="2">
<button type="submit" name="addfundssubmit_btn" class="pn" id="addfundssubmit_btn" value="true"><em>充值</em></button>
</td>
</tr>

</table>
</form>
<span style="display: none" id="return_addfundsform"></span>
<script type="text/javascript">
function addcalcredit() {
var addfundamount = $('addfundamount').value.replace(/^0/, '');
var addfundamount = parseInt(addfundamount);
$('desamount').innerHTML = !isNaN(addfundamount) ? Math.ceil(((addfundamount / <?php echo $_G['setting']['ec_ratio'];?>) * 100)) / 100 : 0;
}
<?php if($_G['setting']['card']['open']) { ?>
function activatecardbox() {
$('apitype_card').checked=true;
$('cardbox').style.display='';
if($('card_box_sec')){
$('card_box_sec').style.display='';
}
$('paybox').style.display='none';
}
<?php } ?>
</script>
<?php } } elseif($_GET['op'] == 'transfer') { if($_G['setting']['transferstatus'] && $_G['group']['allowtransfer']) { ?>
<form id="transferform" name="transferform" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=credit&amp;op=transfer" onsubmit="ajaxpost(this.id, 'return_transfercredit');">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="transfersubmit" value="true" />
<input type="hidden" name="handlekey" value="transfercredit" />
<table cellspacing="0" cellpadding="0" class="tfm mtn">
<tr>
<th>转账</th>
<td class="pns">
<input type="text" name="transferamount" id="transferamount" class="px" size="5" style="width: auto;" value="0" />
&nbsp;<?php echo $_G['setting']['extcredits'][$_G['setting']['creditstransextra']['9']]['title'];?>&nbsp;
给&nbsp;
<input type="text" name="to" id="to" class="px" size="15" style="width: auto;" />
</td>
<td width="300" class="d">
转账后最低余额 <?php echo $_G['setting']['transfermincredits'];?> <?php echo $_G['setting']['extcredits'][$_G['setting']['creditstransextra']['9']]['unit'];?><br />
<?php if(intval($taxpercent) > 0) { ?>积分交易税 <?php echo $taxpercent;?><?php } ?>
</td>
</tr>
<tr>
<th><span class="rq">*</span>登录密码</th>
<td><input type="password" name="password" class="px" value="" /></td>
</tr>
<tr>
<th>转账留言</th>
<td><input type="text" name="transfermessage" class="px" size="40" /></td>
</tr>
<tr>
<th>&nbsp;</th>
<td colspan="2">
<button type="submit" name="transfersubmit_btn" id="transfersubmit_btn" class="pn" value="true"><em>转账</em></button>
<span style="display: none" id="return_transfercredit"></span>
</td>
</tr>
</table>
</form>
<?php } } elseif($_GET['op'] == 'exchange') { if($_G['setting']['exchangestatus'] && ($_G['setting']['extcredits'] || $_CACHE['creditsettings'])) { ?>
<form id="exchangeform" name="exchangeform" method="post" autocomplete="off" action="home.php?mod=spacecp&amp;ac=credit&amp;op=exchange&amp;handlekey=credit" onsubmit="showWindow('credit', 'exchangeform', 'post');">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="operation" value="exchange" />
<input type="hidden" name="exchangesubmit" value="true" />
<input type="hidden" name="outi" value="" />
<table cellspacing="0" cellpadding="0" class="tfm mtn">
<tr>
<th>兑换</th>
<td class="pns">
<input type="text" id="exchangeamount" name="exchangeamount" class="px" size="5" style="width: auto;" value="0" onkeyup="exchangecalcredit()" />
<select name="tocredits" id="tocredits" class="ps" onChange="exchangecalcredit()"><?php if(is_array($_G['setting']['extcredits'])) foreach($_G['setting']['extcredits'] as $id => $ecredits) { if($ecredits['allowexchangein'] && $ecredits['ratio']) { ?>
<option value="<?php echo $id;?>" unit="<?php echo $ecredits['unit'];?>" title="<?php echo $ecredits['title'];?>" ratio="<?php echo $ecredits['ratio'];?>"><?php echo $ecredits['title'];?></option>
<?php } } $i=0;?><?php if(is_array($_CACHE['creditsettings'])) foreach($_CACHE['creditsettings'] as $id => $data) { $i++;?><?php if($data['title']) { ?>
<option value="<?php echo $id;?>" outi="<?php echo $i;?>"><?php echo $data['title'];?></option>
<?php } } ?>
</select>
&nbsp;所需&nbsp;
<input type="text" id="exchangedesamount" class="px" size="5" style="width: auto;" value="0" disabled="disabled" />
<select name="fromcredits" id="fromcredits_0" class="ps" style="display: none" onChange="exchangecalcredit();"><?php if(is_array($_G['setting']['extcredits'])) foreach($_G['setting']['extcredits'] as $id => $credit) { if($credit['allowexchangeout'] && $credit['ratio']) { ?>
<option value="<?php echo $id;?>" unit="<?php echo $credit['unit'];?>" title="<?php echo $credit['title'];?>" ratio="<?php echo $credit['ratio'];?>"><?php echo $credit['title'];?></option>
<?php } } ?>
</select><?php $i=0;?><?php if(is_array($_CACHE['creditsettings'])) foreach($_CACHE['creditsettings'] as $id => $data) { $i++;?><select name="fromcredits_<?php echo $i;?>" id="fromcredits_<?php echo $i;?>" class="ps" style="display: none" onChange="exchangecalcredit()"><?php if(is_array($data['creditsrc'])) foreach($data['creditsrc'] as $id => $ratio) { ?><option value="<?php echo $id;?>" unit="<?php echo $_G['setting']['extcredits'][$id]['unit'];?>" title="<?php echo $_G['setting']['extcredits'][$id]['title'];?>" ratiosrc="<?php echo $data['ratiosrc'][$id];?>" ratiodesc="<?php echo $data['ratiodesc'][$id];?>"><?php echo $_G['setting']['extcredits'][$id]['title'];?></option>
<?php } ?>
</select>
<?php } ?>
<script type="text/javascript">
var tocredits = $('tocredits');
var fromcredits = $('fromcredits_0');
if(fromcredits.length > 1 && tocredits.value == fromcredits.value) {
fromcredits.selectedIndex = tocredits.selectedIndex + 1;
}
</script>
</td>
<td width="300" class="d">
<?php if($_G['setting']['exchangemincredits']) { ?>
兑换后最低余额 <?php echo $_G['setting']['exchangemincredits'];?><br />
<?php } ?>
<span id="taxpercent">
<?php if(intval($taxpercent) > 0) { ?>
积分交易税 <?php echo $taxpercent;?>
<?php } ?>
</span>
</td>
</tr>
<tr>
<th><span class="rq">*</span>登录密码</th>
<td colspan="2"><input type="password" name="password" class="px" value="" size="20" /></td>
</tr>
<tr>
<th>&nbsp;</th>
<td colspan="2">
<button type="submit" name="exchangesubmit_btn" id="exchangesubmit_btn" class="pn" value="true" tabindex="2"><em>兑换</em></button>
</td>
</tr>
</table>
</form>
<script type="text/javascript">
function exchangecalcredit() {
with($('exchangeform')) {
tocredit = tocredits[tocredits.selectedIndex];
if(!tocredit) {
return;
}<?php $i=0;?><?php if(is_array($_CACHE['creditsettings'])) foreach($_CACHE['creditsettings'] as $id => $data) { $i++;?>$('fromcredits_<?php echo $i;?>').style.display = 'none';
<?php } ?>
if(tocredit.getAttribute('outi')) {
outi.value = tocredit.getAttribute('outi');
fromcredit = $('fromcredits_' + tocredit.getAttribute('outi'));
$('taxpercent').style.display = $('fromcredits_0').style.display = 'none';
fromcredit.style.display = '';
fromcredit = fromcredit[fromcredit.selectedIndex];
$('exchangeamount').value = $('exchangeamount').value.toInt();
if($('exchangeamount').value != 0) {
$('exchangedesamount').value = Math.floor( fromcredit.getAttribute('ratiosrc') / fromcredit.getAttribute('ratiodesc') * $('exchangeamount').value);
} else {
$('exchangedesamount').value = '';
}
} else {
outi.value = 0;
$('taxpercent').style.display = $('fromcredits_0').style.display = '';
fromcredit = fromcredits[fromcredits.selectedIndex];
$('exchangeamount').value = $('exchangeamount').value.toInt();
if(fromcredit.getAttribute('title') != tocredit.getAttribute('title') && $('exchangeamount').value != 0) {
if(tocredit.getAttribute('ratio') < fromcredit.getAttribute('ratio')) {
$('exchangedesamount').value = Math.ceil( tocredit.getAttribute('ratio') / fromcredit.getAttribute('ratio') * $('exchangeamount').value * (1 + <?php echo $_G['setting']['creditstax'];?>));
} else {
$('exchangedesamount').value = Math.floor( tocredit.getAttribute('ratio') / fromcredit.getAttribute('ratio') * $('exchangeamount').value * (1 + <?php echo $_G['setting']['creditstax'];?>));
}
} else {
$('exchangedesamount').value = '';
}
}
}
}
String.prototype.toInt = function() {
var s = parseInt(this);
return isNaN(s) ? 0 : s;
}
exchangecalcredit();
</script>
<?php } } else { $_TPL['cycletype'] = array(
'0' => '一次性',
'1' => '每天',
'2' => '整点',
'3' => '间隔分钟',
'4' => '不限周期'
);?><div class="tbmu bw0">
<p>进行以下事件动作，会得到积分奖励。不过，在一个周期内，您最多得到的奖励次数有限制 </p>
</div>
<table cellspacing="0" cellpadding="0" class="dt valt">
<tr>
<th class="xw1">动作名称</th>
<th class="xw1">周期范围</th>
<th class="xw1">周期内最多奖励次数</th><?php if(is_array($_G['setting']['extcredits'])) foreach($_G['setting']['extcredits'] as $key => $value) { ?><th class="xw1"><?php echo $value['title'];?></th>
<?php } ?>
</tr><?php $i = 0;?><?php if(is_array($list)) foreach($list as $key => $value) { $i++;?><tr<?php if($i % 2 == 0) { ?> class="alt"<?php } ?>>
<td><?php echo $value['rulename'];?></td>
<td><?php echo $_TPL['cycletype'][$value['cycletype']];?></td>
<td><?php if($value['rewardnum']) { ?><?php echo $value['rewardnum'];?><?php } else { ?>不限次数<?php } ?></td><?php if(is_array($_G['setting']['extcredits'])) foreach($_G['setting']['extcredits'] as $key => $credit) { $creditkey = 'extcredits'.$key;?><td><?php if($value[$creditkey] > 0) { ?>+<?php echo $value[$creditkey];?><?php } elseif($value[$creditkey] < 0) { ?><?php echo $value[$creditkey];?><?php } else { ?>0<?php } ?></td>
<?php } ?>
</tr>
<?php } ?>
</table>
<?php } ?>
<?php if(!empty($_G['setting']['pluginhooks']['spacecp_credit_bottom'])) echo $_G['setting']['pluginhooks']['spacecp_credit_bottom'];?>
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