<?php

//add theme shop submenu
add_action('admin_menu','_daxiawp_theme_menu_page');	
function _daxiawp_theme_menu_page(){
	add_submenu_page( 'DX-auto-save-images','主题商城','daxiawp主题商城','manage_options','daxiawp_theme','_daxiawp_theme_form' );
	function _daxiawp_theme_form(){
?>

<style type="text/css">
	ul.theme{width:980px;}
	ul.theme li{width:300px; float:left; border:1px solid #ccc; padding:5px; margin-right:10px; margin-bottom:15px;}
</style>

<script type="text/javascript" src="http://cbjs.baidu.com/js/m.js"></script>
<?php 
	$codes=array(
		'454435',
		'454451',
		'454453',
		'454454',
		'454455',
		'454457',
		'454459',
		'454461',
		'454462'
	);
?>
<div class="wrap">

	<div id="icon-options-general" class="icon32"><br></div><h2>daxiawp主题</h2>
	
	<p>以下列出大侠wp最新制作的9个主题预览图，如果不显示，请暂停浏览器的广告过滤插件。浏览所有主题请访问<a href="http://www.daxiawp.com/wordpress-theme/all/" target="_blank">daxiawp</a>。</p>
	
	<ul class="theme">
		<?php for( $n=0; $n<9; $n++ ): ?>
		<li><script type="text/javascript">BAIDU_CLB_fillSlot("<?php echo $codes[$n]; ?>");</script></li>
		<?php endfor;?>
	</ul>
	
	<div style="clear:both;"></div>

</div>

<?php		
	}
}