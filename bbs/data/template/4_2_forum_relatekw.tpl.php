<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('relatekw');?><?php include template('common/header'); if($return) { ?>
<script type="text/javascript">
var rnd='<?php echo TIMESTAMP; ?>';
var tagsplit = $('tags').value.split(',');
var inssplit = "<?php echo $return;?>";
var returnsplit = inssplit.split(',');
var result = '';
for(i in tagsplit) {
for(j in returnsplit) {
if(tagsplit[i] == returnsplit[j]) {
tagsplit[i] = '';break;
}
}
}
for(i in tagsplit) {
if(tagsplit[i] != '') {
result += tagsplit[i] + ',';
}
}
$('tags').value = result + "<?php echo $return;?>";
extraCheck(4);
</script>
<?php } include template('common/footer'); ?>