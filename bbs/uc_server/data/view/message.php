<?php if(!defined('UC_ROOT')) exit('Access Denied');?>
<?php include $this->gettpl('header');?>
<?php if(empty($_REQUEST['inajax'])) { ?>
	<div class="container">
		<div class="ajax rtninfo">
			<div class="ajaxbg">
				<h4>提示信息:</h4>
				<p><?php echo $message;?></p>
				<?php if($redirect == 'BACK') { ?>
					<p><a href="###" onclick="history.back();">点击这里返回</a></p>
				<?php } elseif($redirect) { ?>
					<p><a href="<?php echo $redirect;?>">页面将在 3 秒后自动跳转到下一页，点击这里直接跳转...</a></p>
					<script type="text/javascript">
					function redirect(url, time) {
						setTimeout("window.location='" + url + "'", time * 1000);
					}
					redirect('<?php echo $redirect;?>', 3);
					</script>
				<?php } ?>
			</div>
		</div>
	</div>
<?php } else { ?>
	<?php echo $message;?>
<?php } ?>
<?php include $this->gettpl('footer');?>