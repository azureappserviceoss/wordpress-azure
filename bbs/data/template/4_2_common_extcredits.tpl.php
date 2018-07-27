<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('extcredits');?><?php include template('common/header'); ?><div style="width:130px">
<ul class="bbda mbn pbn"><?php if(is_array($_G['setting']['extcredits'])) foreach($_G['setting']['extcredits'] as $extcreditid => $extcredit) { if(empty($extcredit['hiddeninheader'])) { ?>
<li><?php echo $extcredit['img'];?> <?php echo $extcredit['title'];?>: <span id="hcredit_<?php echo $extcreditid;?>"><?php echo getuserprofile('extcredits'.$extcreditid);; ?><?php echo $extcredit['unit'];?></span></li><?php } } ?>
<?php if(!empty($_G['setting']['pluginhooks']['spacecp_credit_extra'])) echo $_G['setting']['pluginhooks']['spacecp_credit_extra'];?>
</ul>
<?php if(($_G['setting']['ec_ratio'] && ($_G['setting']['ec_tenpay_opentrans_chnid'] || $_G['setting']['ec_tenpay_bargainor'] || $_G['setting']['ec_account'])) || $_G['setting']['card']['open']) { ?>
<div onclick="location.href='home.php?mod=spacecp&ac=credit&op=buy'" class="xi2 pbn" align="right"><label>立即充值&raquo;</label></div>
<?php } ?>
</div><?php include template('common/footer'); ?>