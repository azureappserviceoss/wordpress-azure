<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('space_index');?><?php include template('home/space_header'); ?><div id="ct" class="wp w cl">
<div id="diypage" class="area">
<div id="frame1" class="frame cl" noedit="1">
<div id="frame1_left" style="width:<?php echo $widths['0'];?>px" class="z column">
<?php if(empty($leftlist)) { ?>
<div id="left_temp" class="move-span temp"></div>
<?php } if(is_array($leftlist)) foreach($leftlist as $key => $value) { if(!empty($key)) { ?>
<div id="<?php echo $key;?>" class="block move-span">
<?php echo $value;?>
</div>
<?php } } ?>
</div>

<div id="frame1_center" style="width:<?php echo $widths['1'];?>px" class="z column">
<?php if(empty($centerlist)) { ?>
<div id="center_temp" class="move-span temp"></div>
<?php } if(is_array($centerlist)) foreach($centerlist as $key => $value) { if(!empty($key)) { ?>
<div id="<?php echo $key;?>" class="block move-span">
<?php echo $value;?>
</div>
<?php } } ?>
</div>

<?php if((strlen($userdiy['currentlayout']) > 3) ) { ?>
<div id="frame1_right" style="width:<?php echo $widths['2'];?>px" class="z column">
<?php if(empty($rightlist)) { ?>
<div id="right_temp" class="move-span temp"></div>
<?php } if(is_array($rightlist)) foreach($rightlist as $key => $value) { if(!empty($key)) { ?>
<div id="<?php echo $key;?>" class="block move-span">
<?php echo $value;?>
</div>
<?php } } ?>
</div>
<?php } ?>
</div>
</div>
</div>
<script type="text/javascript">
function succeedhandle_followmod(url, msg, values) {
var fObj = $('followmod');
if(values['type'] == 'add') {
fObj.innerHTML = '取消收听';
fObj.className = 'flw_btn_unfo';
fObj.href = 'home.php?mod=spacecp&ac=follow&op=del&fuid='+values['fuid'];
} else if(values['type'] == 'del') {
fObj.innerHTML = '收听TA';
fObj.className = 'flw_btn_fo';
fObj.href = 'home.php?mod=spacecp&ac=follow&op=add&hash=<?php echo FORMHASH;?>&fuid='+values['fuid'];
}
}
</script><?php include template('common/footer'); ?>