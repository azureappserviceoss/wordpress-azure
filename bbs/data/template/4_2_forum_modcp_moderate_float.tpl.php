<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('modcp_moderate_float');?><?php include template('common/header'); ?><div class="f_c">
<h3 class="flb">
<em id="return_mods">
<?php if($modact == 'delete') { ?>删除<?php } elseif($modact == 'ignore') { ?>忽略<?php } elseif($modact == 'invalidate') { ?>否决<?php } else { ?>通过<?php } if($op == 'members') { ?>审核用户<?php } elseif($op == 'threads') { ?>审核主题<?php } elseif($op == 'replies') { ?>审核回复<?php } ?>
(<?php echo count($list); ?>)</em>
<span>
<?php if(!empty($_GET['infloat'])) { ?><a href="javascript:;" class="flbc" onclick="hideWindow('mods')" title="关闭">关闭</a><?php } ?>
</span>
</h3>

<form id="moderateform" method="post" autocomplete="off" action="<?php echo $cpscript;?>?mod=modcp&action=<?php echo $_GET['action'];?>&op=<?php echo $op;?>&infloat=yes" onsubmit="ajaxpost('moderateform', 'return_mods', 'return_mods', 'onerror');return false;">
<div class="c">
<?php if($list) { ?>
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="filter" value="<?php echo $filter;?>" />
<input type="hidden" name="modact" value="<?php echo $modact;?>" />
<input type="hidden" name="modsubmit" value="1" />
<?php if($op == 'replies') { $_GET['posttableid'] = intval($_GET['posttableid']);?><input type="hidden" name="posttableid" value="<?php echo $_GET['posttableid'];?>" />
<?php } if(!empty($_GET['infloat'])) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } if(is_array($list)) foreach($list as $id) { ?><input type="hidden" name="moderate[]" value="<?php echo $id;?>" />
<?php } ?>
<p>操作理由[可选]: </p>
<p><textarea name="reason" cols="50" rows="3" class="pt mtn"></textarea></p>
<?php } else { ?>
抱歉，您没有选择任何内容，请返回
<?php } ?>
</div>
<p class="o">
<button type="submit" name="modsubmit" id="modsubmit" class="pn pnc" value="true" tabindex="2"><strong>提交</strong></button>
<?php if($op=='members') { ?><label for="sendemail"><input type="checkbox" name="sendemail" id="sendemail" class="pc" value="1" />发 Email 通知被审核用户</label><?php } ?>
</p>
</form>
</div>

<script type="text/javascript" reload="1">
function succeedhandle_<?php echo $_GET['handlekey'];?>(locationhref) {<?php if(is_array($list)) foreach($list as $id) { ?>$('pidcheck_<?php echo $id;?>').parentNode.removeChild($('pidcheck_<?php echo $id;?>'));
$('pid_<?php echo $id;?>').style.display = 'none';
<?php } ?>
recountobj();
hideWindow('mods');
}
</script><?php include template('common/footer'); ?>